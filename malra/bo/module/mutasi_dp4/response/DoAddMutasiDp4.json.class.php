<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_dp4/response/ProcessMutasiDp4.proc.class.php';

class DoAddMutasiDp4 extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputData(); 
//echo $urlRedirect; exit;
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
      
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
