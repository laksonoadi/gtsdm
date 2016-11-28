<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/benefit/response/ProcessBenefit.proc.class.php';

class DoDeleteBenefit extends HtmlResponse{
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