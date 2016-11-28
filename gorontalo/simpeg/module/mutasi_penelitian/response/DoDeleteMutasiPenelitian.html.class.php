<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_penelitian/response/ProcessMutasiPenelitian.proc.class.php';
   
class DoDeleteMutasiPenelitian extends HtmlResponse
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