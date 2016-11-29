<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/ref_bahasa/response/ProcessRefBahasa.proc.class.php';
   
class DoDeleteRefBahasa extends HtmlResponse{
	public function ProcessRequest(){
		$Obj = new Process;
		$urlRedirect = $Obj->delete();
		$this->RedirectTo(Dispatcher::Instance()->GetUrl($urlRedirect[0], $urlRedirect[1], $urlRedirect[2], $urlRedirect[3])) ;
		return NULL;
	}
}
?>