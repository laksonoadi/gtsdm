<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pangkat_golongan/business/mutasi_pangkat_golongan.class.php';

class ViewMutasiPangkatGolongan extends HtmlResponse
   {
      function TemplateModule()
      {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_pangkat_golongan/'.GTFWConfiguration::GetValue('application', 'template_address').'');
         $this->SetTemplateFile('view_mutasi_pangkat_golongan.html');
      }
      
      function ProcessRequest() 
      {
      	$pg = new MutasiPangkatGolongan();
      
      	$msg = Messenger::Instance()->Receive(__FILE__);
      	$this->Data = $msg[0][0];
	$this->Pesan = $msg[0][1];
	$this->css = $msg[0][2];
      
      	$id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      	$dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
      	$this->profilId=$id;
      
      	$arrjp = $pg->GetComboJenisPegawai();
      	$arrpktgol = $pg->GetComboPangkatGolongan();
      
        $tahun=array();
        if(isset($_GET['id'])){
        $dataPegawai = $pg->GetDataDetail($id);
        $dataPagol = $pg->GetListMutasiPangkatGolongan($id);
            if(isset($_GET['dataId'])){
               $dataMutasi = $pg->GetDataMutasiById($id,$dataId);
               $result=$dataMutasi[0];
                  if(!empty($result)){
			            $return['input']['id'] = $result['id'];
                     $return['input']['nip'] = $result['nip'];
			            $return['input']['golongan'] = $result['golongan'];
			            $return['input']['jenisid'] = $result['jenisid'];
			            $return['input']['tmt'] = $result['tmt'];
                  $return['input']['masa_thn'] = $result['masa_thn'];
                  $return['input']['masa_bln'] = $result['masa_bln'];
			            $return['input']['tgl_naik'] = $result['tgl_naik'];
			            $return['input']['pejabat'] = $result['pejabat'];
			            $return['input']['nosk'] = $result['nosk'];
			            $return['input']['tgl_sk'] = $result['tgl_sk'];
                  $return['input']['tgl_bkn'] = $result['tgl_bkn'];
                  $return['input']['gaji_pangkat'] = $result['gaji_pangkat'];
			            $return['input']['dasar'] = $result['dasar'];
			            $return['input']['status'] = $result['status'];
			            $return['input']['upload'] = $result['upload'];
			            $return['input']['pktgol'] = $result['pktgol'];
			         }    
            }else{
            $return['input']['id'] = '';
            $return['input']['nip'] = '';
			   $return['input']['golongan'] = '';
			   $return['input']['jenisid'] = '';
			   $return['input']['tmt'] = date("Y-m-d");
         $return['input']['masa_thn'] = '';
         $return['input']['masa_bln'] = '';
			   $return['input']['tgl_naik'] = date("Y-m-d",strtotime("+4 years $date"));
            $return['input']['pejabat'] = '';
            $return['input']['nosk'] = '';
            $return['input']['tgl_sk'] = date("Y-m-d");
            $return['input']['tgl_bkn'] = date("Y-m-d");
            $return['input']['gaji_pangkat'] = '';
            $return['input']['dasar'] = '';
            $return['input']['status'] = '';
            $return['input']['upload'] = '';
            $return['input']['pktgol'] = '';
            }
            
         }
         
        if(empty($tahun['start'])){
	        $tahun['start']=date("Y")-50;
	}
        	

	$tahun['end'] = date("Y")+5;

	//set the language
	$lang=GTFWConfiguration::GetValue('application', 'button_lang');
      	if ($lang=='eng'){
      		$labeldel=Dispatcher::Instance()->Encrypt('Grade Mutation');
		      $active = "Active"; $inactive = "Inactive";
      	}else{
      		$labeldel=Dispatcher::Instance()->Encrypt('Mutasi Pangkat/ Golongan');
		      $active = "Aktif"; $inactive = "Tidak Aktif";
      	}
      	$return['lang']=$lang;
         
	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tmt', array($return['input']['tmt'], $tahun['start'], $tahun['end'], '', '', 'tmt'), Messenger::CurrentRequest);
	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_naik', array($return['input']['tgl_naik'], $tahun['start'], $tahun['end'], '', '', 'tgl_naik'), Messenger::CurrentRequest);
	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_sk',array($return['input']['tgl_sk'], $tahun['start'], $tahun['end'], '', '', 'tgl_sk'), Messenger::CurrentRequest);
  Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_bkn',array($return['input']['tgl_bkn'], $tahun['start'], $tahun['end'], '', '', 'tgl_bkn'), Messenger::CurrentRequest);
         
        $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'baseaddress').GTFWConfiguration::GetValue( 'application', 'basedir').'upload_file/file/';
         
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_pegawai', array('jenis_pegawai', $arrjp, $return['input']['jenisid'], '', ' style="width:100px;" '), Messenger::CurrentRequest);
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'golongan_ref', array('golongan_ref', $arrpktgol, $return['input']['golongan'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
        $list_status=array(array('id'=>'Aktif','name'=>$active),array('id'=>'Tidak Aktif','name'=>$inactive));
	Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status', $list_status, $return['input']['status'], '', 'id="status " style="width:200px;" '), Messenger::CurrentRequest);
	Messenger::Instance()->SendToComponent('profile', 'profilebox', 'view', 'html', 'profile', array($id), Messenger::CurrentRequest);
    $return['dataPegawai'] = $dataPegawai;
  	$return['dataPagol'] = $dataPagol;


  		   
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
      $dataPagol = $data['dataPagol'];
      //print_r($dataPegawai);
      //print_r($dataPagol);
      
      if ($data['lang']=='eng'){
      	$this->mrTemplate->AddVar('content', 'TITLE', 'GRADE MUTATION');
      	$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Cancel' : 'Reset');
      	$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Add');
      	#$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
      }else{
      	$this->mrTemplate->AddVar('content', 'TITLE', 'MUTASI PANGKAT/GOLONGAN');
      	$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
      	$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah'); 
        #$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');   
      } 

      $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
      
      if ( isset($_GET['dataId'])) {
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_pangkat_golongan', 'updateMutasiPangkatGolongan', 'do', 'html'));
      }else{
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_pangkat_golongan', 'addMutasiPangkatGolongan', 'do', 'html'));
      }
      
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_pangkat_golongan', 'Pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_pangkat_golongan', 'MutasiPangkatGolongan', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
      $this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
      $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['kode']);
      $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['name']);
      $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
     
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai[0]['foto']) | empty($dataPegawai[0]['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai[0]['foto']);
      }
      
      if(!empty($data['input'])){
         
        $data['input']['link_download']=$data['link']['link_download'].$data['input']['upload'];
		    $this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');
	    }
      
      if (empty($dataPagol)) {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
  
  
      $label = "Grade Mutation";
      $urlDelete = Dispatcher::Instance()->GetUrl('mutasi_pangkat_golongan', 'deleteMutasiPangkatGolongan', 'do', 'html');
      $urlReturn = Dispatcher::Instance()->GetUrl('mutasi_pangkat_golongan', 'MutasipangkatGolongan', 'view', 'html');
      Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
      $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

      $total=0;
      $start=1;
      for ($i=0; $i<count($dataPagol); $i++) {
         $no = $i+$start;
         $dataPagol[$i]['number'] = $no;
            if ($no % 2 != 0) {
            $dataPagol[$i]['class_name'] = 'table-common-even';
            }else{
            $dataPagol[$i]['class_name'] = '';
            }

      if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
      if($i == sizeof($dataIstri)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
      
	     $idEnc = Dispatcher::Instance()->Encrypt($dataPagol[$i]['id']);
	     $urlAccept = 'mutasi_pangkat_golongan|deleteMutasiPangkatGolongan|do|html-id-'.$dataPegawai[0]['id'];
	     $urlKembali = 'mutasi_pangkat_golongan|MutasiPangkatGolongan|view|html-id-'.$dataPegawai[0]['id'];
	     $label = 'Data Mutasi Pangkat Golongan';
	     $dataName = $dataPagol[$i]['pktgol'];
         $dataPagol[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
         $dataPagol[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_pangkat_golongan','MutasiPangkatGolongan', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         $dataPagol[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_pangkat_golongan','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         $dataPagol[$i]['tmt'] = $this->date2string($dataPagol[$i]['tmt']);
         $dataPagol[$i]['tgl_sk'] = $this->date2string($dataPagol[$i]['tgl_sk']);
         if (!empty($dataPagol[$i]['upload'])){
			$dataPagol[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataPagol[$i]['upload'];
         }else{
			$dataPagol[$i]['LINK_DOWNLOAD_SK'] = '';
			$dataPagol[$i]['VIEW_DOWNLOAD'] = 'none';
         }
         
	if(($dataPagol[$i]['status']=='Aktif')&&($data['lang']=='eng')) {
		$dataPagol[$i]['status']="Active";
	} elseif(($dataPagol[$i]['status']=='Tidak Aktif')&&($data['lang']=='eng')) {
		$dataPagol[$i]['status']="Inactive";
	} else {
		$dataPagol[$i]['status']=$dataPagol[$i]['status'];
	}

      $this->mrTemplate->AddVars('data_item', $dataPagol[$i], 'PAGOL_');
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
