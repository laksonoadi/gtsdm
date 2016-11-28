<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_gaji/response/ProcessDetilGaji.proc.class.php';

class DoAddDetilGaji extends HtmlResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$Obj = new ProcessDetilGaji();
		$urlRedirect = $Obj->Add();
		$this->RedirectTo($urlRedirect);
		return NULL;
	 }
	function ParseTemplate($data = NULL) {	
	}
}
?>
