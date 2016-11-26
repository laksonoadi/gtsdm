<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_organisasi_pegawai/response/ProcessMutasiOrganisasiPegawai.proc.class.php';
   
class DoDeleteMutasiOrganisasiPegawai extends HtmlResponse
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