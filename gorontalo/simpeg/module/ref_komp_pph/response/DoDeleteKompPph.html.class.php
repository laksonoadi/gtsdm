<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/ref_komp_pph/response/ProcessKompPph.proc.class.php';

class DoDeleteKompPph extends HtmlResponse {

	function TemplateModule() {}
	
	function ProcessRequest() {
		$pphObj = new ProcessPph();
		$urlRedirect = $pphObj->Delete();
		$this->RedirectTo($urlRedirect);
		return NULL;
	}

	function ParseTemplate($data = NULL) {}
}
?>
