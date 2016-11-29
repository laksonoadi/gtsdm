<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessLayoutGraphic.class.php';

class DoDeleteLayoutGraphic extends HtmlResponse {

   function ProcessRequest() {
      $repObj = new ProcessLayoutGraphic();
      $urlRedirect = $repObj->Delete();
      $this->RedirectTo($urlRedirect);
      return NULL;
    }

}
?>
