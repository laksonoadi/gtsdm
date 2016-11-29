<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_saudara_kandung/response/ProcessDataSaudaraKandung.proc.class.php';

class DoInputDataSaudaraKandung extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDatsdr(); 
//echo $urlRedirect; exit;
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
      
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
