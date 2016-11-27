<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/hari_libur/response/ProcessHariLibur.proc.class.php';

class DoDeleteHariLibur extends HtmlResponse{
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