<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_pph_21/response/ProcessKomponen.proc.class.php';

class DoUpdateKomponen extends HtmlResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$obj = new ProcessKomponen();
		$urlRedirect = $obj->Update();
		$this->RedirectTo($urlRedirect);
		return NULL;
	}

	function ParseTemplate($data = NULL) {
	}
}
?>
