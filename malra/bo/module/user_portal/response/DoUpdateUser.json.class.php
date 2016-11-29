<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user_portal/response/ProcessUser.proc.class.php';

class DoUpdateUser extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {

      $userObj = new ProcessUser();
      
      $urlRedirect = $userObj->Update();
      
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
