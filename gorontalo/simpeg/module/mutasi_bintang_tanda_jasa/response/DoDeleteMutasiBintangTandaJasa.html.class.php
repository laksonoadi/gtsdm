<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_bintang_tanda_jasa/response/ProcessMutasiBintangTandaJasa.proc.class.php';
   
class DoDeleteMutasiBintangTandaJasa extends HtmlResponse
{
   function TemplateModule()
   {
   }
   
   function ProcessRequest()
   {
      $ret = "html";
      $Obj = new Process($ret);
      $urlRedirect = $Obj->Delete();
      $this->RedirectTo($urlRedirect) ;
      return NULL;
   }
   
   function ParseTemplate($data = NULL)
   {
   }

}

?>