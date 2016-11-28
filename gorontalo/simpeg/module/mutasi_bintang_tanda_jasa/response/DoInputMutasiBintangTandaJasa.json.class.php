<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_bintang_tanda_jasa/response/ProcessMutasiBintangTandaJasa.proc.class.php';

class DoInputMutasiBintangTandaJasa extends JsonResponse {

   function ProcessRequest() {
      $ret = "json";
      $obj = new Process($ret);
      
      $urlRedirect = $obj->InputData(); 
//echo $urlRedirect; exit;
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
      
    }

}
?>
