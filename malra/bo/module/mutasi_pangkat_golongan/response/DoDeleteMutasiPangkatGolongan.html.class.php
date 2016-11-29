<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_pangkat_golongan/response/ProcessMutasiPangkatGolongan.proc.class.php';
   
class DoDeleteMutasiPangkatGolongan extends HtmlResponse
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