<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jenis_kepegawaian/response/ProcessJenisKepegawaian.proc.class.php';
   
class DoDeleteJenisKepegawaian extends HtmlResponse
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