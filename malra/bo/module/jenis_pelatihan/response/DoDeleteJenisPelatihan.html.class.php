<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jenis_pelatihan/response/ProcessJenisPelatihan.proc.class.php';
   
class DoDeleteJenisPelatihan extends HtmlResponse
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