<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_gaji/response/ProcessGaji.proc.class.php';

class DoUpdateGaji extends JsonResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$Obj = new ProcessGaji();
		$urlRedirect = $Obj->Update();
		return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
	}

	function ParseTemplate($data = NULL) {
	}
}
?>