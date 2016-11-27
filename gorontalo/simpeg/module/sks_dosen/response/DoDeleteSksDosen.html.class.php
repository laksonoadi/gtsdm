<?php
require_once GTFWConfiguration::GetValue('applicaton','docroot').'module/sks_dosen/response/ProcessSksDosen.proc.class.php';

class DoDeleteSksDosen extends HtmlResponse {
   function TemplateModule() {
   
   }
   
   function ProcessRequest() {
      $obj = new ProcessSksDosen();
      $urlRedirect = $obj->Delete();
      $this->RedirectTo($urlRedirect);
      return NULL;
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
