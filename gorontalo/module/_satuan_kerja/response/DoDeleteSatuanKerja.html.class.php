<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/satuan_kerja/response/ProcessSatuanKerja.proc.class.php';
   
class DoDeleteSatuanKerja extends HtmlResponse
{
   function TemplateModule()
   {
   }
   
   function ProcessRequest()
   {
      $Obj = new Process;
	  $Obj->SetPost($_POST);
	  $urlRedirect = $Obj->Delete();
	  $this->RedirectTo($urlRedirect) ;
      return NULL;
   }
   
   function ParseTemplate($data = NULL)
   {
   }

}

?>