<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/satuan_wilayah/response/ProcessSatuanWilayah.proc.class.php';

class DoInputSatuanWilayah extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputSatwil();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
