<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/satuan_kerja/response/ProcessSatuanKerja.proc.class.php';

class DoMoveSatuanKerja extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $result = $obj->MoveSatker();
      
      if($result) {
          $this->RedirectTo(Dispatcher::Instance()->GetUrl('satuan_kerja', 'satuanKerja', 'view', 'html'));
      } else {
          $this->RedirectTo(Dispatcher::Instance()->GetUrl('satuan_kerja', 'moveSatuanKerja', 'view', 'html'));
      }
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
