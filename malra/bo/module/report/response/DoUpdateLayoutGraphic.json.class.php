<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessLayoutGraphic.class.php';

class DoUpdateLayoutGraphic extends JsonResponse {

   function ProcessRequest() {
      $repObj = new ProcessLayoutGraphic();
      $urlRedirect = $repObj->Update();
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")') ;
    }

}
?>
