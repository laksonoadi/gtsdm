<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/ref_unit_kerja/response/ProcessRefUnitKerja.proc.class.php';
   
class DoInputRefUnitKerja extends JsonResponse{
	public function ProcessRequest(){
		$proses = new Process;

		$post = $_POST->AsArray();
		if(isset($post['dataId']) and !empty($post['dataId'])){
			$urlRedirect = $proses->update();
		}else{ 
			$urlRedirect = $proses->add();
		}

		return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","' . Dispatcher::Instance()->GetUrl($urlRedirect[0], $urlRedirect[1], $urlRedirect[2], $urlRedirect[3]) . '&ascomponent=1")');  
	}
}
?>