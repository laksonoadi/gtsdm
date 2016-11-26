<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_saudara_kandung/response/ProcessDataSaudaraKandung.proc.class.php';

class DoInputDataSaudaraKandung extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDatsdr();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
