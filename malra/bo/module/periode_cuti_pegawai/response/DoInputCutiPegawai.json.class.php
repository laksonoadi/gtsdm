<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/periode_cuti_pegawai/response/ProcessCutiPegawai.proc.class.php';

class DoInputCutiPegawai extends JsonResponse{
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