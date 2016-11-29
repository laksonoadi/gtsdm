<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/warga_negara/response/ProcessWargaNegara.proc.class.php';
   
class DoDeleteWargaNegara extends HtmlResponse
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