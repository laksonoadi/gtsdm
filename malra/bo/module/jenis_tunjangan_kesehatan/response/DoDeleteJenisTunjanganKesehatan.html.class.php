<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jenis_tunjangan_kesehatan/response/ProcessJenisTunjanganKesehatan.proc.class.php';
   
class DoDeleteJenisTunjanganKesehatan extends HtmlResponse
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