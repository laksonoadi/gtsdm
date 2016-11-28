<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/bidang_kepakaran/response/ProcessBidangKepakaran.proc.class.php';
   
class DoDeleteBidangKepakaran extends HtmlResponse
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