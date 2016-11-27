<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/status_pegawai/response/ProcessStatusPegawai.proc.class.php';
   
class DoDeleteStatusPegawai extends JsonResponse
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