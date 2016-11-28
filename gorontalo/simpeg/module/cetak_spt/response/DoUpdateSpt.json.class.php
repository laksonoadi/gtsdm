<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/cetak_spt/response/ProcessMutasiSatuanKerja.proc.class.php';

class DoUpdateSpt extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
      $ret = "json";
      $obj = new Process($ret);
      //set post
      $obj->SetPost($_POST);
      
      $urlRedirect = $obj->InputData(); 
//echo $urlRedirect; exit;
      $this->RedirectTo($urlRedirect) ;
      // return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
      return NULL;
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
