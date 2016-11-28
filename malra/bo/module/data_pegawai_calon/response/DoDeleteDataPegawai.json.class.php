<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_pegawai_calon/response/ProcessDataPegawai.proc.class.php';
   
class DoDeleteDataPegawai extends JsonResponse
{
   function TemplateModule()
   {
   }
   
   function ProcessRequest()
   {
    $ret = "html";
    $Obj = new Process($ret);
    //$Obj = new Process();
    $Obj->SetPost($_POST);
	  $urlRedirect = $Obj->Delete();
	  return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
   }
   
   function ParseTemplate($data = NULL)
   {
   }

}

?>