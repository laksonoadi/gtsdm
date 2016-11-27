<?php

require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/peran_penelitian/response/ProcessPeranPenelitian.proc.class.php';

class DoAddPeranPenelitian extends HtmlResponse
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