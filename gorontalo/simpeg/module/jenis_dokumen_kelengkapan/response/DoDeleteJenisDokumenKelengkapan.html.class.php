<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/jenis_dokumen_kelengkapan/response/ProcessJenisDokumenKelengkapan.proc.class.php';

class DoDeleteJenisDokumenKelengkapan extends HtmlResponse{
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