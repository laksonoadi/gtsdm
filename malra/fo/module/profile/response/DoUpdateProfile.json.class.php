<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/profile/response/ProcessProfile.proc.class.php';

class DoUpdateProfile extends JsonResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$Obj = new ProcessProfile();
		$urlRedirect = $Obj->UpdateProfile();
		return  array('exec'=>'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
	}

	function ParseTemplate($data = NULL) {
	}
}
?>