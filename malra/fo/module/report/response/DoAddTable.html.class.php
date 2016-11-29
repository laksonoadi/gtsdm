<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessTable.class.php';

class DoAddTable extends HtmlResponse {

   function ProcessRequest() {
      $repObj = new ProcessTable();
      $urlRedirect = $repObj->Add();
      $this->RedirectTo($urlRedirect);
      return NULL;
    }

}
?>
