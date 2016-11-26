<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/profile/response/ProcessProfile.proc.class.php';

class DoUpdatePassword extends JsonResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$Obj = new ProcessProfile();
		$urlRedirect = $Obj->UpdatePassword();
		return  array('exec'=>'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
	}

	function ParseTemplate($data = NULL) {
	}
}
?>