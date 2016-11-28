<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/balance_benefit/response/ProcessBalanceBenefit.proc.class.php';

class DoDeleteBalanceBenefit extends JsonResponse{
  function TemplateModule(){
  }
  
  function ProcessRequest(){
   $Obj = new Process;
   $urlRedirect = $Obj->Delete();
   return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
  }
  
  function ParseTemplate($data = NULL){
  }
}

?>