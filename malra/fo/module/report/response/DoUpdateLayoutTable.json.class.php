<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessLayout.class.php';

class DoUpdateLayoutTable extends JsonResponse {

   function ProcessRequest() {
      $repObj = new ProcessLayout();
      $urlRedirect = $repObj->Update();
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.htmlentities($urlRedirect).
         '&ascomponent=1")') ;
    }

}
?>
