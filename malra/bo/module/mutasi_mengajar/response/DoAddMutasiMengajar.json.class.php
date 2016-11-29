<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_mengajar/response/ProcessMutasiMengajar.proc.class.php';

class DoAddMutasiMengajar extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $ret = "json";
      $obj = new Process($ret);
      //set post
      $obj->SetPost($_POST);
      $urlRedirect = $obj->InputData(); 
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
      
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
