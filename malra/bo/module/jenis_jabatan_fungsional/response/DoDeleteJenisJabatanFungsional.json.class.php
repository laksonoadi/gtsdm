<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jenis_jabatan_fungsional/response/ProcessJenisJabatanFungsional.proc.class.php';
   
class DoDeleteJenisJabatanFungsional extends JsonResponse
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