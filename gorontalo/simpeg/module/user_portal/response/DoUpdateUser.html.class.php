<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user_portal/response/ProcessUser.proc.class.php';

class DoUpdateUser extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {

      $userObj = new ProcessUser();
      
      $urlRedirect = $userObj->Update();
            
      $this->RedirectTo($urlRedirect) ;
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
