<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_mengajar_diluar/response/ProcessMutasiMengajarDiluar.proc.class.php';
   
class DoDeleteMutasiMengajarDiluar extends JsonResponse
{
   function TemplateModule()
   {
   }
   
   function ProcessRequest()
   {
    $Obj = new Process();
    $Obj->SetPost($_POST);
	  $urlRedirect = $Obj->Delete();
	  return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
   }
   
   function ParseTemplate($data = NULL)
   {
   }

}

?>