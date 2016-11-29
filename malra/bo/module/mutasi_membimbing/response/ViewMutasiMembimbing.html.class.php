<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/mutasi_membimbing/business/mutasi_membimbing.class.php';

class ViewMutasiMembimbing extends HtmlResponse
   {
      function TemplateModule()
      {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_membimbing/'.GTFWConfiguration::GetValue('application', 'template_address').'');
         $this->SetTemplateFile('view_mutasi_membimbing.html');
      }
      
      function ProcessRequest() 
      {
		$mgj = new MutasiMembimbing();
      
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
      
		$id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
		$dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
		$this->profilId=$id;
      
   
         if(isset($_GET['id'])){
         $dataPegawai = $mgj->GetDataDetail($id);
         $dataMgj = $mgj->GetListMutasiMembimbing($id);
            if(isset($_GET['dataId'])){
               $dataMutasi = $mgj->GetDataMutasiById($id,$dataId);
               $result=$dataMutasi[0];
                  if(!empty($result)){
						$return['input']['id'] = $result['id'];
						$return['input']['nip'] = $result['nip'];
						$return['input']['semester'] = $result['semester'];
						$return['input']['nim_mhs'] = $result['nim_mhs'];
						$return['input']['nama_mhs'] = $result['nama_mhs'];
						$return['input']['jenis'] = $result['jenis'];
						$return['input']['judul_ta'] = $result['judul_ta'];
						$return['input']['status'] = $result['status'];
						$return['input']['upload'] = $result['upload'];
			         }    
            }else{
				$return['input']['id'] = '';
				$return['input']['nip'] = '';
				$return['input']['semester'] = '';
				$return['input']['nama_mhs'] = '';
				$return['input']['nim_mhs'] = '';
				$return['input']['jenis'] = '';
				$return['input']['judul_ta'] = '';
				$return['input']['status'] = '';
				$return['input']['upload'] = '';
            }
            
         }
         
         if(empty($tahun['start'])){
	        $tahun['start']=date("Y")-25;
	        }
         $tahun['end'] = date("Y")+5;
         
		//set the language
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
		if($lang=='eng') {
			$active="Active";$inactive="Inactive";
		} else {
			$active="Aktif";$inactive="Tidak Aktif";
		}
		
		$return['lang']=$lang; 
		 
	    $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'baseaddress').GTFWConfiguration::GetValue( 'application', 'basedir').'upload_file/file/';
        $list_status=array(array('id'=>'Aktif','name'=>$active),array('id'=>'Tidak Aktif','name'=>$inactive));
	    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status', $list_status, $return['input']['status'], '', 'id="status "  '), Messenger::CurrentRequest);
		Messenger::Instance()->SendToComponent('profile', 'profilebox', 'view', 'html', 'profile', array($id ), Messenger::CurrentRequest);
         
        
  		   
		//Ini Yang Integrasi Dengan gtAkademik
		$this->StatusIntegrasiAkademik=GTFWConfiguration::GetValue( 'application', 'status_integrasi_gtakademik');
		if ($this->StatusIntegrasiAkademik){
			$nomorkoneksi=GTFWConfiguration::GetValue( 'application', 'nomor_koneksi_gtakademik');
			$mgjIntegrasi = new MutasiMembimbing($nomorkoneksi);
			$mgjIntegrasi->connect();
			$dataMgj = $mgjIntegrasi->GetListMutasiMembimbingIntegrasi($dataPegawai[0]['kode']);
			$return['input']['display'] = 'none';
			$mgj->connect();
		}
		//==End Integrasi dengan gtAkademik
		
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
	  if($data['lang']=='eng') {
		$this->mrTemplate->AddVar('content', 'TITLE', 'TEACHING LECTURER MUTATION');
		$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Add');
		$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Cancel' : 'Reset');
		$this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
		$label = "Management of Teaching Lecturer Mutation Data";
		$label_data = 'Teaching Lecturer Mutation Data';
      } else {
		$this->mrTemplate->AddVar('content', 'TITLE', 'RIWAYAT DOSEN MEMBIMBING');
		$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');
		$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
		$this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
		$label = "Manajemen Data Mutasi Dosen Membimbing";
		$label_data = 'Data Mutasi Dosen Membimbing';
	  }
	  
      if ( isset($_GET['dataId'])) {
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_membimbing', 'updateMutasiMembimbing', 'do', 'html'));
      }else{
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_membimbing', 'addMutasiMembimbing', 'do', 'html'));
      }
      
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_membimbing', 'Pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_membimbing', 'MutasiMembimbing', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
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
      
      if (empty($dataMgj)) {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
  
      $urlDelete = Dispatcher::Instance()->GetUrl('mutasi_membimbing', 'deleteMutasiMembimbing', 'do', 'html');
      $urlReturn = Dispatcher::Instance()->GetUrl('mutasi_membimbing', 'MutasiMembimbing', 'view', 'html');
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
		 $urlAccept = 'mutasi_membimbing|deleteMutasiMembimbing|do|html-id-'.$dataPegawai[0]['id'];
         $urlKembali = 'mutasi_membimbing|MutasiMembimbing|view|html-id-'.$dataPegawai[0]['id'];
		 
		 if ($this->StatusIntegrasiAkademik){
			$dataMgj[$i]['display'] = 'none';
		 }
      
		 $dataName = '['.$dataMgj[$i]['kode_mk'].'] '.$dataMgj[$i]['nama_mk'].' Pada '.$dataMgj[$i]['semester'];
         $dataMgj[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label_data.'&dataName='.$dataName;
         $dataMgj[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_membimbing','MutasiMembimbing', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         $dataMgj[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_membimbing','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         //$dataMgj[$i]['mulai'] = $this->date2string($dataMgj[$i]['mulai']);
         //$dataMgj[$i]['selesai'] = $this->date2string($dataMgj[$i]['selesai']);
         if (!empty($dataMgj[$i]['upload'])){
			$dataMgj[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataMgj[$i]['upload'];
         }
         else{
			$dataMgj[$i]['LINK_DOWNLOAD_SK'] = '';
         }
         
         if($data['lang']=='eng') {
            if($dataMgj[$i]['status'] == 'Aktif'){ $dataMgj[$i]['status'] = "Active";}
            if($dataMgj[$i]['status'] == 'Tidak Aktif'){ $dataMgj[$i]['status'] = "Inactive";}
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