<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/mutasi_seminar/business/mutasi_seminar.class.php';

class ViewMutasiSeminar extends HtmlResponse
   {
      function TemplateModule()
      {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_seminar/'.GTFWConfiguration::GetValue('application', 'template_address').'');
         $this->SetTemplateFile('view_mutasi_seminar.html');
      }
      
      function ProcessRequest() 
      {
      $sem = new MutasiSeminar();
      
      $msg = Messenger::Instance()->Receive(__FILE__);
      $this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
      
      $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      $dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
      $this->profilId=$id;
      
      $arrts = $sem->GetComboTingkatSeminar();
        
         $tahun=array();
         if(isset($_GET['id'])){
         $dataPegawai = $sem->GetDataDetail($id);
         $dataSem = $sem->GetListMutasiSeminar($id);
            if(isset($_GET['dataId'])){
               $dataMutasi = $sem->GetDataMutasiById($id,$dataId);
               $result=$dataMutasi[0];
                  if(!empty($result)){
                     $return['input']['id'] = $result['id'];
                     $return['input']['nip'] = $result['nip'];
                     $return['input']['nama'] = $result['nama'];
                     $return['input']['tingkatid'] = $result['tingkatid'];
                     $return['input']['peranan'] = $result['peranan'];
                     $return['input']['mulai'] = $result['mulai'];
                     $return['input']['penyelenggara'] = $result['penyelenggara'];
                     $return['input']['tempat'] = $result['tempat'];
                     $return['input']['tingkatlabel'] = $result['tingkatlabel'];
					 $return['input']['upload'] = $result['upload'];
			         }    
            }else{
            $return['input']['id'] = '';
            $return['input']['nip'] = '';
            $return['input']['nama'] = '';
            $return['input']['tingkatid'] = '';
            $return['input']['peranan'] = '';
            $return['input']['mulai'] = date("Y-m-d");
            $return['input']['penyelenggara'] = '';
            $return['input']['tempat'] = '';
            $return['input']['tingkatlabel'] = ''; 
			$return['input']['upload'] = '';
            }
            
         }
         
         if(empty($tahun['start'])){
	        $tahun['start']=date("Y")-25;
	        }
         $tahun['end'] = date("Y")+5;
         
      	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'mulai', array($return['input']['mulai'], $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
      	//Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'selesai', array($return['input']['selesai'], $tahun['start'], $tahun['end'], '', '', 'selesai'), Messenger::CurrentRequest);
	     
         $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'baseaddress').GTFWConfiguration::GetValue( 'application', 'basedir').'upload_file/file/';
         Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tingkat', array('tingkat', $arrts, $return['input']['tingkatid'], 'false', ' style="width:200px;" '), Messenger::CurrentRequest);
		 
		 Messenger::Instance()->SendToComponent('profile', 'profilebox', 'view', 'html', 'profile', array($id ), Messenger::CurrentRequest);
         
         $lang=GTFWConfiguration::GetValue('application', 'button_lang');
         $return['lang']=$lang;
      
         $return['dataPegawai'] = $dataPegawai;
  		   $return['dataSem'] = $dataSem;
  		   
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
      $dataSem = $data['dataSem'];
      //print_r($dataPegawai);
      //print_r($dataSem);
      
      if($data['lang'] = 'eng'){
        $this->mrTemplate->AddVar('content', 'TITLE', 'RIWAYAT SEMINAR');
        $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');
        $this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
        $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
      } else {
        $this->mrTemplate->AddVar('content', 'TITLE', 'MUTASI SEMINAR');
        $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');
        $this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
        $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
      }
            
      if ( isset($_GET['dataId'])) {
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_seminar', 'updateMutasiSeminar', 'do', 'html'));
      }else{
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_seminar', 'addMutasiSeminar', 'do', 'html'));
      }
      
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_seminar', 'Pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_seminar', 'MutasiSeminar', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
      $this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
      $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['kode']);
      $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['name']);
      $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
      $this->Data['foto']=$dataPegawai[0]['foto'];
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$this->Data['foto']) | empty($this->Data['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai[0]['foto']);
      }
      
      if(!empty($data['input'])){
         $data['input']['link_download']=$data['link']['link_download'].$data['input']['upload'];
		   $this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');
	  }
      
      if (empty($dataSem)) {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
  
      if($data['lang'] = 'eng'){
        $label = "Seminars Mutation Data Management";
      } else {
        $label = "Manajemen Data Mutasi Seminar";
      }
      $urlDelete = Dispatcher::Instance()->GetUrl('mutasi_seminar', 'deleteMutasiSeminar', 'do', 'html');
      $urlReturn = Dispatcher::Instance()->GetUrl('mutasi_seminar', 'MutasiSeminar', 'view', 'html');
      Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
      $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

      $total=0;
      $start=1;
      for ($i=0; $i<count($dataSem); $i++) {
         $no = $i+$start;
         $dataSem[$i]['number'] = $no;
            if ($no % 2 != 0) {
            $dataSem[$i]['class_name'] = 'table-common-even';
            }else{
            $dataSem[$i]['class_name'] = '';
            }

      if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
      if($i == sizeof($dataIstri)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
      
      $idEnc = Dispatcher::Instance()->Encrypt($dataSem[$i]['id']);
      $urlAccept = 'mutasi_seminar|deleteMutasiSeminar|do|html-id-'.$dataPegawai[0]['id'];
      $urlKembali = 'mutasi_seminar|MutasiSeminar|view|html-id-'.$dataPegawai[0]['id'];
      #$label = 'Data Mutasi Seminar';
      $dataName = $dataSem[$i]['nama'];
         $dataSem[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
         $dataSem[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_seminar','MutasiSeminar', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         $dataSem[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_seminar','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         $dataSem[$i]['mulai'] = $this->date2string($dataSem[$i]['mulai']);
         $dataSem[$i]['selesai'] = $this->date2string($dataSem[$i]['selesai']);
         if (!empty($dataSem[$i]['upload'])){
			$dataSem[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataSem[$i]['upload'];
         }else{
			$dataSem[$i]['LINK_DOWNLOAD_SK'] = '';
			$dataSem[$i]['VIEW_DOWNLOAD'] = 'none';
         }
         
      $this->mrTemplate->AddVars('data_item', $dataSem[$i], 'PEL_');
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