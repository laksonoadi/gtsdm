<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/approval_benefit/response/ProcessDataBenefit.proc.class.php';

class DoInputDataBenefit extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDataBenefit();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
