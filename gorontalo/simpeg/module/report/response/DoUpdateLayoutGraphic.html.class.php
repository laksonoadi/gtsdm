<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessLayoutGraphic.class.php';

class DoUpdateLayoutGraphic extends HtmlResponse {

   function ProcessRequest() {
      $repObj = new ProcessLayoutGraphic();
      $urlRedirect = $repObj->Update();
      $this->RedirectTo($urlRedirect);
      return NULL;
    }

}
?>
