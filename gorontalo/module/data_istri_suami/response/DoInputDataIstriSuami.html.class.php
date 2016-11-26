<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_istri_suami/response/ProcessDataIstriSuami.proc.class.php';

class DoInputDataIstriSuami extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDatistri();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
