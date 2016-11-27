<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/persetujuan_benefit/response/ProcessAppDataBenefit.proc.class.php';

class DoInputAppBenefit extends HtmlResponse {

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
