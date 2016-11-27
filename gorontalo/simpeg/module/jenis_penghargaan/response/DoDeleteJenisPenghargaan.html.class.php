<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jenis_penghargaan/response/ProcessJenisPenghargaan.proc.class.php';
   
class DoDeleteJenisPenghargaan extends HtmlResponse
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