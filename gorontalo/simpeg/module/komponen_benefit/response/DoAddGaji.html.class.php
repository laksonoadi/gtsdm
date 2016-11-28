<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_gaji/response/ProcessGaji.proc.class.php';

class DoAddGaji extends HtmlResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$Obj = new ProcessGaji();
		$urlRedirect = $Obj->Add();
		$this->RedirectTo($urlRedirect);
		return NULL;
	 }
	function ParseTemplate($data = NULL) {	
	}
}
?>
