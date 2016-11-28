<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_penelitian/response/ProcessMutasiPenelitian.proc.class.php';
   
class DoDeleteMutasiPenelitian extends JsonResponse
{
   function TemplateModule()
   {
   }
   
   function ProcessRequest()
   {
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