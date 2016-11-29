<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/persetujuan_benefit/response/ProcessAppDataBenefit.proc.class.php';

class DoInputAppBenefit extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputDataBenefit(); 
//echo $urlRedirect; exit;
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
      
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
