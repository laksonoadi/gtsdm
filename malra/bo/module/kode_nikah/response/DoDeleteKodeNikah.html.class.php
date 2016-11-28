<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/kode_nikah/response/ProcessKodeNikah.proc.class.php';

class DoDeleteKodeNikah extends HtmlResponse {
   function TemplateModule() {
   
   }
   
   function ProcessRequest() {
      $obj = new ProcessKodeNikah();
      $urlRedirect = $obj->Delete();
      $this->RedirectTo($urlRedirect);
      return NULL;
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
