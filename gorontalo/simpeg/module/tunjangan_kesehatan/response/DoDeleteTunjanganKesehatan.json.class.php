<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/tunjangan_kesehatan/response/ProcessTunjanganKesehatan.proc.class.php';
   
class DoDeleteTunjanganKesehatan extends JsonResponse
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