<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_pekerjaan_pegawai/response/ProcessMutasiPekerjaanPegawai.proc.class.php';
   
class DoDeleteMutasiPekerjaanPegawai extends HtmlResponse
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