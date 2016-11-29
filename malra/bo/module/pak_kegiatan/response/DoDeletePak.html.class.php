<?php
require_once GTFWConfiguration::GetValue('applicaton','docroot').'module/pak_kegiatan/response/ProcessPak.proc.class.php';

class DoDeletePak extends HtmlResponse {
   function TemplateModule() {
   
   }
   
   function ProcessRequest() {
      $obj = new ProcessPak();
      $urlRedirect = $obj->Delete();
      $this->RedirectTo($urlRedirect);
      return NULL;
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
