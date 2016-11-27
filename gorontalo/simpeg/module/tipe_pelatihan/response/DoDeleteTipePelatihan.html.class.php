<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/tipe_pelatihan/response/ProcessTipePelatihan.proc.class.php';
   
class DoDeleteTipePelatihan extends HtmlResponse
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