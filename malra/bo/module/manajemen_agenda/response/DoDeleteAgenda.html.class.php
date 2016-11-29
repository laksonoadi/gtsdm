<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/manajemen_agenda/response/ProcessAgenda.proc.class.php';
   
class DoDeleteAgenda extends HtmlResponse
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