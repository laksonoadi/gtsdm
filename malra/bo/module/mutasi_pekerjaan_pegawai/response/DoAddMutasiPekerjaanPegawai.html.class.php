<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_pekerjaan_pegawai/response/ProcessMutasiPekerjaanPegawai.proc.class.php';

class DoAddMutasiPekerjaanPegawai extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      //echo "<pre>";print_r($_GET);echo "</pre>";exit();
      $ret = "html";
      $obj = new Process($ret);
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
