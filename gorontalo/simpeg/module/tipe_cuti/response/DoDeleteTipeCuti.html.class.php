<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/tipe_cuti/response/ProcessTipeCuti.proc.class.php';
   
class DoDeleteTipeCuti extends HtmlResponse
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