<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_seminar/response/ProcessMutasiSeminar.proc.class.php';
   
class DoDeleteMutasiSeminar extends HtmlResponse
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