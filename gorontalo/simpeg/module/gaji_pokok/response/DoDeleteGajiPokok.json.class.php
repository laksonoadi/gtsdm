<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/gaji_pokok/response/ProcessGajiPokok.proc.class.php';
   
class DoDeleteGajiPokok extends JsonResponse
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