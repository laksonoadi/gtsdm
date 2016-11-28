<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/balance_benefit/response/ProcessBalanceBenefit.proc.class.php';

class DoDeleteBalanceBenefit extends HtmlResponse{
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