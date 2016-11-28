<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jenis_karya/response/ProcessJenisKarya.proc.class.php';
   
class DoDeleteJenisKarya extends HtmlResponse
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