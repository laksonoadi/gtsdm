<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jenis_jabatan_fungsional/response/ProcessJenisJabatanFungsional.proc.class.php';
   
class DoDeleteJenisJabatanFungsional extends HtmlResponse
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