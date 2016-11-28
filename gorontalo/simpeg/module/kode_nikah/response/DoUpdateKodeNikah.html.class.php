<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/kode_nikah/response/ProcessKodeNikah.proc.class.php';

class DoUpdateKodeNikah extends HtmlResponse {
      
   function ProcessRequest() {
      $obj = new ProcessKodeNikah();
      $urlRedirect = $obj->Update();
      $this->RedirectTo($urlRedirect);
      return NULL;
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
