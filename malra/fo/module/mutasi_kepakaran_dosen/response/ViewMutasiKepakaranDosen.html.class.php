<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_kepakaran_dosen/business/mutasi_kepakaran_dosen.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_kepakaran_dosen/business/AppPopupBidang.class.php';

class ViewMutasiKepakaranDosen extends HtmlResponse
   {
      function TemplateModule()
      {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_kepakaran_dosen/'.GTFWConfiguration::GetValue('application', 'template_address').'');
         $this->SetTemplateFile('view_mutasi_kepakaran_dosen.html');
      }
      
      function ProcessRequest() 
      {
      $kd = new MutasiKepakaranDosen();
      
      $msg = Messenger::Instance()->Receive(__FILE__);
      $this->Data = $msg[0][0];
		  $this->Pesan = $msg[0][1];
		  $this->css = $msg[0][2];
		  
		  $ObjDatPeg = new DataPegawai();
      $_GET['id'] = $ObjDatPeg->GetPegIdByUserName();
      
      $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      $dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
      $this->profilId=$id;
      
      $Obj = new AppPopupBidang();
      $arrKomp = $Obj->GetComboBidang();
      $field=$_GET['field'];
      $kepakaran=$_GET['kepakaran'];
      $return['field']=$field;
      $arrKepakaran = $Obj->getComboKepakaran($return['field']);
      
      $url_itself=Dispatcher::Instance()->GetUrl('mutasi_kepakaran_dosen', 'MutasiKepakaranDosen', 'view', 'html');
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'nama', array('nama', $arrKomp, $field, "false", "id=\"nama\" onChange=\"js_next(this.form,'".$url_itself."')\""), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'bidang', array('bidang', $arrKepakaran, $kepakaran, "false", 'id="bidang" '), Messenger::CurrentRequest);
            
         //$tahun=array();
         if(isset($_GET['id'])){
         $dataPegawai = $kd->GetDataDetail($id);
         $dataBidang = $kd->GetListMutasiKepakaranDosen($id);
            if(isset($_GET['dataId'])){
               $dataMutasi = $kd->GetDataMutasiById($id,$dataId);
               $result=$dataMutasi[0];
                  if(!empty($result)){
			            $return['input']['id'] = $result['id'];
                     $return['input']['nip'] = $result['nip'];
			            $return['input']['bidang'] = $result['bidangid'];
			            $return['input']['bidanglabel'] = $result['bidanglabel'];
						$return['input']['upload'] = $result['upload'];
			         }    
            }else{
            $return['input']['id'] = '';
            $return['input']['nip'] = '';
            $return['input']['bidang'] = '';
            $return['input']['bidanglabel'] = '';
			$return['input']['upload'] = '';
            }
            
         }
         
         if ($_GET['aksi']=='ya'){
          $return['display_list']='none';
         }else{
          $return['display_form']='none';
         }
         //set the language
		 $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'file_download_path');
		 $lang=GTFWConfiguration::GetValue('application', 'button_lang');
		 $return['lang']=$lang;
	  
       $return['dataPegawai'] = $dataPegawai;
  		 $return['dataBidang'] = $dataBidang;
  		   
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
      $dataBidang = $data['dataBidang'];
      //print_r($dataPegawai);
      //print_r($dataBidang);
	  if($data['lang']=='eng') {
		$this->mrTemplate->AddVar('content', 'TITLE', "LECTURER EXPERTISE MUTATION");
		$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Edit' : 'Add');
		$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Cancel' : 'Reset');
		$this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');	
		$label = "Management of Lecturer Expertise Mutation Data";
      } else {
		$this->mrTemplate->AddVar('content', 'TITLE', "MUTASI BIDANG KEPAKARAN DOSEN");
		$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');
		$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
		$this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');	
		$label = "Manajemen Data Mutasi Kepakaran Dosen";
	  }
	  
	  $this->mrTemplate->AddVar('content', 'URL_POPUP_BIDANG', Dispatcher::Instance()->GetUrl('mutasi_kepakaran_dosen', 'popupBidang', 'view', 'html'));
      if ( isset($_GET['dataId'])) {
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_kepakaran_dosen', 'updateMutasiKepakaranDosen', 'do', 'html'));
      }else{
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_kepakaran_dosen', 'addMutasiKepakaranDosen', 'do', 'html'));
      }
      
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_kepakaran_dosen', 'Pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_kepakaran_dosen', 'MutasiKepakaranDosen', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
      $this->mrTemplate->AddVar('content', 'URL_TAMBAH', Dispatcher::Instance()->GetUrl('mutasi_kepakaran_dosen', 'MutasiKepakaranDosen', 'view', 'html').'&aksi=ya');
      $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('mutasi_kepakaran_dosen', 'MutasiKepakaranDosen', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'DISPLAY_LIST', $data['display_list']);
      $this->mrTemplate->AddVar('content', 'DISPLAY_FORM', $data['display_form']);
      
      $this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
      $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['kode']);
      $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['name']);
      $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
     
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$this->Data['foto']) | empty($this->Data['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai[0]['foto']);
      }
      
      
      if(!empty($data['input'])){
         
         $data['input']['link_download']=$data['link']['link_download'].$data['input']['upload'];
		   $this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');
	  }
      
      if (empty($dataBidang)) {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
  
  
      
			$urlDelete = Dispatcher::Instance()->GetUrl('mutasi_kepakaran_dosen', 'deleteMutasiKepakaranDosen', 'do', 'html');
			$urlReturn = Dispatcher::Instance()->GetUrl('mutasi_kepakaran_dosen', 'MutasiKepakaranDosen', 'view', 'html');
			Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
			$this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

			$total=0;
			$start=1;
			for ($i=0; $i<count($dataBidang); $i++) {
				$no = $i+$start;
				$dataBidang[$i]['number'] = $no;
				if ($no % 2 != 0) {
					$dataBidang[$i]['class_name'] = 'table-common-even';
				}else{
					$dataBidang[$i]['class_name'] = '';
				}

				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
				if($i == sizeof($dataIstri)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
      
				$idEnc = Dispatcher::Instance()->Encrypt($dataBidang[$i]['id']);
				$urlAccept = 'mutasi_kepakaran_dosen|deleteMutasiKepakaranDosen|do|html-id-'.$dataPegawai[0]['id'];
				$urlKembali = 'mutasi_kepakaran_dosen|MutasiKepakaranDosen|view|html-id-'.$dataPegawai[0]['id'];
      
				$dataName = $dataBidang[$i]['bidanglabel'];
				$dataBidang[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
				$dataBidang[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_kepakaran_dosen','MutasiKepakaranDosen', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc.'&field='. $dataBidang[$i]['field'].'&kepakaran='. $dataBidang[$i]['bidangid'].'&aksi=ya';
				$dataBidang[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_kepakaran_dosen','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
				if (!empty($dataBidang[$i]['upload'])){
				$dataBidang[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataBidang[$i]['upload'];
				}
				else{
				$dataBidang[$i]['LINK_DOWNLOAD_SK'] = '';
				}
         
				$this->mrTemplate->AddVars('data_item', $dataBidang[$i], 'KD_');
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
