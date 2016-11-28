<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_absensi/response/ProcessImport.proc.class.php';

class DoProsesAbsensiHarian extends HtmlResponse
{
   function TemplateModule ()
   {
   }
   
   function ProcessRequest() {
      $obj = new ProcessImport();
       
      $urlRedirect = $obj->ProsesAbsensiHarian();
      $this->RedirectTo($urlRedirect);
      return NULL;
    }
   function ParseTemplate($data = NULL) { 
   }
}
?>
