<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/asal_dana/response/ProcessAsalDana.proc.class.php';
   
class DoDeleteAsalDana extends HtmlResponse
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