<?php
//echo 'sds';exit();
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/jenis_dokumen_kelengkapan/response/ProcessJenisDokumenKelengkapan.proc.class.php';

//echo dump2($_REQUEST);die;
class DoInputJenisDokumenKelengkapan extends HtmlResponse{
  function TemplateModule(){
  }
  
  function ProcessRequest(){
   $proses = new Process;
   if (isset($_GET['id'])){
      $urlRedirect = $proses->Update();
   }else{ 
      $urlRedirect = $proses->Add();
   }
  
   $this->RedirectTo($urlRedirect) ;
   return NULL;  
  }
  
  function ParseTemplate($data = NULL){
  }
}


?>