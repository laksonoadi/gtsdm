<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/template_cetak/response/ProcessTemplateCetak.proc.class.php';

class DoDeleteTemplateCetak extends HtmlResponse {

	function TemplateModule() {}
	
	function ProcessRequest() {
		$objTemplate = new ProcessTemplateCetak();
		$urlRedirect = $objTemplate->Delete();
		$this->RedirectTo($urlRedirect);
		return NULL;
	}

	function ParseTemplate($data = NULL) {}
}
?>
