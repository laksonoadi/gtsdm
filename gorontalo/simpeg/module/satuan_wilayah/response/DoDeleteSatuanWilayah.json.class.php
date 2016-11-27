<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/satuan_wilayah/response/ProcessSatuanWilayah.proc.class.php';
   
class DoDeleteSatuanWilayah extends JsonResponse
{
   function TemplateModule()
   {
   }
   
   function ProcessRequest()
   {
      $Obj = new Process;
	  $Obj->SetPost($_POST);
	  $urlRedirect = $Obj->Delete();
	  return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
   }
   
   function ParseTemplate($data = NULL)
   {
   }

}

?>