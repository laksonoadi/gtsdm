<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jenis_publikasi/response/ProcessJenisPublikasi.proc.class.php';
   
//echo dump2($_REQUEST);die;
class DoInputJenisPublikasi extends HtmlResponse
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