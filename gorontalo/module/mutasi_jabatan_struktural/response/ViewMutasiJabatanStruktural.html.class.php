<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_jabatan_struktural/business/mutasi_jabatan_struktural.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';

class ViewMutasiJabatanStruktural extends HtmlResponse
   {
      function TemplateModule()
      {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_jabatan_struktural/'.GTFWConfiguration::GetValue('application', 'template_address').'');
         $this->SetTemplateFile('view_mutasi_jabatan_struktural.html');
      }
      
      function ProcessRequest() 
      {
      $js = new MutasiJabatanStruktural();
      
      $msg = Messenger::Instance()->Receive(__FILE__);
      $this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
      
      $ObjDatPeg = new DataPegawai();
      $_GET['id'] = $ObjDatPeg->GetPegIdByUserName();
      
      $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      $dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
      $this->profilId=$id;
      
      $arrjp = $js->GetComboJabatanStruktural();
      
         $tahun=array();
         if(isset($_GET['id'])){
         $dataPegawai = $js->GetDataDetail($id);
         $arrpktgol = $js->GetComboPangkatGolonganAll();
         $dataJabs = $js->GetListMutasiJabatanStruktural($id);
            if(isset($_GET['dataId'])){
               $dataMutasi = $js->GetDataMutasiById($id,$dataId);
               $result=$dataMutasi[0];
                  if(!empty($result)){
			            $return['input']['id'] = $result['id'];
                     $return['input']['nip'] = $result['nip'];
			            $return['input']['struktural'] = $result['struktural'];
			            $return['input']['eselon'] = $result['eselon'];
			            $return['input']['pktgolid'] = $result['pktgolid'];
			            $return['input']['mulai'] = $result['mulai'];
			            $return['input']['selesai'] = $result['selesai'];
			            $return['input']['pejabat'] = $result['pejabat'];
			            $return['input']['nosk'] = $result['nosk'];
			            $return['input']['tgl_sk'] = $result['tgl_sk'];
			            $return['input']['status'] = $result['status'];
			            $return['input']['upload'] = $result['upload'];
			            
			         }    
            }else{
            $return['input']['id'] = '';
            $return['input']['nip'] = '';
            $return['input']['struktural'] = '';
            $return['input']['eselon'] = '';
            $return['input']['pktgolid'] = '';
            $return['input']['mulai'] = date("Y-m-d");
            $return['input']['selesai'] = date("Y-m-d");
            $return['input']['pejabat'] = '';
            $return['input']['nosk'] = '';
            $return['input']['tgl_sk'] = date("Y-m-d");
            $return['input']['status'] = '';
            $return['input']['upload'] = '';
            $arrpktgol = $js->GetComboPangkatGolongan($id);
            }
            
         }
         
         if(empty($tahun['start'])){
	        $tahun['start']=date("Y")-25;
	        }
         $tahun['end'] = date("Y")+5;
         
      	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'mulai', array($return['input']['mulai'], $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
      	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'selesai', array($return['input']['selesai'], $tahun['start'], $tahun['end'], '', '', 'selesai'), Messenger::CurrentRequest);
	      Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_sk',array($return['input']['tgl_sk'], $tahun['start'], $tahun['end'], '', '', 'tgl_sk'), Messenger::CurrentRequest);
         
         $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'file_download_path');
         
         Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jabs_ref', array('jabs_ref', $arrjp, $return['input']['struktural'], '', ' style="width:280px;" '), Messenger::CurrentRequest);
         Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'golongan_ref', array('golongan_ref', $arrpktgol, $return['input']['pktgolid'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
         $list_status=array(array('id'=>'Aktif','name'=>'Aktif'),array('id'=>'Tidak Aktif','name'=>'Tidak Aktif'));
	      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status', $list_status, $return['input']['status'], '', 'id="status "  '), Messenger::CurrentRequest);
	      $list_eselon=array(array('id'=>'IA','name'=>'IA'),array('id'=>'IB','name'=>'IB'),array('id'=>'IIA','name'=>'IIA'),array('id'=>'IIB','name'=>'IIB'),array('id'=>'IIIA','name'=>'IIIA'),array('id'=>'IIIB','name'=>'IIIB'),array('id'=>'IVA','name'=>'IVA'),array('id'=>'IVB','name'=>'IVB'));
	      
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'eselon', array('eselon', $list_eselon, $return['input']['eselon'], 'false', 'id="eselon "  '), Messenger::CurrentRequest);
         $return['dataPegawai'] = $dataPegawai;
  		   $return['dataJabs'] = $dataJabs;
  		   //$aa = sizeof($dataJabs);
        //print_r($aa);
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
      $dataJabs = $data['dataJabs'];
      //print_r($dataPegawai);
      //print_r($dataJabs);
      $this->mrTemplate->AddVar('content', 'TITLE', 'Mutasi Jabatan Struktural');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');
      $this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? ' Batal ' : 'Reset');
      $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
      
      if ( isset($_GET['dataId'])) {
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_jabatan_struktural', 'updateMutasiJabatanStruktural', 'do', 'html'));
      }else{
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_jabatan_struktural', 'addMutasiJabatanStruktural', 'do', 'html'));
      }
      
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_jabatan_struktural', 'Pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_jabatan_struktural', 'MutasiJabatanStruktural', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
      $this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
      $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['kode']);
      $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['name']);
      $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
      $this->mrTemplate->AddVar('content', 'KAT', $dataPegawai[0]['kategori']);
     
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai[0]['foto']) | empty($dataPegawai[0]['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai[0]['foto']);
      }
      
      if(!empty($data['input'])){
         
         $data['input']['link_download']=$data['link']['link_download'].$data['input']['upload'];
		   $this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');
	  }
      
      if (empty($dataJabs)) {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
  
  
      $label = "Manajemen Mutasi Jabatan Struktural";
      $urlDelete = Dispatcher::Instance()->GetUrl('mutasi_jabatan_struktural', 'deleteMutasiJabatanStruktural', 'do', 'html');
      $urlReturn = Dispatcher::Instance()->GetUrl('mutasi_jabatan_struktural', 'MutasiJabatanStruktural', 'view', 'html');
      Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
      $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

      $total=0;
      $start=1;
      for ($i=0; $i<count($dataJabs); $i++) {
         $no = $i+$start;
         $dataJabs[$i]['number'] = $no;
            if ($no % 2 != 0) {
            $dataJabs[$i]['class_name'] = 'table-common-even';
            }else{
            $dataJabs[$i]['class_name'] = '';
            }

      if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
      if($i == sizeof($dataJabs)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
      
      $idEnc = Dispatcher::Instance()->Encrypt($dataJabs[$i]['id']);
      $urlAccept = 'mutasi_jabatan_struktural|deleteMutasiJabatanStruktural|do|html-id-'.$dataPegawai[0]['id'];
      $urlKembali = 'mutasi_jabatan_struktural|MutasiJabatanStruktural|view|html-id-'.$dataPegawai[0]['id'];
      $label = 'Data Mutasi Jabatan Struktural';
      $dataName = $dataJabs[$i]['jabstruk'];
         $dataJabs[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
         $dataJabs[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_jabatan_struktural','MutasiJabatanStruktural', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         $dataJabs[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_jabatan_struktural','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         $dataJabs[$i]['mulai'] = $this->date2string($dataJabs[$i]['mulai']);
         $dataJabs[$i]['selesai'] = $this->date2string($dataJabs[$i]['selesai']);
         if (!empty($dataJabs[$i]['upload'])){
         $dataJabs[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataJabs[$i]['upload'];
         }
         else{
         $dataJabs[$i]['LINK_DOWNLOAD_SK'] = '';
         }
         
      $this->mrTemplate->AddVars('data_item', $dataJabs[$i], 'JS_');
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