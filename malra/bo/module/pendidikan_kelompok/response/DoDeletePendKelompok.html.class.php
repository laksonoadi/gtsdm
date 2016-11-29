<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/pendidikan_kelompok/response/ProcessPendKelompok.proc.class.php';
   
class DoDeletePendKelompok extends HtmlResponse
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