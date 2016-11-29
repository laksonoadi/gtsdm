<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessTable.class.php';

class DoUpdateTable extends HtmlResponse {

   function ProcessRequest() {
      $repObj = new ProcessTable();
      $urlRedirect = $repObj->Update();
      $this->RedirectTo($urlRedirect);
      return NULL;
    }

}
?>
