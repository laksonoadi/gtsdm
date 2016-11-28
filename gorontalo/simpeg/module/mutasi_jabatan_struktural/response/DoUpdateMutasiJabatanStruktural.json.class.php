<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_jabatan_struktural/response/ProcessMutasiJabatanStruktural.proc.class.php';

class DoUpdateMutasiJabatanStruktural extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $ret = "json";
      $obj = new Process($ret);
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputData(); 
//echo $urlRedirect; exit;
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
      
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
