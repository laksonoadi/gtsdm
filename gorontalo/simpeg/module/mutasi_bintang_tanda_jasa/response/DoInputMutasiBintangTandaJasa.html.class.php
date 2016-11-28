<?php 
ini_set('display_errors',1);
error_reporting(E_ALL);
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_bintang_tanda_jasa/response/ProcessMutasiBintangTandaJasa.proc.class.php';

class DoInputMutasiBintangTandaJasa extends HtmlResponse {

   function ProcessRequest() {
      //echo "<pre>";print_r($_GET);echo "</pre>";exit();
      $ret = "html";
      $obj = new Process($ret);
      $urlRedirect = $obj->InputData();          
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

}
?>
