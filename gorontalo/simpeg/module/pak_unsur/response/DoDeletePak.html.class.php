<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/pak_unsur/response/ProcessPak.proc.class.php';

class DoDeletePak extends HtmlResponse {
   function TemplateModule() {
   
   }
   
   function ProcessRequest() {
      $obj = new ProcessPak();
      $urlRedirect = $obj->Delete();
      $this->RedirectTo($urlRedirect);
      return NULL;
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
