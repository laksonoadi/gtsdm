<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/pekerjaan/response/ProcessPekerjaan.proc.class.php';
   
class DoDeletePekerjaan extends JsonResponse
{
   function TemplateModule()
   {
   }
   
   function ProcessRequest()
   {
      $Obj = new Process;
	  $urlRedirect = $Obj->Delete();
	  return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
   }
   
   function ParseTemplate($data = NULL)
   {
   }

}

?>