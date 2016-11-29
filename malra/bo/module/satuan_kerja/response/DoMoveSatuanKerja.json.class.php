<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/satuan_kerja/response/ProcessSatuanKerja.proc.class.php';

class DoMoveSatuanKerja extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {

      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $result = $obj->MoveSatker();
      
      if($result) {
          return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element", "'.Dispatcher::Instance()->GetUrl('satuan_kerja', 'satuanKerja', 'view', 'html').'&ascomponent=1")');
      } else {
          return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element", "'.Dispatcher::Instance()->GetUrl('satuan_kerja', 'moveSatuanKerja', 'view', 'html').'&ascomponent=1")');
      }
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
