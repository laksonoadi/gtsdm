<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/periode_cuti_pegawai/response/ProcessCutiPegawai.proc.class.php';

class DoDeleteCutiPegawai extends HtmlResponse{
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