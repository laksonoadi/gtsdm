<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_jabatan_fungsional/response/ProcessJabatanFungsional.proc.class.php';
   
class DoDeleteJabatanFungsional extends JsonResponse
{
   function TemplateModule()
   {
   }
   
   function ProcessRequest()
   {
    $ret = "json";
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