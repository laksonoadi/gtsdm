<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/penghasilan/response/ProcessPenghasilan.proc.class.php';
   
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
	  $this->RedirectTo($urlRedirect) ;
      return NULL; 
   }
   
   function ParseTemplate($data = NULL)
   {
   }
}

?>