<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_beasiswa/response/ProcessMutasiBeasiswa.proc.class.php';
   
class DoDeleteMutasiBeasiswa extends JsonResponse
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