<?php
require_once GTFWConfiguration::GetValue('applicaton','docroot').'module/pangkat_golongan/response/ProcessPangkatGolongan.proc.class.php';

class DoAddPangkatGolongan extends JsonResponse {
   function TemplateModule() {
   
   }
   
   function ProcessRequest() {
      $obj = new ProcessPangkatGolongan();
      
      $urlRedirect = $obj->Add();
      
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>