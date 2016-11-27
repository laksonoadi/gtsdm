<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_absensi/response/ProcessImport.proc.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_absensi/response/ImportAbsensiHarian.proc.class.php';

class DoImportAbsensiHarian extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new ProcessImportAbsensiHarian();
      $urlRedirect = $obj->ImportFromExcel();
      $this->RedirectTo($urlRedirect);
      return NULL;
    }
   function ParseTemplate($data = NULL) { 
   }
}
?>
