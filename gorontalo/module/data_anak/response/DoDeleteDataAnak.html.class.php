<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_anak/response/ProcessDataAnak.proc.class.php';
   
class DoDeleteDataAnak extends HtmlResponse
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