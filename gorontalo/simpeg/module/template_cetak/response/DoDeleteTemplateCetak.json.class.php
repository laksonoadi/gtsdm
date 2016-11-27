<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/template_cetak/response/ProcessTemplateCetak.proc.class.php';

class DoDeleteTemplateCetak extends JsonResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$objTemplate = new ProcessTemplateCetak();
		$urlRedirect = $objTemplate->Delete();
		
		return array('exec'=>'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
	 }
	function ParseTemplate($data = NULL) {	
	}
}
?>
