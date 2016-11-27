<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/status_pegawai/response/ProcessStatusPegawai.proc.class.php';
   
class DoDeleteStatusPegawai extends HtmlResponse
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