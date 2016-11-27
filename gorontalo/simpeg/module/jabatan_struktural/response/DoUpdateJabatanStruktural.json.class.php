<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/jabatan_struktural/response/ProcessJabatanStruktural.proc.class.php';

class DoUpdateJabatanStruktural extends JsonResponse {
      
   function ProcessRequest() {
      $obj = new ProcessJabatanStruktural();
      $urlRedirect = $obj->Update();
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
