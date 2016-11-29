<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/warga_negara/response/ProcessWargaNegara.proc.class.php';
   
class DoDeleteWargaNegara extends JsonResponse
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