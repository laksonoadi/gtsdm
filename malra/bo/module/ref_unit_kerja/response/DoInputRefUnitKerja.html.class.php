<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/ref_unit_kerja/response/ProcessRefUnitKerja.proc.class.php';
   
class DoInputRefUnitKerja extends HtmlResponse{
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