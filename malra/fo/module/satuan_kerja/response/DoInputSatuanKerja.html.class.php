<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/satuan_kerja/response/ProcessSatuanKerja.proc.class.php';

class DoInputSatuanKerja extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputSatker();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
