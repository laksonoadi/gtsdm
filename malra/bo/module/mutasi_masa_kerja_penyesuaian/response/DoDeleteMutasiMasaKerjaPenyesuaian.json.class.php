<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_masa_kerja_penyesuaian/response/ProcessMutasiMasaKerjaPenyesuaian.proc.class.php';
   
class DoDeleteMutasiMasaKerjaPenyesuaian extends JsonResponse
{
   function TemplateModule()
   {
   }
   
   function ProcessRequest()
   {
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