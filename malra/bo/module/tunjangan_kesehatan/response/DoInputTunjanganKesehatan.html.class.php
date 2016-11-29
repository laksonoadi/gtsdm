<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/tunjangan_kesehatan/response/ProcessTunjanganKesehatan.proc.class.php';

class DoInputTunjanganKesehatan extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDattun();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
