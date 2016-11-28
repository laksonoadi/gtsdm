<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/pak_unsur/response/ProcessPak.proc.class.php';

class DoUpdatePak extends JsonResponse {
      
   function ProcessRequest() {
      $obj = new ProcessPak();
      $urlRedirect = $obj->Update();
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
