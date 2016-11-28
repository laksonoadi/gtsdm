<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/import/response/ImportData.proc.class.php';

class DoImportData extends JsonResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$obj = new ProcessImportData(); 
		$urlRedirect = $obj->Import();
		return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
	 }

	function ParseTemplate($data = NULL) {
	}
}
?>
