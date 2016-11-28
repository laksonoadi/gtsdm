<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_pph_21/response/ProcessKomponen.proc.class.php';

class DoAddKomponen extends HtmlResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$Obj = new ProcessKomponen();
		$urlRedirect = $Obj->Add();
		$this->RedirectTo($urlRedirect);
		return NULL;
	 }
	function ParseTemplate($data = NULL) {	
	}
}
?>
