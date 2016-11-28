<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/response/ProcessPayrollPegawai.proc.class.php';

class DoPayrollPegawai extends JsonResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$obj = new ProcessPayrollPegawai();
		$urlRedirect = $obj->Add();
		return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
	 }

	function ParseTemplate($data = NULL) {
	}
}
?>
