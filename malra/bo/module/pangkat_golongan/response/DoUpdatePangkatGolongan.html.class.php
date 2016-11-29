<?php
require_once GTFWConfiguration::GetValue('applicaton','docroot').'module/pangkat_golongan/response/ProcessPangkatGolongan.proc.class.php';

class DoUpdatePangkatGolongan extends HtmlResponse {
      
   function ProcessRequest() {
      $obj = new ProcessPangkatGolongan();
      $urlRedirect = $obj->Update();
      $this->RedirectTo($urlRedirect);
      return NULL;
      
   }
   function ParseTemplate($dat=NULL){
   
   }
}

?>
