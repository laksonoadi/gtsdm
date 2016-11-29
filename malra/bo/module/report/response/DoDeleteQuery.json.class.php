<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessQuery.class.php';

class DoDeleteQuery extends JsonResponse {

   function ProcessRequest() {
      $repObj = new ProcessQuery();
      $urlRedirect = $repObj->Delete();
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")') ;
    }

}
?>
