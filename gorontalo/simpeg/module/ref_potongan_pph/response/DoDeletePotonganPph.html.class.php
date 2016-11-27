<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/ref_potongan_pph/response/ProcessPotonganPph.proc.class.php';

class DoDeletePotonganPph extends HtmlResponse {

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
