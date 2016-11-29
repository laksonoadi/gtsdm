<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/ref_daftar_gaji/response/ProcessRefDaftarGaji.proc.class.php';
   
class DoDeleteRefDaftarGaji extends HtmlResponse{
	public function ProcessRequest(){
		$Obj = new Process;
		$urlRedirect = $Obj->delete();
		$this->RedirectTo(Dispatcher::Instance()->GetUrl($urlRedirect[0], $urlRedirect[1], $urlRedirect[2], $urlRedirect[3])) ;
		return NULL;
	}
}
?>