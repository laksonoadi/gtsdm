<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/kode_nikah/response/ProcessKodeNikah.proc.class.php';

class DoDeleteKodeNikah extends JsonResponse {
   function TemplateModule() {
   
   }
   
   function ProcessRequest() {
      $obj = new ProcessKodeNikah();
      $urlRedirect = $obj->Delete();
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
