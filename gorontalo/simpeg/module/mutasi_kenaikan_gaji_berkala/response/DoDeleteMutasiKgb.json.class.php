<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_kenaikan_gaji_berkala/response/ProcessMutasiKgb.proc.class.php';
   
class DoDeleteMutasiKgb extends JsonResponse
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