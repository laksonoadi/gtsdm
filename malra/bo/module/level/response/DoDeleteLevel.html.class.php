<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/level/response/ProcessLevel.proc.class.php';

class DoDeleteLevel extends HtmlResponse{
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