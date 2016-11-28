<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_orang_tua/response/ProcessDataOrangTua.proc.class.php';

class DoInputDataOrangTua extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDatortu();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
