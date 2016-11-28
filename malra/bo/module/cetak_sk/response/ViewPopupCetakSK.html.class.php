<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/cetak_sk/business/CetakSK.class.php';

class ViewPopupCetakSK extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'].'module/cetak_sk/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_popup_cetak_sk.html');
	}
   
    function TemplateBase() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') . 'main/template/');
      $this->SetTemplateFile('document-common-popup.html');
      $this->SetTemplateFile('layout-common-popup.html');
	}
	
	function ProcessRequest() {
		$Obj = new CetakSK();
		if(isset($_GET['jenis'])) {
			$jenis = Dispatcher::Instance()->Decrypt($_GET['jenis']);
			$pegId = Dispatcher::Instance()->Decrypt($_GET['pegId']);
			$dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']);
		}
	
		$detailData = $Obj->GetDataDetail($pegId,$dataId,$jenis);
		$return = $detailData[0];
		
		
		$tahun['start']=date("Y")-25; $tahun['end'] = date("Y")+5;
		Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tanggal_penetapan', array(date('Y-m-d'), $tahun['start'], $tahun['end'], '', '', 'tanggal_penetapan'), Messenger::CurrentRequest);
	  
		$return['jenis']=$jenis;
		$return['universitas']=GTFWConfiguration::GetValue('application', 'company_name');
		$return['url_rtf_sk'] = Dispatcher::Instance()->GetUrl('cetak_sk','CetakRtfSK','view','html').'&jenis='.$jenis;
		
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$this->mrTemplate->AddVars('content', $data, '');
	}
}
?>
