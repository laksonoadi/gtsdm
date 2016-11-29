<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/ref_pph_pegawai_komponen/response/ProcessPphPegawaiKomp.proc.class.php';

class DoAddPphPegawaiKomp extends JsonResponse {

	function TemplateModule() {
	}
	
	function ProcessRequest() {
		$pphObj = new ProcessPph();
		$urlRedirect = $pphObj->Add();
		return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
	 }

	function ParseTemplate($data = NULL) {
	}
}
?>
