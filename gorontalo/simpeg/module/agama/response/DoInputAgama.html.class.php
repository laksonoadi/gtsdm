<?php
//echo 'sds';exit();
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/agama/response/ProcessAgama.proc.class.php';

//echo dump2($_REQUEST);die;
class DoInputAgama extends HtmlResponse{
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