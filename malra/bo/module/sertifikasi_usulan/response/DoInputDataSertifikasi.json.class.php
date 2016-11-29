<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/sertifikasi_usulan/response/ProcessDataSertifikasi.proc.class.php';

class DoInputDataSertifikasi extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDataSertifikasi();
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
      
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
