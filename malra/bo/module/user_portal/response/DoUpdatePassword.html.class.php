<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user_portal/response/ProcessUser.proc.class.php';

class DoUpdatePassword extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {      
      $userObj = new ProcessUser();
      
      $urlRedirect = $userObj->UpdatePassword();
            
      $this->RedirectTo($urlRedirect);
      
      return NULL;
   }

   function ParseTemplate($data = NULL) {
   }
}
?>
