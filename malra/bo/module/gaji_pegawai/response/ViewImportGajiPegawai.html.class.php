<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/business/AppGajiPegawai.class.php';

class ViewImportGajiPegawai extends HtmlResponse {
	
	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') . 'module/gaji_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('import_gaji_pegawai.html');
	}
	
	function ProcessRequest() {
		
	}

	function ParseTemplate($data = NULL) {
		$this->mrTemplate->AddVar('content','URL_ACTION',Dispatcher::Instance()->GetUrl('gaji_pegawai','importGajiPegawai','do','html'));
  }
}
?>
