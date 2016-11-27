<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_istri_suami/response/ProcessDataIstriSuami.proc.class.php';
   
class DoDeleteDataIstriSuami extends JsonResponse
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