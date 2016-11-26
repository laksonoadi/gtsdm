<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/group/response/ProcessGroup.proc.class.php';

class DoDeleteGroup extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {      
      $groupObj = new ProcessGroup();
      $urlRedirect = $groupObj->Delete();
      $this->RedirectTo($urlRedirect);
      return NULL;      
   }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
