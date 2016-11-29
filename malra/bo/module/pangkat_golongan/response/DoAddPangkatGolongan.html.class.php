<?php
require_once GTFWConfiguration::GetValue('applicaton','docroot').'module/pangkat_golongan/response/ProcessPangkatGolongan.proc.class.php';

class DoAddPangkatGolongan extends HtmlResponse {
   function TemplateModule() {
   
   }
   
   function ProcessRequest() {
      $obj = new ProcessPangkatGolongan();
      $urlRedirect = $obj->Add();
      $this->RedirectTo($urlRedirect);
      return NULL;
      
   }
   function ParseTemplate($dat=NULL){
   
   }
}

?>
