<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/ref_status_verifikasi/response/ProcessStatusVerifikasi.proc.class.php';

class DoDeleteStatusVerifikasi extends HtmlResponse{
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