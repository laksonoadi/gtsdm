<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/template_cetak/response/ProcessTemplateCetak.proc.class.php';

class DoUpdateStatusTemplateCetak extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $prdObj = new ProcessTemplateCetak();
      $urlRedirect = $prdObj->UpdateStatus();

      $this->RedirectTo($urlRedirect);
      return NULL;
   }

   function ParseTemplate($data = NULL) { 
   }
}
?>