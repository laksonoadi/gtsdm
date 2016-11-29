<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/template_cetak/response/ProcessTemplateCetak.proc.class.php';

class DoUpdateTemplateCetak extends JsonResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$templateObj = new ProcessTemplateCetak();
		$urlRedirect = $templateObj->Update('json');
		return array('exec'=>'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
	 }
    
	function ParseTemplate($data = NULL) {	
	}
}
?>