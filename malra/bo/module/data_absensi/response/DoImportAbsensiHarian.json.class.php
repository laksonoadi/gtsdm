<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_absensi/response/ProcessImport.proc.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_absensi/response/ImportAbsensiHarian.proc.class.php';

class DoImportAbsensiHarian extends JsonResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$obj = new ProcessImportAbsensiHarian();
        $urlRedirect = $obj->ImportFromExcel();
		return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
	 }

	function ParseTemplate($data = NULL) {
	}
}
?>
