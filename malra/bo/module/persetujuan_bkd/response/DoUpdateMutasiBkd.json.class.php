<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_bkd/response/ProcessMutasiBkd.proc.class.php';

class DoUpdateMutasiBkd extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $ret = "json";
      $obj = new Process($ret);
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->UpdateData(); 
		//echo $urlRedirect; exit;
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
      
    }

   function ParseTemplate($data = NULL) {
   }
}
?>