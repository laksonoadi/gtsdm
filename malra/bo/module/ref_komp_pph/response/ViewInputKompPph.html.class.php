<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/ref_komp_pph/business/KompPph.class.php';

class ViewInputKompPph extends HtmlResponse {
	var $Data;
	var $Pesan;
	
	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
		'module/ref_komp_pph/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('input_komppph.html');
	}
	
	function ProcessRequest() {
		$idDec = Dispatcher::Instance()->Decrypt($_REQUEST['dataId']);
		$pphObj = new KompPph();
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->Data = $msg[0][0];

		$dataPph = $pphObj->GetDataPphById($idDec);

		$return['decDataId'] = $idDec;
		$return['dataPph'] = $dataPph;
		
		$this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
		if ($this->lang=='eng'){
			$return['tambah']="Add";
			$return['ubah']="Edit";		
		}
		else {
			$return['tambah']="Tambah";
			$return['ubah']="Ubah";
		}
		
		return $return;
	}

	function ParseTemplate($data = NULL) {
		if ($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
		}
		$dataPph = $data['dataPph'];
		
		if ($_REQUEST['dataId']=='') {
			$url="addKompPph";
			$param=$data['tambah'];
		} else {
			$url="updateKompPph";
			$param=$data['ubah'];
		}
		$this->mrTemplate->AddVar('content', 'JUDUL', $param);
		$this->mrTemplate->AddVar('content', 'PPH_KODE', empty($dataPph[0]['pph_kode'])?$this->Data['pph_kode']:$dataPph[0]['pph_kode']);
		$this->mrTemplate->AddVar('content', 'PPH_NAMA', empty($dataPph[0]['pph_nama'])?$this->Data['pph_nama']:$dataPph[0]['pph_nama']);
		$this->mrTemplate->AddVar('content', 'PPH_KETERANGAN', empty($dataPph[0]['pph_keterangan'])?$this->Data['pph_keterangan']:$dataPph[0]['pph_keterangan']);

		$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('ref_komp_pph', $url, 'do', 'html') . "&dataId=" . Dispatcher::Instance()->Encrypt($data['decDataId']));

		$this->mrTemplate->AddVar('content', 'DATAID', Dispatcher::Instance()->Decrypt($_GET['dataId']));
		$this->mrTemplate->AddVar('content', 'PAGE', Dispatcher::Instance()->Decrypt($_GET['page']));
	}
}
?>
