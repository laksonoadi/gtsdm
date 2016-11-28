<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/report/response/ProcessQuery.class.php';

class DoDeleteQuery extends HtmlResponse {

   function ProcessRequest() {
      $repObj = new ProcessQuery();
      $urlRedirect = $repObj->Delete();
      $this->RedirectTo($urlRedirect);
      return NULL;
    }

}
?>
