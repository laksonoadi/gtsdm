<?php
require_once GTFWConfiguration::GetValue('applicaton','docroot').'module/sks_dosen/response/ProcessSksDosen.proc.class.php';

class DoAddSksDosen extends HtmlResponse {
   function TemplateModule() {
   
   }
   
   function ProcessRequest() {
      $obj = new ProcessSksDosen();
      $urlRedirect = $obj->Add();
      $this->RedirectTo($urlRedirect);
      return NULL;
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
