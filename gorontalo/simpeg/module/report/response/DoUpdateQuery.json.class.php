<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessQuery.class.php';

class DoUpdateQuery extends JsonResponse {

   function ProcessRequest() {
      $repObj = new ProcessQuery();
      $urlRedirect = $repObj->Update();
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")') ;
    }

}
?>
