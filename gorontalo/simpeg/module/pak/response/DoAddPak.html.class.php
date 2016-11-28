<?php
require_once GTFWConfiguration::GetValue('applicaton','docroot').'module/pak/response/ProcessPak.proc.class.php';

class DoAddPak extends HtmlResponse {
   function TemplateModule() {
   
   }
   
   function ProcessRequest() {
      $obj = new ProcessPak();
      $urlRedirect = $obj->Add();
      $this->RedirectTo($urlRedirect);
      return NULL;
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
