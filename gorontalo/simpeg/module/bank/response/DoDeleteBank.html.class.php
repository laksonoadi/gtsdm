<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/bank/response/ProcessBank.proc.class.php';

class DoDeleteBank extends HtmlResponse{
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