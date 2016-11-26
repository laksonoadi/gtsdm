<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/approval_lembur/response/ProcessDataLembur.proc.class.php';

class DoInputDataLembur extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDataLembur();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
