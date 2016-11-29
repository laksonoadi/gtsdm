<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/ref_periode_cuti/response/ProcessPeriodeCuti.proc.class.php';

class DoInputPeriodeCuti extends JsonResponse{
  function TemplateModule(){
  }
  
  function ProcessRequest(){
    $proses = new Process;
    
    if (isset($_GET['id'])){
      $urlRedirect = $proses->Update();
    }else{ 
      $urlRedirect = $proses->Add();
    }
    
    return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');  
  }
}


?>