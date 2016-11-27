<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pokok/response/ProcessGajiPokok.proc.class.php';

class DoInputGajiPokok extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDatgapok();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
