<?php
require_once GTFWConfiguration::GetValue('applicaton','docroot').'module/pak/response/ProcessPak.proc.class.php';

class DoAddPak extends JsonResponse {
   function TemplateModule() {
   
   }
   
   function ProcessRequest() {
      $obj = new ProcessPak();
      
      $urlRedirect = $obj->Add();
      
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
