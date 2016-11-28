<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_istri_suami/response/ProcessDataIstriSuami.proc.class.php';

class DoInputDataIstriSuami extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDatistri(); 
//echo $urlRedirect; exit;
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
      
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
