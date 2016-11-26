<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessTable.class.php';

class DoDeleteTable extends HtmlResponse {

   function ProcessRequest() {
      $repObj = new ProcessTable();
      $urlRedirect = $repObj->Delete();
      $this->RedirectTo($urlRedirect);
      return NULL;
    }

}
?>
