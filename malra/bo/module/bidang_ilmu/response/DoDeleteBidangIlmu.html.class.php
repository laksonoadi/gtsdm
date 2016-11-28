<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/bidang_ilmu/response/ProcessBidangIlmu.proc.class.php';
   
class DoDeleteBidangIlmu extends HtmlResponse
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