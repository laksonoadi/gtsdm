<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_orang_tua/response/ProcessDataOrangTua.proc.class.php';

class DoInputDataOrangTua extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDatortu(); 
//echo $urlRedirect; exit;
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
      
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
