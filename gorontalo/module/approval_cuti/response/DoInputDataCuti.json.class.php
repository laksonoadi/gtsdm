<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/approval_cuti/response/ProcessDataCuti.proc.class.php';

class DoInputDataCuti extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDataCuti(); 
//echo $urlRedirect; exit;
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
      
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
