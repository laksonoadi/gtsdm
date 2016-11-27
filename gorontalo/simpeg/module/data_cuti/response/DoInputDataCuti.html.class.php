<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_cuti/response/ProcessDataCuti.proc.class.php';

class DoInputDataCuti extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDataCuti();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
