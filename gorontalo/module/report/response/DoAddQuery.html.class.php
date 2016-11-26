<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessQuery.class.php';

class DoAddQuery extends HtmlResponse {

   function ProcessRequest() {
      $repObj = new ProcessQuery();
      $urlRedirect = $repObj->Add();
      $this->RedirectTo($urlRedirect);
      return NULL;
    }

}
?>
