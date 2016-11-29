<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user/response/ProcessUser.proc.class.php';

class DoDeleteUser extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {

      $userObj = new ProcessUser();
      
      $urlRedirect = $userObj->Delete();
            
      $this->RedirectTo($urlRedirect) ;
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
