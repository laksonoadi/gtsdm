<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_dp3/response/ProcessMutasiDp3.proc.class.php';

class DoUpdateMutasiDp3 extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      //echo "<pre>";print_r($_GET);echo "</pre>";exit();
      //$ret = "html";
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
