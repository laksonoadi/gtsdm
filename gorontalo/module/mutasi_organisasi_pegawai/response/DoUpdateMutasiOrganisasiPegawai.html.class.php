<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_organisasi_pegawai/response/ProcessMutasiOrganisasiPegawai.proc.class.php';

class DoUpdateMutasiOrganisasiPegawai extends HtmlResponse {

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
