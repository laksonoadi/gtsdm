<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/jabatan_struktural/response/ProcessJabatanStruktural.proc.class.php';

class DoAddJabatanStruktural extends HtmlResponse {
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
