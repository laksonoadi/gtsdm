<?php
require_once GTFWConfiguration::GetValue('applicaton','docroot').'module/sks_dosen/response/ProcessSksDosen.proc.class.php';

class DoUpdateSksDosen extends JsonResponse {
      
   function ProcessRequest() {
      $obj = new ProcessSksDosen();
      $urlRedirect = $obj->Update();
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
