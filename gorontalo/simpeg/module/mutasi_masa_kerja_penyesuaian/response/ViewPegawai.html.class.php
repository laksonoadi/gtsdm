<?php
class ViewPegawai extends HtmlResponse {

    function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/'.Dispatcher::Instance()->mModule.'/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_pegawai.html');    
    } 
    
    function ProcessRequest() {
		$judul='MUTASI MASA KERJA PENYESUAIAN';
		$urlSearch=Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType);
		$urlSelect=Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule,'MutasiMasaKerjaPenyesuaian',Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType);
		Messenger::Instance()->SendToComponent('data_pegawai', 'listPegawaiVerified', 'view', 'html', 'list', array($judul,$urlSearch,$urlSelect), Messenger::CurrentRequest);
		return $data;
    }
    
    function ParseTemplate($data = NULL) {
    }
}
?>