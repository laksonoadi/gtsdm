<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_pegawai_calon/response/ProcessDataPegawai.proc.class.php';
   
class DoDeleteDataPegawai extends HtmlResponse
{
   function TemplateModule()
   {
   }
   
   function ProcessRequest()
   {
      $ret = "html";
      $Obj = new Process($ret);
      //$Obj = new Process();
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