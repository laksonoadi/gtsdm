<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/spt_kategori/response/ProcessSptKategori.proc.class.php';
   
class DoDeleteSptKategori extends JsonResponse{
	public function ProcessRequest(){
		$Obj = new Process;
		$urlRedirect = $Obj->delete();
		return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","' . Dispatcher::Instance()->GetUrl($urlRedirect[0], $urlRedirect[1], $urlRedirect[2], $urlRedirect[3]) . '&ascomponent=1")');
	}
}
?>