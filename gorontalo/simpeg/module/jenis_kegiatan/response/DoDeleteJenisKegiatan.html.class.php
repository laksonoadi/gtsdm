<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jenis_kegiatan/response/ProcessJenisKegiatan.proc.class.php';
   
class DoDeleteJenisKegiatan extends HtmlResponse
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