<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/profile/response/ProcessProfile.proc.class.php';

class DoUpdatePassword extends HtmlResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$Obj = new ProcessProfile();
		$urlRedirect = $Obj->UpdatePassword();
		$this->RedirectTo($urlRedirect);
		return NULL;
	}

	function ParseTemplate($data = NULL) {
	}
}
?>