<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_hukuman/business/mutasi_hukuman.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';

class ViewMutasiHukuman extends HtmlResponse
   {
      function TemplateModule()
      {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_hukuman/'.GTFWConfiguration::GetValue('application', 'template_address').'');
         $this->SetTemplateFile('view_mutasi_hukuman.html');
      }
      
      function ProcessRequest() 
      {
      $hkm = new MutasiHukuman();
      
      $msg = Messenger::Instance()->Receive(__FILE__);
      $this->Data = $msg[0][0];
		  $this->Pesan = $msg[0][1];
		  $this->css = $msg[0][2];
		
		  $ObjDatPeg = new DataPegawai();
      $_GET['id'] = $ObjDatPeg->GetPegIdByUserName();
      
      $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      $dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
      $this->profilId=$id;
      
   
      $arrjh = $hkm->GetComboJenisHukuman();      
         $tahun=array();
         if(isset($_GET['id'])){
         $dataPegawai = $hkm->GetDataDetail($id);
         $dataHkm = $hkm->GetListMutasiHukuman($id);
            if(isset($_GET['dataId'])){
               $dataMutasi = $hkm->GetDataMutasiById($id,$dataId);
               $result=$dataMutasi[0];
                  if(!empty($result)){
                     $return['input']['id'] = $result['id'];
                     $return['input']['nip'] = $result['nip'];
                     $return['input']['kat'] = $result['kat'];
                     $return['input']['jenis'] = $result['jenis'];
                     $return['input']['namahkm'] = $result['namahkm'];
                     $return['input']['mulai'] = $result['mulai'];
                     $return['input']['selesai'] = $result['selesai'];
					 $return['input']['upload'] = $result['upload'];
                     $return['input']['ket'] = $result['ket'];
			         }    
            }else{
            $return['input']['id'] = '';
            $return['input']['nip'] = '';
            $return['input']['kat'] = '';
            $return['input']['jenis'] = '';
            $return['input']['namahkm'] = '';
            $return['input']['mulai'] = date("Y-m-d");
            $return['input']['selesai'] = date("Y-m-d");
			$return['input']['upload'] = '';
            $return['input']['ket'] = '';
            }
            
         }
         
         if(empty($tahun['start'])){
	        $tahun['start']=date("Y")-25;
	        }
         $tahun['end'] = date("Y")+5;
         
      	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'mulai', array($return['input']['mulai'], $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
      	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'selesai', array($return['input']['selesai'], $tahun['start'], $tahun['end'], '', '', 'selesai'), Messenger::CurrentRequest);
	     
         
         $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'file_download_path');
         
         
         Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis', array('jenis', $arrjh, $return['input']['jenis'], 'false', ' style="width:200px;" '), Messenger::CurrentRequest);
         $list_kat=array(array('id'=>'Ringan','name'=>'Ringan'),array('id'=>'Sedang','name'=>'Sedang'),array('id'=>'Berat','name'=>'Berat'));
	      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'kat', array('kat', $list_kat, $return['input']['kat'], 'false', 'id="kat "  '), Messenger::CurrentRequest);
         
         $return['dataPegawai'] = $dataPegawai;
  		   $return['dataHkm'] = $dataHkm;
  		   
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
      $dataHkm = $data['dataHkm'];
      //print_r($dataPegawai);
      //print_r($dataHkm);
      $this->mrTemplate->AddVar('content', 'TITLE', 'Hukuman');
      //$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Edit' : 'Add');
      //$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
      //$this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
      
      if ( isset($_GET['dataId'])) {
         //$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_hukuman', 'updateMutasiHukuman', 'do', 'html'));
      }else{
         //$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_hukuman', 'addMutasiHukuman', 'do', 'html'));
      }
      
      //$this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_hukuman', 'Pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_hukuman', 'MutasiHukuman', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
      $this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
      $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['kode']);
      $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['name']);
      $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
      
      $this->Data=$dataPegawai[0];
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$this->Data['foto']) | empty($this->Data['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai[0]['foto']);
      }
      
      
      if(!empty($data['input'])){
         
         $data['input']['link_download']=$data['link']['link_download'].$data['input']['upload'];
		   $this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');
	  }
      
      if (empty($dataHkm)) {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
  
  
      $label = "Manajemen Data Mutasi Hukuman";
      $urlDelete = Dispatcher::Instance()->GetUrl('mutasi_hukuman', 'deleteMutasiHukuman', 'do', 'html');
      $urlReturn = Dispatcher::Instance()->GetUrl('mutasi_hukuman', 'MutasiHukuman', 'view', 'html');
      Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
      $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

      $total=0;
      $start=1;
      for ($i=0; $i<count($dataHkm); $i++) {
         $no = $i+$start;
         $dataHkm[$i]['number'] = $no;
            if ($no % 2 != 0) {
            $dataHkm[$i]['class_name'] = 'table-common-even';
            }else{
            $dataHkm[$i]['class_name'] = '';
            }

      if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
      if($i == sizeof($dataIstri)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
      
      $idEnc = Dispatcher::Instance()->Encrypt($dataHkm[$i]['id']);
      $urlAccept = 'mutasi_hukuman|deleteMutasiHukuman|do|html-id-'.$dataPegawai[0]['id'];
      $urlKembali = 'mutasi_hukuman|MutasiHukuman|view|html-id-'.$dataPegawai[0]['id'];
      $label = 'Data Mutasi Hukuman';
      $dataName = $dataHkm[$i]['namahkm'];
         $dataHkm[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
         $dataHkm[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_hukuman','MutasiHukuman', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         $dataHkm[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_hukuman','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         $dataHkm[$i]['mulai'] = $this->date2string($dataHkm[$i]['mulai']);
         $dataHkm[$i]['selesai'] = $this->date2string($dataHkm[$i]['selesai']);
         if (!empty($dataHkm[$i]['upload'])){
         $dataHkm[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataHkm[$i]['upload'];
         }
         else{
         $dataHkm[$i]['LINK_DOWNLOAD_SK'] = '';
         }
         
      $this->mrTemplate->AddVars('data_item', $dataHkm[$i], 'HKM_');
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