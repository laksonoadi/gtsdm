<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessQuery.class.php';

class DoUpdateQuery extends HtmlResponse {

   function ProcessRequest() {
      $repObj = new ProcessQuery();
      $urlRedirect = $repObj->Update();
      $this->RedirectTo($urlRedirect);
      return NULL;
    }

}
?>
