<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/tunjangan_kesehatan/response/ProcessTunjanganKesehatan.proc.class.php';

class DoInputTunjanganKesehatan extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDattun(); 
//echo $urlRedirect; exit;
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
      
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
