<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_seminar/response/ProcessMutasiSeminar.proc.class.php';
   
class DoDeleteMutasiSeminar extends JsonResponse
{
   function TemplateModule()
   {
   }
   
   function ProcessRequest()
   {
    $ret = 'json';
    $Obj = new Process($ret);
    $Obj->SetPost($_POST);
	  $urlRedirect = $Obj->Delete();
	  return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
   }
   
   function ParseTemplate($data = NULL)
   {
   }

}

?>