<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/sertifikasi_verifikasi/response/ProcessDataSertifikasi.proc.class.php';

class DoInputDataSertifikasi extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDataSertifikasi();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>