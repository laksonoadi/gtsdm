<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessTable.class.php';

class DoUpdateTable extends JsonResponse {

   function ProcessRequest() {
      $repObj = new ProcessTable();
      $urlRedirect = $repObj->Update();
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")') ;
    }

}
?>
