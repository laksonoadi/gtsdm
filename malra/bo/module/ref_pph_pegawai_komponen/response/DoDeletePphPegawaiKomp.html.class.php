<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/ref_pph_pegawai_komponen/response/ProcessPphPegawaiKomp.proc.class.php';

class DoDeletePphPegawaiKomp extends HtmlResponse {

	function TemplateModule() {}
	
	function ProcessRequest() {
		$pphObj = new ProcessPph();
		$urlRedirect = $pphObj->Delete();
		$this->RedirectTo($urlRedirect);
		return NULL;
	}

	function ParseTemplate($data = NULL) {}
}
?>
