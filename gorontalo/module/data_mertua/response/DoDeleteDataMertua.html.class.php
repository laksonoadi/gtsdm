<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_mertua/response/ProcessDataMertua.proc.class.php';
   
class DoDeleteDataMertua extends HtmlResponse
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