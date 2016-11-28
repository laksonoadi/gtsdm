<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_bintang_tanda_jasa/response/ProcessMutasiBintangTandaJasa.proc.class.php';
   
class DoDeleteMutasiBintangTandaJasa extends JsonResponse
{
   function ProcessRequest()
   {
      $ret = "json";
    $Obj = new Process($ret);
	  $urlRedirect = $Obj->Delete();
	  return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
   }
   
}

?>