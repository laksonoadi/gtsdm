<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_jabatan_struktural/response/ProcessMutasiJabatanStruktural.proc.class.php';
   
class DoDeleteMutasiJabatanStruktural extends HtmlResponse
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