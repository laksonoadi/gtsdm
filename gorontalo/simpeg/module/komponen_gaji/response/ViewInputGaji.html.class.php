<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_gaji/business/AppGaji.class.php';

class ViewInputGaji extends HtmlResponse {
	var $Data;
	var $Pesan;
	
	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') . 'module/komponen_gaji/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('input_gaji.html');
	}
	
	function ProcessRequest() {
		$idDec = Dispatcher::Instance()->Decrypt($_REQUEST['dataId']);
		$Obj = new AppGaji();
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->Data = $msg[0][0];

		$dataGaji = $Obj->GetDataById($idDec);

//startof kombo jenis
    $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    $arrJenis[0]['id'] = "tambah";
    $arrJenis[1]['id'] = "kurang";
     if ($lang=='eng'){
        $arrJenis[0]['name'] = "Salary Increment Factor";
        $arrJenis[1]['name'] = "Salary Decrement Factor";
     }else{
        $arrJenis[0]['name'] = "Faktor Penambah Gaji";  
        $arrJenis[1]['name'] = "Faktor Pengurang Gaji";
     }
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis', array('jenis', $arrJenis, (empty($this->Data['jenis'])?$dataGaji[0]['jenis']:$this->Data['jenis']), '-', ' style="width:170px;" '), Messenger::CurrentRequest);
//endof kombo jenis

		$return['decDataId'] = $idDec;
		$return['dataGaji'] = $dataGaji;
		return $return;
	}

	function ParseTemplate($data = NULL) {
		if ($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
		}
		$dataGaji = $data['dataGaji'];
		
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
		
		if ($_REQUEST['dataId']=='') {
			$url="addGaji";
			 if ($lang=='eng'){
          $tambah="Add";
       }else{
          $tambah="Tambah";  
       }
		} else {
			$url="updateGaji";
			 if ($lang=='eng'){
          $tambah="Update";
       }else{
          $tambah="Ubah";  
       }
		}
		$this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
		$this->mrTemplate->AddVar('content', 'KODE', empty($dataGaji[0]['kode'])?$this->Data['kode']:$dataGaji[0]['kode']);
		$this->mrTemplate->AddVar('content', 'NAMA', empty($dataGaji[0]['nama'])?$this->Data['nama']:$dataGaji[0]['nama']);
		$this->mrTemplate->AddVar('content', 'KETERANGAN', empty($dataGaji[0]['keterangan'])?$this->Data['keterangan']:$dataGaji[0]['keterangan']);

		$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('komponen_gaji', $url, 'do', 'html') . "&dataId=" . Dispatcher::Instance()->Encrypt($data['decDataId']));

		$this->mrTemplate->AddVar('content', 'DATAID', Dispatcher::Instance()->Decrypt($_GET['dataId']));
		$this->mrTemplate->AddVar('content', 'PAGE', Dispatcher::Instance()->Decrypt($_GET['page']));
	}
}
?>
