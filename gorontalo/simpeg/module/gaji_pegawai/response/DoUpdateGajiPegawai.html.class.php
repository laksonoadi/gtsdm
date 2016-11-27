<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/response/ProcessGajiPegawai.proc.class.php';

class DoUpdateGajiPegawai extends HtmlResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$gaji_pegawaiObj = new ProcessGajiPegawai();
		$urlRedirect = $gaji_pegawaiObj->Update();
		$this->RedirectTo($urlRedirect);
		return NULL;
	}

	function ParseTemplate($data = NULL) {
	}
}
?>
