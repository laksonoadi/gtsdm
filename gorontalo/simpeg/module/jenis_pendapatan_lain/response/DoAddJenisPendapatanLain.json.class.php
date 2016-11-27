<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jenis_pendapatan_lain/response/ProcessJenisPendapatanLain.proc.class.php';
   
class DoAddJenisPendapatanLain extends JsonResponse
{
   function TemplateModule()
   {
   }
   
   function ProcessRequest()
   {
      $proses = new Process;
	   
      if (isset($_GET['id'])){
         $urlRedirect = $proses->Update();
	  }
      else{ 
	     $urlRedirect = $proses->Add();
	  }
      
		return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');  
   }
}
   
   
?>