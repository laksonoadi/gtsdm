<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/tunjangan_kesehatan/response/ProcessTunjanganKesehatan.proc.class.php';
   
class DoDeleteTunjanganKesehatan extends HtmlResponse
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