<?php

require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/status_pegawai/response/ProcessStatusPegawai.proc.class.php';

class DoAddStatusPegawai extends HtmlResponse
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