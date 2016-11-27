<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/import/response/ImportData.proc.class.php';

class DoImportData extends HtmlResponse {

	function TemplateModule() {
	}
   
	function ProcessRequest() {
		$obj = new ProcessImportData();
		$urlRedirect = $obj->Import();
		$this->RedirectTo($urlRedirect);
		return NULL;
    }
	function ParseTemplate($data = NULL) { 
	}
}
?>
