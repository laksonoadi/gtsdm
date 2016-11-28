<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jenis_pendapatan_lain/response/ProcessJenisPendapatanLain.proc.class.php';
   
class DoDeleteJenisPendapatanLain extends HtmlResponse
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