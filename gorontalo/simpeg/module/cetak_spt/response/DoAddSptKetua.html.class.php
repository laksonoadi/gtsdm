<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/cetak_spt/response/ProcessMutasiSatuanKerja.proc.class.php';

class DoAddSptKetua extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      // echo "<pre>";print_r($_GET);echo "</pre>";exit();
      $ret = "html";
      $obj = new Process($ret);
      //set post
      
      $obj->SetPost($_POST);
      $urlRedirect = $obj->InputDataKetua();          
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
