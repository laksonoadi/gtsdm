<?php
//echo 'sds';exit();
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/status_nikah/response/ProcessStatusNikah.proc.class.php';
   
//echo dump2($_REQUEST);die;
class DoInputStatusNikah extends HtmlResponse
{
   function TemplateModule()
   {
   }
   
   function ProcessRequest()
   {
      $proses = new Process;
      if (isset($_GET['id'])){
         $urlRedirect = $proses->Update();
	  }
      else{ 
	     $urlRedirect = $proses->Add();
	  }
      
	  $this->RedirectTo($urlRedirect) ;
      return NULL;  
   }
   
   function ParseTemplate($data = NULL)
   {
   }
}
   
   
?>