<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/manajemen_berita/response/ProcessBerita.proc.class.php';
   
class DoDeleteBerita extends HtmlResponse
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