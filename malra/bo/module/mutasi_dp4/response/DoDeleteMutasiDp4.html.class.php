<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_dp4/response/ProcessMutasiDp4.proc.class.php';
   
class DoDeleteMutasiDp4 extends HtmlResponse
{
   function TemplateModule()
   {
   }
   
   function ProcessRequest()
   {
      $Obj = new Process($ret);
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