<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/response/ProcessGajiPegawai.proc.class.php';

class DoDeleteGajiPegawai extends JsonResponse {

	function TemplateModule() {}

	function ProcessRequest() {
		$gaji_pegawaiObj = new ProcessGajiPegawai();
		$urlRedirect = $gaji_pegawaiObj->Delete();
		return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
	}

	function ParseTemplate($data = NULL) {}
}
?>
