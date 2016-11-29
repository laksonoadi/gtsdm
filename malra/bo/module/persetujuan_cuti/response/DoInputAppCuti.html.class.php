<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/persetujuan_cuti/response/ProcessAppDataCuti.proc.class.php';

class DoInputAppCuti extends HtmlResponse {

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
