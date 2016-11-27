<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/satuan_wilayah/response/ProcessSatuanWilayah.proc.class.php';
   
class DoDeleteSatuanWilayah extends HtmlResponse
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