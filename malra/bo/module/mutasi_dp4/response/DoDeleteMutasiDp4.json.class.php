<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_dp4/response/ProcessMutasiDp4.proc.class.php';
   
class DoDeleteMutasiDp4 extends JsonResponse
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