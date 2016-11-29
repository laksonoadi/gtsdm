<?php
require_once GTFWConfiguration::GetValue('applicaton','docroot').'module/pangkat_golongan/response/ProcessPangkatGolongan.proc.class.php';

class DoDeletePangkatGolongan extends HtmlResponse {
   function TemplateModule() {
   
   }
   
   function ProcessRequest() {
      $obj = new ProcessPangkatGolongan();
      $urlRedirect = $obj->Delete();
      $this->RedirectTo($urlRedirect);
      return NULL;
      
   }
   function ParseTemplate($dat=NULL){
   
   }
}

?>
