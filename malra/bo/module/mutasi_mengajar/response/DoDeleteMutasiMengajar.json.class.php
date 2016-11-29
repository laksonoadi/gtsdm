<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_mengajar/response/ProcessMutasiMengajar.proc.class.php';
   
class DoDeleteMutasiMengajar extends JsonResponse
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