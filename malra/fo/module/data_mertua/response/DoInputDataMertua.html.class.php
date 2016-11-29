<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_mertua/response/ProcessDataMertua.proc.class.php';

class DoInputDataMertua extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDatmertua();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
