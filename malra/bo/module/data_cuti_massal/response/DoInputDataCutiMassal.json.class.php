<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_cuti_massal/response/ProcessDataCutiMassal.proc.class.php';

class DoInputDataCutiMassal extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDataCutiMassal(); 
//echo $urlRedirect; exit;
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
      
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
