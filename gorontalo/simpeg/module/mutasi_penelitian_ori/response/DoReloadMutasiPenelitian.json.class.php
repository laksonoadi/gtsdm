<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_penelitian/response/ProcessMutasiPenelitian.proc.class.php';

class DoReloadMutasiPenelitian extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      //$ret = "json";
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      $urlRedirect = $obj->ReloadData(); 
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
      
    }

   function ParseTemplate($data = NULL) {
   }
}
?>