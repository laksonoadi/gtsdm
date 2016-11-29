<?php
require_once GTFWConfiguration::GetValue('applicaton','docroot').'module/sks_dosen/response/ProcessSksDosen.proc.class.php';

class DoUpdateSksDosen extends HtmlResponse {
      
   function ProcessRequest() {
      $obj = new ProcessSksDosen();
      $urlRedirect = $obj->Update();
      $this->RedirectTo($urlRedirect);
      return NULL;
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
