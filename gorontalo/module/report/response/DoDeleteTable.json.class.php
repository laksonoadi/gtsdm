<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessTable.class.php';

class DoDeleteTable extends JsonResponse {

   function ProcessRequest() {
      $repObj = new ProcessTable();
      $urlRedirect = $repObj->Delete();
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")') ;
    }

}
?>
