<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_organisasi_pegawai/business/mutasi_organisasi_pegawai.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';

class ViewMutasiOrganisasiPegawai extends HtmlResponse
   {
      function TemplateModule()
      {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_organisasi_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
         $this->SetTemplateFile('view_mutasi_organisasi_pegawai.html');
      }
      
      function ProcessRequest() 
      {
      $org = new MutasiOrganisasiPegawai();
      
      $msg = Messenger::Instance()->Receive(__FILE__);
      $this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
      
      $ObjDatPeg = new DataPegawai();
      $_GET['id'] = $ObjDatPeg->GetPegIdByUserName();
      
      $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      $dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
      $this->profilId=$id;
      
   
     // $arrjh = $org->GetComboJenisOrganisasi Pegawai();      
         //$tahun=array();
         if(isset($_GET['id'])){
         $dataPegawai = $org->GetDataDetail($id);
         $dataOrg = $org->GetListMutasiOrganisasiPegawai($id);
            if(isset($_GET['dataId'])){
               $dataMutasi = $org->GetDataMutasiById($id,$dataId);
               $result=$dataMutasi[0];
                  if(!empty($result)){
                     $return['input']['id'] = $result['id'];
                     $return['input']['nip'] = $result['nip'];
                     $return['input']['nama'] = $result['nama'];
                     $return['input']['jabatan'] = $result['jabatan'];
                     $return['input']['mulai'] = $result['mulai'];
					 $return['input']['upload'] = $result['upload'];
                     if ($result['selesai']=='0000') {
                           $return['input']['selesai'] = '';
                     }else {
                           $return['input']['selesai'] = $result['selesai'];
                     }
                     
			         }    
            }else{
            $return['input']['id'] = '';
            $return['input']['nip'] = '';
            $return['input']['nama'] = '';
            $return['input']['jabatan'] ='';
            $return['input']['mulai'] = '';
            $return['input']['selesai'] = '';
			$return['input']['upload'] = '';
            }
            
         }
         
         if ($_GET['aksi']=='ya'){
          $return['display_list']='none';
         }else{
          $return['display_form']='none';
         }
         
         /*
         if(empty($tahun['start'])){
	        $tahun['start']=date("Y")-25;
	        }
         $tahun['end'] = date("Y")+5;
         
      	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'mulai', array($return['input']['mulai'], $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
      	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'selesai', array($return['input']['selesai'], $tahun['start'], $tahun['end'], '', '', 'selesai'), Messenger::CurrentRequest);
	*/     
         $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'file_download_path');
        /* 
         Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis', array('jenis', $arrjh, $return['input']['jenis'], 'false', ' style="width:200px;" '), Messenger::CurrentRequest);
         $list_kat=array(array('id'=>'Ringan','name'=>'Ringan'),array('id'=>'Sedang','name'=>'Sedang'),array('id'=>'Berat','name'=>'Berat'));
	      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'kat', array('kat', $list_kat, $return['input']['kat'], 'false', 'id="kat "  '), Messenger::CurrentRequest);
         */
		 $list_org=$org->GetComboJenisOrganisasi();
	     Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_organisasi', array('jenis_organisasi', $list_org, $return['input']['jenis'], 'false', 'id="jenis_organisasi"'), Messenger::CurrentRequest);
         $return['dataPegawai'] = $dataPegawai;
  		   $return['dataOrg'] = $dataOrg;
  		   
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
      $dataOrg = $data['dataOrg'];
      //print_r($dataPegawai);
      //print_r($dataOrg);
      $this->mrTemplate->AddVar('content', 'TITLE', 'ORGANISASI PEGAWAI');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');
      $this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
      $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
      
      if ( isset($_GET['dataId'])) {
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_organisasi_pegawai', 'updateMutasiOrganisasiPegawai', 'do', 'html'));
      }else{
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_organisasi_pegawai', 'addMutasiOrganisasiPegawai', 'do', 'html'));
      }
      
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_organisasi_pegawai', 'Pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_organisasi_pegawai', 'MutasiOrganisasiPegawai', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
      $this->mrTemplate->AddVar('content', 'URL_TAMBAH', Dispatcher::Instance()->GetUrl('mutasi_organisasi_pegawai', 'MutasiOrganisasiPegawai', 'view', 'html').'&aksi=ya');
      $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('mutasi_organisasi_pegawai', 'MutasiOrganisasiPegawai', 'view', 'html'));
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
      
      if (empty($dataOrg)) {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
  
  
      $label = "Manajemen Data Mutasi Organisasi Pegawai";
      $urlDelete = Dispatcher::Instance()->GetUrl('mutasi_organisasi_pegawai', 'deleteMutasiOrganisasiPegawai', 'do', 'html');
      $urlReturn = Dispatcher::Instance()->GetUrl('mutasi_organisasi_pegawai', 'MutasiOrganisasiPegawai', 'view', 'html');
      Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
      $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

      $total=0;
      $start=1;
      for ($i=0; $i<count($dataOrg); $i++) {
         $no = $i+$start;
         $dataOrg[$i]['number'] = $no;
            if ($no % 2 != 0) {
            $dataOrg[$i]['class_name'] = 'table-common-even';
            }else{
            $dataOrg[$i]['class_name'] = '';
            }

      if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
      if($i == sizeof($dataIstri)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
      
      $idEnc = Dispatcher::Instance()->Encrypt($dataOrg[$i]['id']);
      $urlAccept = 'mutasi_organisasi_pegawai|deleteMutasiOrganisasiPegawai|do|html-id-'.$dataPegawai[0]['id'];
      $urlKembali = 'mutasi_organisasi_pegawai|MutasiOrganisasiPegawai|view|html-id-'.$dataPegawai[0]['id'];
      $label = 'Data Mutasi Organisasi Pegawai';
      $dataName = $dataOrg[$i]['nama'];
         $dataOrg[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
         $dataOrg[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_organisasi_pegawai','MutasiOrganisasiPegawai', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc.'&aksi=ya';
         $dataOrg[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_organisasi_pegawai','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         
         if (($dataOrg[$i]['selesai'])!== '0000'){
         $dataOrg[$i]['selesai'] = $dataOrg[$i]['selesai'];
         }
         else{
         $dataOrg[$i]['selesai'] = 'Sekarang';
         }
		 
         if (!empty($dataOrg[$i]['upload'])){
         $dataOrg[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataOrg[$i]['upload'];
         }
         else{
         $dataOrg[$i]['LINK_DOWNLOAD_SK'] = '';
         }
		 
      $this->mrTemplate->AddVars('data_item', $dataOrg[$i], 'ORG_');
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