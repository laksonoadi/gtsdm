<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_kunjungan/response/ProcessMutasiKunjungan.proc.class.php';

class DoUpdateMutasiKunjungan extends HtmlResponse {

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
