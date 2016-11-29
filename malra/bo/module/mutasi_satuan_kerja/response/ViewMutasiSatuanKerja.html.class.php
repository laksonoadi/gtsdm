<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/mutasi_satuan_kerja/business/mutasi_satuan_kerja.class.php';

require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';

require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_jabatan_struktural/business/mutasi_jabatan_struktural.class.php';
class ViewMutasiSatuanKerja extends HtmlResponse
{
  function TemplateModule()
  {
    $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
    'module/mutasi_satuan_kerja/'.GTFWConfiguration::GetValue('application', 'template_address').'');
    $this->SetTemplateFile('view_mutasi_satuan_kerja.html');
  }
      
  function ProcessRequest() 
  {
   	$pg = new MutasiSatuanKerja();
   	$objSatker = new SatuanKerja();
      
   	$msg = Messenger::Instance()->Receive(__FILE__);
   	$this->Data = isset($msg[0][0]) ? $msg[0][0] : NULL;
	  $this->Pesan = isset($msg[0][1]) ? $msg[0][1] : NULL;
	  $this->css = isset($msg[0][2]) ? $msg[0][2] : NULL;
      
    $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
    $dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
    $this->profilId=$id;
      
    $arrsatker = $objSatker->GetComboSatuanKerja();
    $arrjabstruk = $pg->GetComboJabatanStruktural();
    $arrjenpeg =  $objSatker->GetListJenisKepegawaian();
    $arrPangkat = $pg->GetComboPangkatGolonganAll();


    // print_r($arrPangkat);
    
    $tahun=array();

    if(isset($_GET['id'])){
      $dataPegawai = $pg->GetDataDetail($id);
      $dataSatker = $pg->GetListMutasiSatuanKerja($id);
      if(isset($_GET['dataId'])){
        $dataMutasi = $pg->GetDataMutasiById($id,$dataId);
        
        print_r($pangkatName);        
        $result=$dataMutasi['0'];       
        if(!empty($result)){
		      $return['input']['id'] = $result['id'];
          $return['input']['nip'] = $result['nip'];
		      $return['input']['satker'] = $result['satker'];
          $return['input']['old_satker'] = $result['old_satker'];
          $return['input']['jenpeg'] = $result['jenpeg'];
          $return['input']['satker_pangkat'] = $result['satker_pangkat'];
          $return['input']['ref_jab'] = $result['ref_jab'];
		      $return['input']['tmt'] = $result['tmt'];
		      $return['input']['pejabat'] = $result['pejabat'];
		      $return['input']['nosk'] = $result['nosk'];
		      $return['input']['tgl_sk'] = $result['tgl_sk'];
		      $return['input']['status'] = $result['status'];
		      $return['input']['upload'] = $result['upload'];
          $return['input']['tugas'] = $result['tugas'];
		    }    
      }else{
        $return['input']['id'] = '';
        $return['input']['nip'] = '';
		    $return['input']['satker'] = '';
        $return['input']['old_satker'] = '';
        $return['input']['jenpeg'] = '';
        $return['input']['satker_pangkat'] = '';
        $return['input']['ref_jab'] = '';
			  $return['input']['tmt'] = date("Y-m-d");
        $return['input']['pejabat'] = '';
        $return['input']['nosk'] = '';
        $return['input']['tgl_sk'] = date("Y-m-d");
        $return['input']['status'] = '';
        $return['input']['upload'] = '';
        $return['input']['pktgol'] = '';
        $return['input']['tugas'] = '';
      }
    }
    if(empty($tahun['start'])){
	    $tahun['start']=date("Y")-25;
	  }        	

	  $tahun['end'] = date("Y")+5;
    
    // $arrjabstruk = $pg->GetJabBySatker($return['input']['satker']);

	  //set the language
	  $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    if ($lang=='eng'){
      $labeldel=Dispatcher::Instance()->Encrypt('Working Unit Mutation');
		  $active = "Active"; $inactive = "Inactive";
    }else{
    	$labeldel=Dispatcher::Instance()->Encrypt('Mutasi Satuan Kerja');
		  $active = "Aktif"; $inactive = "Tidak Aktif";
    }
    $return['lang']=$lang;
    
    $dataDir = $pg->GetDataAtas($this->Data['dirspv']);
  	$dataMor = $pg->GetDataAtas($this->Data['mor']);
  	$return['atasan']['namaDir'] = $dataDir['nama'];
  	$return['atasan']['satDir'] = $dataDir['namaSat'];
  	$return['atasan']['namaMor'] = $dataMor['nama'];
  	$return['atasan']['satMor'] = $dataMor['namaSat'];
       
	  Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tmt', array($return['input']['tmt'], $tahun['start'], $tahun['end'], '', '', 'tmt'), Messenger::CurrentRequest);
	  Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_sk',array($return['input']['tgl_sk'], $tahun['start'], $tahun['end'], '', '', 'tgl_sk'), Messenger::CurrentRequest);
    // print_r($return['input']);
    $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'baseaddress').GTFWConfiguration::GetValue( 'application', 'basedir').'upload_file/file/';
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'satker', array('satker', $arrsatker, $return['input']['satker'], 'false', ' style="width:700px;"  onchange="setDS()" '), Messenger::CurrentRequest);
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenpeg', array('jenpeg', $arrjenpeg, $return['input']['jenpeg'], 'false', ' style="width:200px;" '), Messenger::CurrentRequest);

    Messenger::Instance()->SendToComponent('combobox', 
                                           'Combobox', 'view', 'html', 'ref_jab', 
                                           array('ref_jab', $arrjabstruk, $return['input']['ref_jab'], 
                                            'false', 'id="ref_jab" style="width:500px;" '),
                                            Messenger::CurrentRequest);

      Messenger::Instance()->SendToComponent('combobox', 
                                           'Combobox', 'view', 'html', 'satker_pangkat', 
                                           array('satker_pangkat', $arrPangkat, $return['input']['satker_pangkat'], 
                                            'false', 'style="width:300px;" '),
                                            Messenger::CurrentRequest);

    $list_status=array(array('id'=>'Aktif','name'=>$active),array('id'=>'Tidak Aktif','name'=>$inactive));
	Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status', $list_status, $return['input']['status'], 'false', 'id="status " style="width:200px;"  '), Messenger::CurrentRequest);
	Messenger::Instance()->SendToComponent('profile', 'profilebox', 'view', 'html', 'profile', array($id), Messenger::CurrentRequest);
    $return['dataPegawai'] = $dataPegawai;
    $return['dataSatker'] = $dataSatker;
	   
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
    $dataSatker = $data['dataSatker'];
      
    if ($data['lang']=='eng'){
     	$this->mrTemplate->AddVar('content', 'TITLE', 'WORKING UNIT MUTATION');
     	$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Cancel' : 'Reset');
     	$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Add');
    }else{
     	$this->mrTemplate->AddVar('content', 'TITLE', 'MUTASI UNIT KERJA');
     	$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
     	$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');  
    } 
    $this->mrTemplate->addVar('content','URL_JENIS',GtfwDispt()->GetUrl('mutasi_satuan_kerja', 'ComboJabatanSatuanKerja', 'view', 'html'));
    $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
     
    if ( isset($_GET['dataId'])) {
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_satuan_kerja', 'updateMutasiSatuanKerja', 'do', 'html'));
    }else{
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_satuan_kerja', 'addMutasiSatuanKerja', 'do', 'html'));
    }
      
    $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_satuan_kerja', 'Pegawai', 'view', 'html') );
    $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_satuan_kerja', 'MutasiSatuanKerja', 'view', 'html').'&id='.$dataPegawai[0]['id'] );

    $this->mrTemplate->AddVar('content', 'URL_SPT', Dispatcher::Instance()->GetUrl('cetak_spt', 'InputSpt', 'view', 'html').'&id='.$dataPegawai['0']['id']);                  

    $this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
    // $this->mrTemplate->AddVar('content', 'PANGGOL', $dataPegawai[0]['pgkode'].' '.$dataPegawai[0]['pgnama']);
    $this->mrTemplate->AddVar('content', 'PGTINKAT', $dataPegawai[0]['pgtgkt']);
    $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['kode']);
    $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['name']);
    $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
     
    if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai[0]['foto']) | empty($dataPegawai[0]['foto'])) { 
		  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  }else{
      $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai[0]['foto']);
    }
    
    $id1 = Dispatcher::Instance()->Encrypt("A");
    $id2 = Dispatcher::Instance()->Encrypt("B");
    $this->mrTemplate->AddVar('content', 'URL_POPUP_PEG_1', Dispatcher::Instance()->GetUrl('data_pegawai', 'popupPegawai', 'view', 'html').'&dataPeg='.$id1.'&dataSatker='); 
    $this->mrTemplate->AddVar('content', 'URL_POPUP_PEG_2', Dispatcher::Instance()->GetUrl('data_pegawai', 'popupPegawai', 'view', 'html').'&dataPeg='.$id2.'&dataSatker='); 
     
       
    if(!empty($data['input'])){
      $data['input']['link_download']=$data['link']['link_download'].$data['input']['upload'];
		  $this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');

      $this->mrTemplate->AddVar('content', 'OLD_SATKER_CHECKED', $data['input']['satker'] === '0' ? 'checked="checked"' : '');
	  }
      
    if (empty($dataSatker)) {
      $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  	} else {
  		$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
      


      $label = "Manajemen Data Mutasi Satuan Kerja";
      $urlDelete = Dispatcher::Instance()->GetUrl('mutasi_satuan_kerja', 'deleteMutasiSatuanKerja', 'do', 'html');
      $urlReturn = Dispatcher::Instance()->GetUrl('mutasi_satuan_kerja', 'MutasiSatuanKerja', 'view', 'html');
      Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
      $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

      $total=0;
      $start=1;
      for ($i=0; $i<count($dataSatker); $i++) {
        $no = $i+$start;
        $dataSatker[$i]['number'] = $no;
        if ($no % 2 != 0) {
          $dataSatker[$i]['class_name'] = 'table-common-even';
        }else{
          $dataSatker[$i]['class_name'] = '';
        }     

        if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
        if($i == sizeof($dataIstri)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
      
        $idEnc = Dispatcher::Instance()->Encrypt($dataSatker[$i]['id']);
        $urlAccept = 'mutasi_satuan_kerja|deleteMutasiSatuanKerja|do|html-id-'.$dataPegawai[0]['id'];
        $urlKembali = 'mutasi_satuan_kerja|MutasiSatuanKerja|view|html-id-'.$dataPegawai[0]['id'];
        $label = 'Data Mutasi Satuan Kerja';
        $dataName = $dataSatker[$i]['name'];
        $dataSatker[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
        $dataSatker[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_satuan_kerja','MutasiSatuanKerja', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
        $dataSatker[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_satuan_kerja','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
        $dataSatker[$i]['tmt'] = $this->date2string($dataSatker[$i]['tmt']);
        $dataSatker[$i]['tgl_sk'] = $this->date2string($dataSatker[$i]['tgl_sk']);
        if (!empty($dataSatker[$i]['upload'])){
          $dataSatker[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataSatker[$i]['upload'];
        }else{
          $dataSatker[$i]['LINK_DOWNLOAD_SK'] = '';
		  $dataSatker[$i]['VIEW_DOWNLOAD'] = 'none';
        }
         
      	if(($dataSatker[$i]['status']=='Aktif')&&($data['lang']=='eng')) {
      		$dataSatker[$i]['status']="Active";
      	} elseif(($dataSatker[$i]['status']=='Tidak Aktif')&&($data['lang']=='eng')) {
		      $dataSatker[$i]['status']="Inactive";
	      } else {
      		$dataSatker[$i]['status']=$dataSatker[$i]['status'];
      	}

        $this->mrTemplate->AddVars('data_item', $dataSatker[$i], 'SATKER_');
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
