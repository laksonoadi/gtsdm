<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/ref_bahasa/response/ProcessRefBahasa.proc.class.php';
   
class DoDeleteRefBahasa extends JsonResponse{
	public function ProcessRequest(){
		$Obj = new Process;
		$urlRedirect = $Obj->delete();
		return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","' . Dispatcher::Instance()->GetUrl($urlRedirect[0], $urlRedirect[1], $urlRedirect[2], $urlRedirect[3]) . '&ascomponent=1")');
	}
}
?>