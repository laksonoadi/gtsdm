<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/layanan_pangkat_fungsional/response/ProcessLayananPangkatFungsional.proc.class.php';
   
class DoInputLayananPangkatFungsional extends JsonResponse{
	public function ProcessRequest(){
		$proses = new Process;

		$post = $_POST->AsArray();
		if(isset($post['dataId']) and !empty($post['dataId'])){
			$urlRedirect = $proses->update();
		}else{ 
			$urlRedirect = $proses->add();
		}

		return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","' . Dispatcher::Instance()->GetUrl($urlRedirect[0], $urlRedirect[1], $urlRedirect[2], $urlRedirect[3]) . '&id='.$post['id_pktgol'].'&ascomponent=1")');  
	}
}
?>