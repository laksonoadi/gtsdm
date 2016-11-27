<?php
//echo 'sds';exit();
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/penghasilan/response/ProcessPenghasilan.proc.class.php';
   
//echo dump2($_REQUEST);die;
class DoMovePenghasilan extends HtmlResponse
{
   function TemplateModule()
   {
   }
   
   function ProcessRequest()
   {
      $proses = new Process;
	  if(isset($_GET['phslId']) AND isset($_GET['move'])){
	     $urlRedirect = $proses->MoveOrder();
	  }
	  return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")'); 
   }
   
   function ParseTemplate($data = NULL)
   {
   }
}
   
   
?>