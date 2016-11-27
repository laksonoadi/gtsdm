<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/peran_penelitian/response/ProcessPeranPenelitian.proc.class.php';
   
class DoDeletePeranPenelitian extends HtmlResponse
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