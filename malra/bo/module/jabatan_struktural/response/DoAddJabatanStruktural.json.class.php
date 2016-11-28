<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/jabatan_struktural/response/ProcessJabatanStruktural.proc.class.php';

class DoAddJabatanStruktural extends JsonResponse {
   function TemplateModule() {
   
   }
   
   function ProcessRequest() {
      $obj = new ProcessJabatanStruktural();
      
      $urlRedirect = $obj->Add();
      
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
