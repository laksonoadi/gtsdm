<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_pegawai/response/ProcessKontrakPegawai.proc.class.php';
   
class DoDeleteKontrakPegawai extends HtmlResponse
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