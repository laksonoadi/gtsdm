<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/spt_format/response/ProcessSptFormat.proc.class.php';
   
class DoDeleteSptFormat extends HtmlResponse{
	public function ProcessRequest(){
		$Obj = new Process;
		$urlRedirect = $Obj->delete();
		$this->RedirectTo(Dispatcher::Instance()->GetUrl($urlRedirect[0], $urlRedirect[1], $urlRedirect[2], $urlRedirect[3])) ;
		return NULL;
	}
}
?>