<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_kunjungan/response/ProcessMutasiKunjungan.proc.class.php';
   
class DoDeleteMutasiKunjungan extends HtmlResponse
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