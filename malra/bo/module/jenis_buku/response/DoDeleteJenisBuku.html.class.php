<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jenis_buku/response/ProcessJenisBuku.proc.class.php';
   
class DoDeleteJenisBuku extends HtmlResponse
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