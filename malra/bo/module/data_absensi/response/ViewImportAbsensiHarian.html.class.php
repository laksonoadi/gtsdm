<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_absensi/business/AppAbsensi.class.php';

class ViewImportAbsensiHarian extends HtmlResponse {
	
	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') . 'module/data_absensi/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('import_absensi_harian.html');
	}
	
	function ProcessRequest() {
		
	}

	function ParseTemplate($data = NULL) {
		$this->mrTemplate->AddVar('content','URL_ACTION',Dispatcher::Instance()->GetUrl('data_absensi','importAbsensiHarian','do','html'));
	}
}
?>
