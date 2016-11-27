<?php
require_once GTFWConfiguration::GetValue('applicaton','docroot').'module/sks_dosen/response/ProcessSksDosen.proc.class.php';

class DoDeleteSksDosen extends JsonResponse {
   function TemplateModule() {
   
   }
   
   function ProcessRequest() {
      $obj = new ProcessSksDosen();
      $urlRedirect = $obj->Delete();
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
