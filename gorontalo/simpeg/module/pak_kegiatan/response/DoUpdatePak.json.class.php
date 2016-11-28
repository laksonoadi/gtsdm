<?php
require_once GTFWConfiguration::GetValue('applicaton','docroot').'module/pak_kegiatan/response/ProcessPak.proc.class.php';

class DoUpdatePak extends JsonResponse {
      
   function ProcessRequest() {
      $obj = new ProcessPak();
      $urlRedirect = $obj->Update();
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
      
   }
   function ParseTemplate($data=NULL){
   
   }
}

?>
