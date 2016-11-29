<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/satuan_wilayah/response/ProcessSatuanWilayah.proc.class.php';

class DoInputSatuanWilayah extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {

      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputSatwil(); 
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
