<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/pendapatan_lain/response/ProcessPendapatanLain.proc.class.php';

class DoDeletePendapatanLain extends HtmlResponse {

	function TemplateModule() {}
	
	function ProcessRequest() {
		$Obj = new ProcessPendapatanLain();
		$urlRedirect = $Obj->Delete();
		$this->RedirectTo($urlRedirect);
		return NULL;
	}

	function ParseTemplate($data = NULL) {}
}
?>