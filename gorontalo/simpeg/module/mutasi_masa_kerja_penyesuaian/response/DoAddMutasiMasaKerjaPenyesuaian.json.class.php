<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_masa_kerja_penyesuaian/response/ProcessMutasiMasaKerjaPenyesuaian.proc.class.php';

class DoAddMutasiMasaKerjaPenyesuaian extends JsonResponse {

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
