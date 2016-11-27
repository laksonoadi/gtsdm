<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_saudara_kandung/response/ProcessDataSaudaraKandung.proc.class.php';
   
class DoDeleteDataSaudaraKandung extends JsonResponse
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