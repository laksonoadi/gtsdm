<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/ref_tanda_jasa/response/ProcessRefTandaJasa.proc.class.php';
   
class DoInputRefTandaJasa extends HtmlResponse{
	public function ProcessRequest(){
		$proses = new Process;
		
		$post = $_POST->AsArray();
		if(isset($post['dataId']) and !empty($post['dataId'])){
			$urlRedirect = $proses->update();
		}else{ 
			$urlRedirect = $proses->add();
		}

		$this->RedirectTo(Dispatcher::Instance()->GetUrl($urlRedirect[0], $urlRedirect[1], $urlRedirect[2], $urlRedirect[3])) ;
		return NULL;  
	}
}
?>