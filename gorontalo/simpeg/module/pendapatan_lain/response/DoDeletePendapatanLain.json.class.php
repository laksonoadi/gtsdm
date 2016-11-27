<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/pendapatan_lain/response/ProcessPendapatanLain.proc.class.php';

class DoDeletePendapatanLain extends JsonResponse {

	function TemplateModule() {}

	function ProcessRequest() {
		$Obj = new ProcessPendapatanLain();
		$urlRedirect = $Obj->Delete();
		return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
	}

	function ParseTemplate($data = NULL) {}
}
?>
