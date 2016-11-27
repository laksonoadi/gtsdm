<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user/response/ProcessUser.proc.class.php';

class DoAddUser extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {

      $userObj = new ProcessUser();
      
      $urlRedirect = $userObj->Add();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
