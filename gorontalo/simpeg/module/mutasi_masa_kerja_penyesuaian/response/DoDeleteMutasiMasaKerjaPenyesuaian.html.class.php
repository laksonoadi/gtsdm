<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_masa_kerja_penyesuaian/response/ProcessMutasiMasaKerjaPenyesuaian.proc.class.php';
   
class DoDeleteMutasiMasaKerjaPenyesuaian extends HtmlResponse
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