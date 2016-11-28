<?php
require_once GTFWConfiguration::GetValue('applicaton','docroot').'module/pak_unsur/response/ProcessPak.proc.class.php';

class DoUpdatePak extends HtmlResponse {
      
   function ProcessRequest() {
      $obj = new ProcessPak();
      $urlRedirect = $obj->Update();
      $this->RedirectTo($urlRedirect);
      return NULL;
      
   }
   function ParseTemplate($dat=NULL){
   
   }
}

?>
