<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jenis_tunjangan_kesehatan/response/ProcessJenisTunjanganKesehatan.proc.class.php';
   
class DoDeleteJenisTunjanganKesehatan extends JsonResponse
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