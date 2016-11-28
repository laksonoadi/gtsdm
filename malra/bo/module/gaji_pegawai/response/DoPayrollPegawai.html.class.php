<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/response/ProcessPayrollPegawai.proc.class.php';

class DoPayrollPegawai extends HtmlResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$obj = new ProcessPayrollPegawai();
		$urlRedirect = $obj->Add();
		$this->RedirectTo($urlRedirect);
		return NULL;
	 }
	function ParseTemplate($data = NULL) {	
	}
}
?>
