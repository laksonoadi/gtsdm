<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_anak/response/ProcessDataAnak.proc.class.php';

class DoInputDataAnak extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDatanak();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
