<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/pak_unsur/response/ProcessPak.proc.class.php';

class DoDeletePak extends JsonResponse {
   function TemplateModule() {
   
   }
   
   function ProcessRequest() {
      $obj = new ProcessPak();
      $urlRedirect = $obj->Delete();
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
