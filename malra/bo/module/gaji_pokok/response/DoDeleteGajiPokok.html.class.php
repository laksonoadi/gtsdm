<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/gaji_pokok/response/ProcessGajiPokok.proc.class.php';
   
class DoDeleteGajiPokok extends HtmlResponse
{
   function TemplateModule()
   {
   }
   
   function ProcessRequest()
   {
      $Obj = new Process();
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