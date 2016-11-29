<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_istri_suami/response/ProcessDataIstriSuami.proc.class.php';
   
class DoDeleteDataIstriSuami extends HtmlResponse
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