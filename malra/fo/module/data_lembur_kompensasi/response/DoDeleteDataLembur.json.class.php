<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_lembur_kompensasi/response/ProcessDataLembur.proc.class.php';
   
class DoDeleteDataLembur extends JsonResponse
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