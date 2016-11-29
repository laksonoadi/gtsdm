<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/periode_cuti_pegawai/response/ProcessCutiPegawai.proc.class.php';

class DoDeleteCutiPegawai extends JsonResponse{
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