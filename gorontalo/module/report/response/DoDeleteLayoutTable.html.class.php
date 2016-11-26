<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessLayout.class.php';

class DoDeleteLayoutTable extends HtmlResponse {

   function ProcessRequest() {
      $repObj = new ProcessLayout();
      $urlRedirect = $repObj->Delete();
      $this->RedirectTo($urlRedirect);
      return NULL;
    }

}
?>
