<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/rekomendasi/response/ProcessRekomendasi.proc.class.php';
   
class DoDeleteRekomendasi extends HtmlResponse
{
   function TemplateModule()
   {
   }
   
   function ProcessRequest()
   {
      $Obj = new Process;
	  $urlRedirect = $Obj->Delete();
	  $this->RedirectTo($urlRedirect) ;
      return NULL;
   }
   
   function ParseTemplate($data = NULL)
   {
   }

}

?>