<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/template_cetak/response/ProcessTemplateCetak.proc.class.php';

class DoAddTemplateCetak extends HtmlResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$templateObj = new ProcessTemplateCetak();
		$urlRedirect = $templateObj->Add();
		$this->RedirectTo($urlRedirect);
		return NULL;
	 }
	function ParseTemplate($data = NULL) {	
	}
}
?>