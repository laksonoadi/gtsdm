<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessLayout.class.php';

class DoAddLayoutTable extends HtmlResponse {

   function ProcessRequest() {
      $repObj = new ProcessLayout();
      $urlRedirect = $repObj->Add();
      $this->RedirectTo($urlRedirect);
      return NULL;
    }

}
?>
