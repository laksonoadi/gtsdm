<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/verifikasi_data/business/verifikasi_data.class.php';

class ViewPopupVerifikasiData extends HtmlResponse {

    function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/verifikasi_data/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_popup_verifikasi_data.html');    
    } 
    
    function ProcessRequest() {
		$Obj = new VerifikasiData;
		$this->GET=$_GET->AsArray();
		$id = Dispatcher::Instance()->Decrypt($_GET['id']);
		$referensi = Dispatcher::Instance()->Decrypt($_GET['referensi']);
		
		$arrStatusData = $Obj->GetComboStatusData();
		$data['dataDetail'] = $Obj->GetDataNotifikasiById($id,$referensi);
		
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status_data', array('status_data', $arrStatusData, $data['dataDetail'][0]['status_data'], '', ''), Messenger::CurrentRequest);
		
		if(isset($_GET['cari'])) {
  			$nip_nama = Dispatcher::Instance()->Decrypt($_GET['nip_nama']->Raw());
			$status_data = Dispatcher::Instance()->Decrypt($_GET['status_data']->Raw());
			$jenis_data = Dispatcher::Instance()->Decrypt($_GET['jenis_data']->Raw());
			$itemViewed = Dispatcher::Instance()->Decrypt($_GET['item_viewed']->Raw());
  		}
		
		$data['extend-url']= ''.
		'&nip_nama=' . Dispatcher::Instance()->Encrypt($nip_nama).
		'&status_data=' . Dispatcher::Instance()->Encrypt($status_data).
		'&jenis_data=' . Dispatcher::Instance()->Encrypt($jenis_data).
		'&item_viewed=' . Dispatcher::Instance()->Encrypt($itemViewed).
        '&cari=' . Dispatcher::Instance()->Encrypt(1);
		
		return $data;
    }
    
    function ParseTemplate($data = NULL) {
		$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('verifikasi_data', 'VerifikasiData', 'view', 'html').$data['extend-url']);
		
		$value=$data['dataDetail'][0];
		if (!file_exists(GTFWConfiguration::GetValue( 'application', 'file_save_path').$value['dokumen']) | empty($value['dokumen'])) { 
			$value['dokumen'] = 'Tidak Ada Dokumen';
		}else{
			$value['dokumen'] = '<a href='.GTFWConfiguration::GetValue( 'application', 'file_download_path').$value['dokumen'].' target="_Blank">Download Dokumen</a>';
		}
		$this->mrTemplate->AddVars('content', $value, 'DATA_');
    }
}
?>
