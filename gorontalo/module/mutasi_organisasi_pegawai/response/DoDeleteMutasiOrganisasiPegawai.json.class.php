<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_organisasi_pegawai/response/ProcessMutasiOrganisasiPegawai.proc.class.php';
   
class DoDeleteMutasiOrganisasiPegawai extends JsonResponse
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