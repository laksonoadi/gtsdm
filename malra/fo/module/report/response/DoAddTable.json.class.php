<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessTable.class.php';

class DoAddTable extends JsonResponse {

   function ProcessRequest() {
      $repObj = new ProcessTable();
      $urlRedirect = $repObj->Add();
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")') ;
    }

}
?>
