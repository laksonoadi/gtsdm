<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/tingkat_seminar/response/ProcessTingkatSeminar.proc.class.php';
   
class DoDeleteTingkatSeminar extends HtmlResponse
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