<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jenis_publikasi/response/ProcessJenisPublikasi.proc.class.php';
   
class DoDeleteJenisPublikasi extends HtmlResponse
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