<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/profile/response/ProcessProfile.proc.class.php';

class DoUpdateProfile extends HtmlResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$Obj = new ProcessProfile();
		$urlRedirect = $Obj->UpdateProfile();
		$this->RedirectTo($urlRedirect);
		return NULL;
	}

	function ParseTemplate($data = NULL) {
	}
}
?>
