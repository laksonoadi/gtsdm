<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/business/AppGajiPegawai.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_gaji/business/AppDetilGaji.class.php';

class ViewInputGajiPegawai extends HtmlResponse {
  var $Data;
  var $Pesan;
  
  function TemplateModule() {
    $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') . 'module/gaji_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
    $this->SetTemplateFile('input_gaji_pegawai.html');
  }
  
  function ProcessRequest() {
    $idDec = Dispatcher::Instance()->Decrypt($_REQUEST['dataId']);
	
	//Menginputkan komponen otomatis
	$ObjKomponen = new AppDetilGaji();
	$ObjKomponen->GetKomponenGajiOtomatis($idDec);
	
    $Obj = new AppGajiPegawai();
    $msg = Messenger::Instance()->Receive(__FILE__);
    $this->Pesan = $msg[0][1];
    $this->Data = $msg[0][0];
    
    $dataGajiPegawai = $Obj->GetDataById($idDec);
    $komponenGaji = $Obj->GetKomponenById($idDec);
    
    for ($i=1; $i<=28; $i++){
      $arrTgl[$i-1]['id']=$i;
      $arrTgl[$i-1]['name']=$i;
    }
    
    if (!empty($dataGajiPegawai['tgl_gajian'])){
      $tgl_gajian=$dataGajiPegawai['tgl_gajian'];
    }
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tgl_gajian', array('tgl_gajian', $arrTgl, $tgl_gajian, "false", ' id="tgl_gajian"'), Messenger::CurrentRequest);
    
    $return['decDataId'] = $idDec;
    $return['dataGajiPegawai'] = $dataGajiPegawai;
    $return['dataKomponenGaji'] = $komponenGaji;
    return $return;
  }
  
  function ParseTemplate($data = NULL) {
    if ($this->Pesan) {
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
    }
    $dataGajiPegawai = $data['dataGajiPegawai'];
    //print_r($dataGajiPegawai);
    $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    $x = $_REQUEST['dataId'];
    //print_r($x);
    if ($_REQUEST['dataId']=='') {

      $url="addGajiPegawai";
      if ($lang=='eng'){
         $tambah="Add";
      }else{
         $tambah="Tambah";  
      }
    } else {

      $url="updateGajiPegawai";
      if ($lang=='eng'){
         $tambah="Update";
      }else{
         $tambah="Ubah";  
      }
    }
    
    $this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
    $this->mrTemplate->AddVar('content', 'NIP', $dataGajiPegawai['nip']);
    $this->mrTemplate->AddVar('content', 'SATKER_UNIT', $dataGajiPegawai['satker_unit']);
    $this->mrTemplate->AddVar('content', 'NAMA', $dataGajiPegawai['nama']);
    $this->mrTemplate->AddVar('content', 'ALAMAT', $dataGajiPegawai['alamat']);
    $this->mrTemplate->AddVar('content', 'HP', $dataGajiPegawai['hp']);
    $this->mrTemplate->AddVar('content', 'TELP', $dataGajiPegawai['telp']);
    $this->mrTemplate->AddVar('content', 'REKENING', $dataGajiPegawai['rekening']);
    $this->mrTemplate->AddVar('content', 'RESIPIEN', $dataGajiPegawai['resipien']);
    $this->mrTemplate->AddVar('content', 'BANK', $dataGajiPegawai['bank']);
    $this->mrTemplate->AddVar('content', 'BANK_LABEL', $dataGajiPegawai['bank_label']);
    if($dataGajiPegawai['cash'] == 'Ya')
      $this->mrTemplate->AddVar('content', 'CASH_CHECKED', 'checked');
    if($dataGajiPegawai['aktif'] == 'Ya')
      $this->mrTemplate->AddVar('content', 'AKTIF_CHECKED', 'checked'); 
    //$this->mrTemplate->AddVar('content', 'GAJIPEGAWAI_NAMA', empty($dataGajiPegawai[0]['gaji_pegawai_nama'])?$this->Data['gaji_pegawai_nama']:$dataGajiPegawai[0]['gaji_pegawai_nama']);
    
    
    $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('gaji_pegawai', $url, 'do', 'html') . "&dataId=" . Dispatcher::Instance()->Encrypt($data['decDataId']));
    $this->mrTemplate->AddVar('content', 'URL_POPUP_BANK', Dispatcher::Instance()->GetUrl('gaji_pegawai', 'popupBank', 'view', 'html'));
    $this->mrTemplate->AddVar('content', 'URL_POPUP_KOMPONENGAJI', Dispatcher::Instance()->GetUrl('gaji_pegawai', 'popupKomponenGaji', 'view', 'html'));
    
    $this->mrTemplate->AddVar('content', 'DATAID', Dispatcher::Instance()->Decrypt($_GET['dataId']));
    $this->mrTemplate->AddVar('content', 'PAGE', Dispatcher::Instance()->Decrypt($_GET['page']));
    
    if(empty($data['dataKomponenGaji'])) {
      $this->mrTemplate->AddVar('detil_komponen_gaji', 'DATA_EMPTY', "YES");
    } else {
      $this->mrTemplate->AddVar('detil_komponen_gaji', 'DATA_EMPTY', "NO");
      $dataKomponen = $data['dataKomponenGaji'];
      for ($i=0; $i<sizeof($dataKomponen); $i++) {
        $this->mrTemplate->AddVars('data_komponen_item', $dataKomponen[$i], 'DATA_');
        $this->mrTemplate->parseTemplate('data_komponen_item', 'a');	 
      }
    }
  }
}
?>
