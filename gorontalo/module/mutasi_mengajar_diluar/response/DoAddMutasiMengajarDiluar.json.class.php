<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_mengajar_diluar/response/ProcessMutasiMengajarDiluar.proc.class.php';

class DoAddMutasiMengajarDiluar extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      //$ret = "json";
      $obj = new Process();
      //set post
      $obj->SetPost($_POST);
      $urlRedirect = $obj->InputData(); 
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
      
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
