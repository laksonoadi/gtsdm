<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_gaji_karyawan/response/ProcessKomponen.proc.class.php';

class DoUpdateKomponen extends JsonResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$obj = new ProcessKomponen();
		$urlRedirect = $obj->Update();
		return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
	}

	function ParseTemplate($data = NULL) {
	}
}
?>
