<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/pak_unsur/response/ProcessPak.proc.class.php';

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
