<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_satuan_kerja/business/mutasi_satuan_kerja.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_jabatan_struktural/business/mutasi_jabatan_struktural.class.php';

class ViewInputWizardSatuanKerja extends HtmlResponse
{
  function TemplateModule()
  {
    $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
    'module/data_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
    $this->SetTemplateFile('view_input_wizard_satuan_kerja.html');
  }
      
  function ProcessRequest() 
  {
   	$pg = new MutasiSatuanKerja();
   	$objSatker = new SatuanKerja();
      
   	$msg = Messenger::Instance()->Receive(__FILE__);
   	$this->Data = isset($msg[0][0]) ? $msg[0][0] : NULL;
    $this->Pesan = isset($msg[0][1]) ? $msg[0][1] : NULL;
    $this->css = isset($msg[0][2]) ? $msg[0][2] : NULL;
    $post = $this->Data;
      
    $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
    $dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
    $this->profilId=$id;
      
    $arrsatker = $objSatker->GetComboUnitSatker();
    $arrjabstruk = $pg->GetComboJabatanStruktural();
    $arrjenpeg =  $objSatker->GetListJenisKepegawaian();
    
    $tahun=array();

    $dataPegawai = $pg->GetDataDetail($id);
    if($post) {
        $return['input']['id'] = '';
        $return['input']['nip'] = '';
        $return['input']['satker'] = $post['satker'];
        $return['input']['ref_jab'] = $post['satkerjab'];
        $return['input']['jenpeg'] = $post['jenpeg'];
        $return['input']['tmt'] = $post['tmt_day'].'-'.$post['tmt_mon'].'-'.$post['tmt_year'];
        $return['input']['pejabat'] = $post['pejabat'];
        $return['input']['nosk'] = $post['sk_no'];
        $return['input']['tgl_sk'] = $post['tgl_sk_day'].'-'.$post['tgl_sk_mon'].'-'.$post['tgl_sk_year'];
        $return['input']['status'] = $post['status'];
        $return['input']['upload'] = '';
        $return['input']['pktgol'] = '';
    } else {
        $return['input']['id'] = '';
        $return['input']['nip'] = '';
        $return['input']['satker'] = '';
        $return['input']['jenpeg'] = '';
        $return['input']['ref_jab'] = '';
        $return['input']['tmt'] = date("Y-m-d");
        $return['input']['pejabat'] = '';
        $return['input']['nosk'] = '';
        $return['input']['tgl_sk'] = date("Y-m-d");
        $return['input']['status'] = '';
        $return['input']['upload'] = '';
        $return['input']['pktgol'] = '';
    }

    if(empty($tahun['start'])){
	    $tahun['start']=date("Y")-50;
	  }        	

	  $tahun['end'] = date("Y")+5;

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
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'satker', array('satker', $arrsatker, $return['input']['satker'], 'false', ' style="width:200px;"  onchange="setDS()" '), Messenger::CurrentRequest);
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenpeg', array('jenpeg', $arrjenpeg, $return['input']['jenpeg'], 'false', ' style="width:200px;" '), Messenger::CurrentRequest);

    Messenger::Instance()->SendToComponent('combobox', 
                                           'Combobox', 'view', 'html', 'satkerjab', 
                                           array('satkerjab', $arrjabstruk, $return['input']['ref_jab'], 
                                            'false', 'id="ref_jab" style="width:200px;" '),
                                            Messenger::CurrentRequest);

    $list_status=array(array('id'=>'Aktif','name'=>$active),array('id'=>'Aktif','name'=>$inactive));
	Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status', $list_status, $return['input']['status'], 'false', 'id="status " style="width:200px;"  '), Messenger::CurrentRequest);
	Messenger::Instance()->SendToComponent('profile', 'profilebox', 'view', 'html', 'profile', array($id), Messenger::CurrentRequest);
    $return['dataPegawai'] = $dataPegawai;
	   
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
      
    $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('data_pegawai', 'addWizardSatuanKerja', 'do', 'html'));
  
    $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_pegawai', 'DataPegawai', 'view', 'html'));
    $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_satuan_kerja', 'MutasiSatuanKerja', 'view', 'html').'&id='.$dataPegawai[0]['id'] );

    $this->mrTemplate->AddVar('content', 'URL_SPT', Dispatcher::Instance()->GetUrl('cetak_spt', 'InputSpt', 'view', 'html').'&id='.$dataPegawai['0']['id']);

    $this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
    $this->mrTemplate->AddVar('content', 'PANGGOL', $dataPegawai[0]['pgkode'].' '.$dataPegawai[0]['pgnama']);
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
