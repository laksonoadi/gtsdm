<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/kode_nikah/response/ProcessKodeNikah.proc.class.php';

class DoAddKodeNikah extends HtmlResponse {
   function TemplateModule() {
   
   }
   
   function ProcessRequest() {
      $obj = new ProcessJabatanStruktural();
      $urlRedirect = $obj->Add();
      $this->RedirectTo($urlRedirect);
      return NULL;
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
