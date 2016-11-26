<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_taf/response/ProcessDataTaf.proc.class.php';

class DoInputDataTaf extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDataTaf();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
