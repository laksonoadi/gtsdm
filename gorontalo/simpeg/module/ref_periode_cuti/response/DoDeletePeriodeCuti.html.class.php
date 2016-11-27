<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/ref_periode_cuti/response/ProcessPeriodeCuti.proc.class.php';

class DoDeletePeriodeCuti extends HtmlResponse{
  function TemplateModule(){
  }
  
  function ProcessRequest(){
    $Obj = new Process;
    $urlRedirect = $Obj->Delete();
    $this->RedirectTo($urlRedirect) ;
    return NULL;
  }
  
  function ParseTemplate($data = NULL){
  }

}

?>