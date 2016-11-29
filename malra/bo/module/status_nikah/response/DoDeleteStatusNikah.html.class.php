<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/status_nikah/response/ProcessStatusNikah.proc.class.php';
   
class DoDeleteStatusNikah extends HtmlResponse
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