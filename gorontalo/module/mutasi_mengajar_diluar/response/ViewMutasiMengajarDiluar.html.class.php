<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_mengajar_diluar/business/mutasi_mengajar_diluar.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';

class ViewMutasiMengajarDiluar extends HtmlResponse
   {
      function TemplateModule()
      {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_mengajar_diluar/'.GTFWConfiguration::GetValue('application', 'template_address').'');
         $this->SetTemplateFile('view_mutasi_mengajar_diluar.html');
      }
      
      function ProcessRequest() 
      {
      $mgj = new MutasiMengajarDiluar();
      
      $msg = Messenger::Instance()->Receive(__FILE__);
      $this->Data = $msg[0][0];
		  $this->Pesan = $msg[0][1];
		  $this->css = $msg[0][2];
      
      $ObjDatPeg = new DataPegawai();
      $_GET['id'] = $ObjDatPeg->GetPegIdByUserName();
      
      $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      $dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
      $this->profilId=$id;
      
   
      //$arrjh = $mgj->GetComboJenisHukuman();      
         //$tahun=array();
         if(isset($_GET['id'])){
         $dataPegawai = $mgj->GetDataDetail($id);
         $dataMgj = $mgj->GetListMutasiMengajarDiluar($id);
            if(isset($_GET['dataId'])){
               $dataMutasi = $mgj->GetDataMutasiById($id,$dataId);
               $result=$dataMutasi[0];
                  if(!empty($result)){
                     $return['input']['id'] = $result['id'];
                     $return['input']['nip'] = $result['nip'];
                     $return['input']['univ'] = $result['univ'];
                     $return['input']['mk'] = $result['mk'];
                     $return['input']['status'] = $result['status'];
					 $return['input']['upload'] = $result['upload'];
			         }    
            }else{
            $return['input']['id'] = '';
            $return['input']['nip'] = '';
            $return['input']['univ'] = '';
            $return['input']['mk'] = '';
            $return['input']['status'] = '';
			$return['input']['upload'] = '';
            }
            
         }
         
         if ($_GET['aksi']=='ya'){
          $return['display_list']='none';
         }else{
          $return['display_form']='none';
         }
         
         if(empty($tahun['start'])){
	        $tahun['start']=date("Y")-25;
	        }
         $tahun['end'] = date("Y")+5;
         
      	//Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'mulai', array($return['input']['mulai'], $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
      	//Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'selesai', array($return['input']['selesai'], $tahun['start'], $tahun['end'], '', '', 'selesai'), Messenger::CurrentRequest);
	     $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'file_download_path');
         //Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis', array('jenis', $arrjh, $return['input']['jenis'], 'false', ' style="width:200px;" '), Messenger::CurrentRequest);
         $list_status=array(array('id'=>'Aktif','name'=>'Aktif'),array('id'=>'Tidak Aktif','name'=>'Tidak Aktif'));
	      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status', $list_status, $return['input']['status'], 'false', 'id="status "  '), Messenger::CurrentRequest);
         
         $return['dataPegawai'] = $dataPegawai;
  		   $return['dataMgj'] = $dataMgj;
  		   
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
      $dataMgj = $data['dataMgj'];
      //print_r($dataPegawai);
      //print_r($dataMgj);
      $this->mrTemplate->AddVar('content', 'TITLE', 'Dosen Mengajar Di Luar');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');
      $this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
      $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
      
      if ( isset($_GET['dataId'])) {
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_mengajar_diluar', 'updateMutasiMengajarDiluar', 'do', 'html'));
      }else{
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_mengajar_diluar', 'addMutasiMengajarDiluar', 'do', 'html'));
      }
      
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_mengajar_diluar', 'Pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_mengajar_diluar', 'MutasiMengajarDiluar', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
      $this->mrTemplate->AddVar('content', 'URL_TAMBAH', Dispatcher::Instance()->GetUrl('mutasi_mengajar_diluar', 'MutasiMengajarDiluar', 'view', 'html').'&aksi=ya');
      $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('mutasi_mengajar_diluar', 'MutasiMengajarDiluar', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'DISPLAY_LIST', $data['display_list']);
      $this->mrTemplate->AddVar('content', 'DISPLAY_FORM', $data['display_form']);
      
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
      
      if (empty($dataMgj)) {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
  
  
      $label = "Manajemen Data Mutasi Dosen Mengajar Diluar";
      $urlDelete = Dispatcher::Instance()->GetUrl('mutasi_mengajar_diluar', 'deleteMutasiMengajarDiluar', 'do', 'html');
      $urlReturn = Dispatcher::Instance()->GetUrl('mutasi_mengajar_diluar', 'MutasiMengajarDiluar', 'view', 'html');
      Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
      $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

      $total=0;
      $start=1;
      for ($i=0; $i<count($dataMgj); $i++) {
         $no = $i+$start;
         $dataMgj[$i]['number'] = $no;
            if ($no % 2 != 0) {
            $dataMgj[$i]['class_name'] = 'table-common-even';
            }else{
            $dataMgj[$i]['class_name'] = '';
            }

      if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
      if($i == sizeof($dataIstri)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
      
      $idEnc = Dispatcher::Instance()->Encrypt($dataMgj[$i]['id']);
      $urlAccept = 'mutasi_mengajar_diluar|deleteMutasiMengajarDiluar|do|html-id-'.$dataPegawai[0]['id'];
      $urlKembali = 'mutasi_mengajar_diluar|MutasiMengajarDiluar|view|html-id-'.$dataPegawai[0]['id'];
      $label = 'Data Mutasi Dosen Mengajar Diluar';
      $dataName = $dataMgj[$i]['univ'];
         $dataMgj[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
         $dataMgj[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_mengajar_diluar','MutasiMengajarDiluar', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc.'&aksi=ya';
         $dataMgj[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_mengajar_diluar','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         $dataMgj[$i]['mulai'] = $this->date2string($dataMgj[$i]['mulai']);
         $dataMgj[$i]['selesai'] = $this->date2string($dataMgj[$i]['selesai']);
         if (!empty($dataMgj[$i]['upload'])){
         $dataMgj[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataMgj[$i]['upload'];
         }
         else{
         $dataMgj[$i]['LINK_DOWNLOAD_SK'] = '';
         }
         
      $this->mrTemplate->AddVars('data_item', $dataMgj[$i], 'HKM_');
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