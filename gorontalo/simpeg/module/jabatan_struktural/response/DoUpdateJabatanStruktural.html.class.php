<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/jabatan_struktural/response/ProcessJabatanStruktural.proc.class.php';

class DoUpdateJabatanStruktural extends HtmlResponse {
      
   function ProcessRequest() {
      $obj = new ProcessJabatanStruktural();
      $urlRedirect = $obj->Update();
      $this->RedirectTo($urlRedirect);
      return NULL;
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
