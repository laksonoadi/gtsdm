<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/ref_komp_pph/response/ProcessKompPph.proc.class.php';

class DoUpdateKompPph extends JsonResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$pphObj = new ProcessPph();
		$urlRedirect = $pphObj->Update();
		return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
	}

	function ParseTemplate($data = NULL) {
	}
}
?>
