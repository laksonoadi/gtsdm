<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/ref_potongan_pph/business/PotonganPph.class.php';

class ViewInputPotonganPph extends HtmlResponse {
	var $Data;
	var $Pesan;
	
	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
		'module/ref_potongan_pph/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('input_potonganpph.html');
	}
	
	function ProcessRequest() {
		$idDec = Dispatcher::Instance()->Decrypt($_REQUEST['dataId']);
		$pphObj = new PotonganPph();
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
			$url="addPotonganPph";
			$param=$data['tambah'];
		} else {
			$url="updatePotonganPph";
			$param=$data['ubah'];
		}
		$this->mrTemplate->AddVar('content', 'JUDUL', $param);
		$this->mrTemplate->AddVar('content', 'PPHRP_NAMA', empty($dataPph[0]['pphrp_nama'])?$this->Data['pphrp_nama']:$dataPph[0]['pphrp_nama']);
		$this->mrTemplate->AddVar('content', 'PPHRP_NOMINAL', empty($dataPph[0]['pphrp_nominal'])?$this->Data['pphrp_nominal']:$dataPph[0]['pphrp_nominal']);
		$this->mrTemplate->AddVar('content', 'PPHRP_ORDER', empty($dataPph[0]['pphrp_order'])?$this->Data['pphrp_order']:$dataPph[0]['pphrp_order']);

		$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('ref_potongan_pph', $url, 'do', 'html') . "&dataId=" . Dispatcher::Instance()->Encrypt($data['decDataId']));

		$this->mrTemplate->AddVar('content', 'DATAID', Dispatcher::Instance()->Decrypt($_GET['dataId']));
		$this->mrTemplate->AddVar('content', 'PAGE', Dispatcher::Instance()->Decrypt($_GET['page']));
	}
}
?>
