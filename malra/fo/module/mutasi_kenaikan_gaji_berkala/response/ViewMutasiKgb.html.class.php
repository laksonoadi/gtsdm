<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/mutasi_kenaikan_gaji_berkala/business/mutasi_kgb.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';


class ViewMutasiKgb extends HtmlResponse
   {
      function TemplateModule()
      {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_kenaikan_gaji_berkala/'.GTFWConfiguration::GetValue('application', 'template_address').'');
         $this->SetTemplateFile('view_mutasi_kgb.html');
      }
      
      function ProcessRequest() 
      {
      $kgb = new MutasiKgb();
      
      $msg = Messenger::Instance()->Receive(__FILE__);
      $this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
      $ObjDatPeg = new DataPegawai();
      $_GET['id'] = $ObjDatPeg->GetPegIdByUserName();
	  
      $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      $dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
      $this->profilId=$id;
            
         $tahun=array();
         if(isset($_GET['id'])){
         $dataPegawai = $kgb->GetDataDetail($id);
         $dataKgb = $kgb->GetListMutasiKgb($id);
            if(isset($_GET['dataId'])){
               $dataMutasi = $kgb->GetDataMutasiById($id,$dataId);
               $result=$dataMutasi[0];
                  if(!empty($result)){
			            $return['input']['id'] = $result['id'];
                     $return['input']['nip'] = $result['nip'];
			            $return['input']['pktgolid'] = $result['pktgolid'];
			            $return['input']['gapok'] = $result['gapok'];
			            $return['input']['masa_label'] = $result['masa_label'];
			            $return['input']['mulai'] = $result['mulai'];
			            $return['input']['yad'] = $result['yad'];
			            $return['input']['pejabat'] = $result['pejabat'];
			            $return['input']['tgl_sk'] = $result['tgl_sk'];
			            $return['input']['nosk'] = $result['nosk'];
			            $return['input']['status'] = $result['status'];
			            $return['input']['upload'] = $result['upload'];
			            $idKomp = $kgb->GetIdKomp($result['pktgolid'],$result['masa_label']);
			            $result2=$idKomp[0];
                  if(!empty($result2)){
			               $return['input2']['idK'] = $result2['id'];
                     $return['input2']['nomK'] = $result2['nominal'];
                  }else{
                     $return['input2']['idK'] = '';
                     $return['input2']['nomK'] = '';
                  }
                  $arrpktgol = $kgb->GetComboPangkatGolonganAll($result['pktgolid']);
			         }    
            }else{
            $return['input']['id'] = '';
            $return['input']['nip'] = '';
            $return['input']['pktgolid'] = '';
            $return['input']['gapok'] = '';
            $return['input']['masa_label'] = '';
            $return['input']['mulai'] = date("Y-m-d");
            $return['input']['yad'] = date("Y-m-d");
            $return['input']['pejabat'] = '';
            $return['input']['tgl_sk'] = date("Y-m-d");
            $return['input']['nosk'] = '';
            $return['input']['status'] = '';
            $return['input']['upload'] = '';
            $arrpktgol = $kgb->GetComboPangkatGolongan($id);

            }
            
         }
         
         //print_r($arrpktgol[0]['id']);
                  
         if(empty($tahun['start'])){
	        $tahun['start']=date("Y")-25;
	        }
         $tahun['end'] = date("Y")+8;
         
      	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'mulai', array($return['input']['mulai'], $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
      	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'yad', array($return['input']['yad'], $tahun['start'], $tahun['end'], '', '', 'yad'), Messenger::CurrentRequest);
	      Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_sk',array($return['input']['tgl_sk'], $tahun['start'], $tahun['end'], '', '', 'tgl_sk'), Messenger::CurrentRequest);
         
         $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'file_download_path');
         Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'golongan_ref', array('golongan_ref', $arrpktgol, $return['input']['pktgolid'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
         $list_status=array(array('id'=>'Aktif','name'=>'Active'),array('id'=>'Tidak Aktif','name'=>'Inactive'));
	      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status', $list_status, $return['input']['status'], '', 'id="status "  '), Messenger::CurrentRequest);
	      $return['dataPegawai'] = $dataPegawai;
  		   $return['dataKgb'] = $dataKgb;
  		   $return['pktgol'] = $arrpktgol[0]['id'];
  		   
  		   return $return;  
      }
      
      function ParseTemplate($data = NULL)
      {
       if($this->Pesan)
      {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      $dataPegawai = $data['dataPegawai'];
      $dataKgb = $data['dataKgb'];
      //print_r($dataPegawai);
      //print_r($dataKgb);
      
      $lang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($lang=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'PERIODICALLY SALARY RAISE MUTATION');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Add');
         $this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Cancel' : 'Reset');
         $label = "Periodically Salary Raise Mutation";
       }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'MUTASI KENAIKAN GAJI BERKALA');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');
         $this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
         $label = "Mutasi Kenaikan Gaji Berkala";
       }
      
      $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
      $this->mrTemplate->AddVar('content', 'URL_POPUP_MASA', Dispatcher::Instance()->GetUrl('mutasi_kenaikan_gaji_berkala', 'popupMasa', 'view', 'html').'&idMasa='.Dispatcher::Instance()->Encrypt($data['pktgol'])); 
      
      if ( isset($_GET['dataId'])) {
         //$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_kenaikan_gaji_berkala', 'updateMutasiKgb', 'do', 'html'));
      }else{
         //$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_kenaikan_gaji_berkala', 'addMutasiKgb', 'do', 'html'));
      }
      
      //$this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_kenaikan_gaji_berkala', 'Pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_kenaikan_gaji_berkala', 'MutasiKgb', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
      $this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
      $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['kode']);
      $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['name']);
      $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
      $this->mrTemplate->AddVar('content', 'IDNOM', $data['input2']['idK']);
      $this->mrTemplate->AddVar('content', 'NOM', $data['input2']['nomK']);
     
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai[0]['foto']) | empty($dataPegawai[0]['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai[0]['foto']);
      }
      
      if(!empty($data['input'])){
         
         $data['input']['link_download']=$data['link']['link_download'].$data['input']['upload'];
		   $this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');
	  }
      
      if (empty($dataKgb)) {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
  
      $urlDelete = Dispatcher::Instance()->GetUrl('mutasi_kenaikan_gaji_berkala', 'deleteMutasiKgb', 'do', 'html');
      $urlReturn = Dispatcher::Instance()->GetUrl('mutasi_kenaikan_gaji_berkala', 'deleteMutasiKgb', 'view', 'html');
      Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
      $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

      $total=0;
      $start=1;
      for ($i=0; $i<count($dataKgb); $i++) {
         $no = $i+$start;
         $dataKgb[$i]['number'] = $no;
            if ($no % 2 != 0) {
            $dataKgb[$i]['class_name'] = 'table-common-even';
            }else{
            $dataKgb[$i]['class_name'] = '';
            }

      if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
      if($i == sizeof($dataKgb)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
      
      $idEnc = Dispatcher::Instance()->Encrypt($dataKgb[$i]['id']);
      $urlAccept = 'mutasi_kenaikan_gaji_berkala|deleteMutasiKgb|do|html-id-'.$dataPegawai[0]['id'];
      $urlKembali = 'mutasi_kenaikan_gaji_berkala|MutasiKgb|view|html-id-'.$dataPegawai[0]['id'];
      $dataName = $dataKgb[$i]['pktgollabel'];
      
      $dataKgb[$i]['gapok'] = number_format($dataKgb[$i]['gapok'], 2, ',', '.');
      
       $dataKgb[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
       $dataKgb[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_kenaikan_gaji_berkala','MutasiKgb', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
       $dataKgb[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_kenaikan_gaji_berkala','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
       $dataKgb[$i]['mulai'] = $this->date2string($dataKgb[$i]['mulai']);
       $dataKgb[$i]['yad'] = $this->date2string($dataKgb[$i]['yad']);
       $dataKgb[$i]['tgl_sk'] = $this->date2string($dataKgb[$i]['tgl_sk']);
       if (!empty($dataKgb[$i]['upload'])){
       $dataKgb[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataKgb[$i]['upload'];
       }
       else{
       $dataKgb[$i]['LINK_DOWNLOAD_SK'] = '';
       }
         
      $this->mrTemplate->AddVars('data_item', $dataKgb[$i], 'JS_');
      $this->mrTemplate->parseTemplate('data_item', 'a');	 
  			}
  		  }
      }
      
      function date2string($date) {
	   $bln = array(
	            1  => '01',
					2  => '02',
					3  => '03',
					4  => '04',
					5  => '05',
					6  => '06',
					7  => '07',
					8  => '08',
					9  => '09',
					10 => '10',
					11 => '11',
					12 => '12'					
	               );
	   $arrtgl = explode('-',$date);
	   return $arrtgl[2].'/'.$bln[(int) $arrtgl[1]].'/'.$arrtgl[0];
	   
	}
   }
?>