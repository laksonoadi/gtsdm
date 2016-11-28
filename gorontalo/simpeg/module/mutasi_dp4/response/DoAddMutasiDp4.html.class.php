<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_dp4/response/ProcessMutasiDp4.proc.class.php';

class DoAddMutasiDp4 extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      //echo "<pre>";print_r($_GET);echo "</pre>";exit();
      
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      $urlRedirect = $obj->InputData();          
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
