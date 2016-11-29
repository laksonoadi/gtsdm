<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user/response/ProcessUser.proc.class.php';

class DoDeleteUser extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {

      $userObj = new ProcessUser();
      
      $urlRedirect = $userObj->Delete();
      
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
