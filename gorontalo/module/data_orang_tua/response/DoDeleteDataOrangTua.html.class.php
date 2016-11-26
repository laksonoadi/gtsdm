<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_orang_tua/response/ProcessDataOrangTua.proc.class.php';
   
class DoDeleteDataOrangTua extends HtmlResponse
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