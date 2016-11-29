<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_organisasi_pegawai/response/ProcessMutasiOrganisasiPegawai.proc.class.php';

class DoAddMutasiOrganisasiPegawai extends JsonResponse {

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
