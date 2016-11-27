<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user/response/ProcessUser.proc.class.php';

class DoUpdatePassword extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {      
      $userObj = new ProcessUser();
      
      $urlRedirect = $userObj->UpdatePassword();
      
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")') ;
               
      return NULL;
   }

   function ParseTemplate($data = NULL) {
   }
}
?>
