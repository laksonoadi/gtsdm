<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/persetujuan_lembur/response/ProcessAppDataLembur.proc.class.php';

class DoInputAppLembur extends HtmlResponse {

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
