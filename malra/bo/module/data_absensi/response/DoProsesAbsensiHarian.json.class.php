<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_absensi/response/ProcessProses.proc.class.php';

class DoProsesAbsensiHarian extends JsonResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$obj = new ProcessImport(); 
		
    $urlRedirect = $obj->ProsesAbsensiHarian($arr);
		return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
	 }

	function ParseTemplate($data = NULL) {
	}
}
?>
