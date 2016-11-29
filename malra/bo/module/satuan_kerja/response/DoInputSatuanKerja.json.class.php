<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/satuan_kerja/response/ProcessSatuanKerja.proc.class.php';

class DoInputSatuanKerja extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {

      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputSatker(); 
      
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
      
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
