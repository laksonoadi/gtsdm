<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessLayout.class.php';

class DoUpdateLayoutTable extends HtmlResponse {

   function ProcessRequest() {
      $repObj = new ProcessLayout();
      $urlRedirect = $repObj->Update();
      $this->RedirectTo($urlRedirect);
      return NULL;
    }

}
?>
