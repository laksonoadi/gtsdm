<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jenis_kunjungan_ln/response/ProcessJenisKunjunganLn.proc.class.php';
   
class DoDeleteJenisKunjunganLn extends HtmlResponse
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