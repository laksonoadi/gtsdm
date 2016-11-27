<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/tipe_struktural/response/ProcessTipeStruktural.proc.class.php';
   
class DoDeleteTipeStruktural extends HtmlResponse
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