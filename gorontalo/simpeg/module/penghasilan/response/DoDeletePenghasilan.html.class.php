<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/penghasilan/response/ProcessPenghasilan.proc.class.php';
   
class DoDeletePenghasilan extends HtmlResponse
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