<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/response/ImportGajiPegawai.proc.class.php';

class DoImportGajiPegawai extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new ProcessImportGajiPegawai();
      $urlRedirect = $obj->Import();
      $this->RedirectTo($urlRedirect);
      return NULL;
    }
   function ParseTemplate($data = NULL) { 
   }
}
?>
