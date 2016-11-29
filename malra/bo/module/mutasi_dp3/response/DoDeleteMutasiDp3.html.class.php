<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_dp3/response/ProcessMutasiDp3.proc.class.php';
   
class DoDeleteMutasiDp3 extends HtmlResponse
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