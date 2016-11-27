<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_cuti_massal/response/ProcessDataCutiMassal.proc.class.php';

class DoInputDataCutiMassal extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDataCutiMassal();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
