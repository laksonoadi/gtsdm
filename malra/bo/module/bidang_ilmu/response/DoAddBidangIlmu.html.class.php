<?php

require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/bidang_ilmu/response/ProcessBidangIlmu.proc.class.php';

class DoAddBidangIlmu extends HtmlResponse
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