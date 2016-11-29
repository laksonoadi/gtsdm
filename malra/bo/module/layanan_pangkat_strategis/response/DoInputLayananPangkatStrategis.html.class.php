<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/layanan_pangkat_strategis/response/ProcessLayananPangkatStrategis.proc.class.php';
   
class DoInputLayananPangkatStrategis extends HtmlResponse{
	public function ProcessRequest(){
		$proses = new Process;
		
		$post = $_POST->AsArray();
		if(isset($post['dataId']) and !empty($post['dataId'])){
			$urlRedirect = $proses->update();
		}else{ 
			$urlRedirect = $proses->add();
		}

		$this->RedirectTo(Dispatcher::Instance()->GetUrl($urlRedirect[0], $urlRedirect[1], $urlRedirect[2], $urlRedirect[3]).'&id='.$post['id_pktgol']) ;
		return NULL;  
	}
}
?>