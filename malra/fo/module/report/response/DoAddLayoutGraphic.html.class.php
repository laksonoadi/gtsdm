<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessLayoutGraphic.class.php';

class DoAddLayoutGraphic extends HtmlResponse {

   function ProcessRequest() {
      $repObj = new ProcessLayoutGraphic();
      $urlRedirect = $repObj->Add();
      $this->RedirectTo($urlRedirect);
      return NULL;
    }

}
?>
