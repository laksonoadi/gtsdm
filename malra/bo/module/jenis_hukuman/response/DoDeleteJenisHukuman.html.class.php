<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jenis_hukuman/response/ProcessJenisHukuman.proc.class.php';
   
class DoDeleteJenisHukuman extends HtmlResponse
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