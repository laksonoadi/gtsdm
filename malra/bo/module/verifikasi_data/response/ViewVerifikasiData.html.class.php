<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/verifikasi_data/business/verifikasi_data.class.php';

class ViewVerifikasiData extends HtmlResponse {

    function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/verifikasi_data/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_verifikasi_data.html');    
    } 
    
    function ProcessRequest() {
		$msg = Messenger::Instance()->Receive(__FILE__);
		$data['pesan'] = $msg[0][1];
		$data['css'] = $msg[0][2];
		$data['start'] = $startRec+1;
		
		$Obj = new VerifikasiData;
		$convert=array('15'=>15,'25'=>25,'50'=>50,'100'=>100,'250'=>250);
		$arrStatusData = $Obj->GetComboStatusData();
		$arrJenisData = $Obj->GetComboJenisData();
		
		$POSTDATA = $_POST->AsArray();
		if (isset($POSTDATA['op'])){
			$update=$Obj->DoUpdateStatus($POSTDATA['id_value'],$POSTDATA['referensi'],$POSTDATA['status_data']);
			if ($update===true){
				$data['pesan'] = 'Verifikasi Data Berhasil Dilakukan.';
				$data['css'] = 'notebox-done';
			}
		}
		
		if($_POST || isset($_GET['cari'])) {
  			if(isset($_POST['cari'])) {
				$this->POST=$_POST->AsArray();
  				$nip_nama = $_POST['nip_nama'];
				$status_data = $_POST['status_data'];
				$jenis_data = $_POST['jenis_data'];
				$itemViewed = $convert[$this->POST['item_viewed']];
  			} elseif(isset($_GET['cari'])) {
  				$nip_nama = Dispatcher::Instance()->Decrypt($_GET['nip_nama']->Raw());
				$status_data = Dispatcher::Instance()->Decrypt($_GET['status_data']->Raw());
				$jenis_data = Dispatcher::Instance()->Decrypt($_GET['jenis_data']->Raw());
				$itemViewed = Dispatcher::Instance()->Decrypt($_GET['item_viewed']->Raw());
  			} else {
  				$nip_nama = '';
				$status_data = $arrStatusData[0]['id'];  
				$jenis_data = $arrJenisData[0]['id'];
				$itemViewed = 15;  
  			}
  		}
		
		$msg = Messenger::Instance()->Receive(__FILE__,$this->mComponentName);
		$data['judul'] = $msg[0][0];
		$data['url_search'] = Dispatcher::Instance()->GetUrl('verifikasi_data', 'VerifikasiData', 'view', 'html');//$msg[0][1];
		$data['url_select'] = Dispatcher::Instance()->GetUrl('verifikasi_data', 'PopupVerifikasiData', 'view', 'html');;//$msg[0][2];
		
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status_data', array('status_data', $arrStatusData, $status_data, 'true', ''), Messenger::CurrentRequest);
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_data', array('jenis_data', $arrJenisData, $jenis_data, '', ''), Messenger::CurrentRequest);
		$arrItemViewed=array(
							array('id'=>15, 'name'=>'15 Data'),
							array('id'=>25, 'name'=>'25 Data'),
							array('id'=>50, 'name'=>'50 Data'),
							array('id'=>100, 'name'=>'100 Data'),
							array('id'=>250, 'name'=>'250 Data')
						);
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'item_viewed', array('item_viewed', $arrItemViewed, $itemViewed, '', ''), Messenger::CurrentRequest);
		
        //create paging 
		$totalData = $Obj->GetCountDataNotifikasi($nip_nama, $status_data, $jenis_data);
  		$currPage = 1;
  		$startRec = 0 ;
  		if(isset($_GET['page'])) {
  			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec =($currPage-1) * $itemViewed;
  		}
  		
  		$data['list'] = $Obj->GetDataNotifikasi($startRec, $itemViewed, $nip_nama, $status_data, $jenis_data);
		
  	
  		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType .
        '&nip_nama=' . Dispatcher::Instance()->Encrypt($nip_nama).
		'&status_data=' . Dispatcher::Instance()->Encrypt($status_data).
		'&jenis_data=' . Dispatcher::Instance()->Encrypt($jenis_data).
		'&item_viewed=' . Dispatcher::Instance()->Encrypt($itemViewed).
        '&cari=' . Dispatcher::Instance()->Encrypt(1));
		
		$data['extend-url']= ''.
		'&nip_nama=' . Dispatcher::Instance()->Encrypt($nip_nama).
		'&status_data=' . Dispatcher::Instance()->Encrypt($status_data).
		'&jenis_data=' . Dispatcher::Instance()->Encrypt($jenis_data).
		'&item_viewed=' . Dispatcher::Instance()->Encrypt($itemViewed).
        '&cari=' . Dispatcher::Instance()->Encrypt(1);
		
		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
		//create paging end here

		//set the language
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
		$labeldel=Dispatcher::Instance()->Encrypt($data['judul']);
		$data['arrStatusData']=$arrStatusData;
		$data['lang']=$lang;
		$data['keyword']=$nip_nama;
		return $data;
    }
    
    function ParseTemplate($data = NULL) {
		$this->mrTemplate->AddVar('content', 'TITLE', $data['judul']);


		$this->mrTemplate->AddVar('content', 'URL_SEARCH', $data['url_search'] );
      
		if($data['pesan']) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
		}
      
		if (empty($data['list'])) {
			$this->mrTemplate->AddVar('data_list_data', 'DATA_EMPTY', 'YES');
		} else {      
			$this->mrTemplate->AddVar('data_list_data', 'DATA_EMPTY', 'NO');
			$len = sizeof($data['list']);
			foreach ($data['list'] as $key => $value) {
				$no = $key + $data['start'];
          
				if($key == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
				if($key == ($len-1)) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);				
				$value['NUMBER'] = $no;
          
				$value['no'] = $no;
				if ($no % 2 != 0) {
					$value['class_name'] = 'table-common-even';
				} else {
					$value['class_name'] = '';
				}       
				$idEnc = Dispatcher::Instance()->Encrypt($value['id']);
				if (!file_exists(GTFWConfiguration::GetValue( 'application', 'file_save_path').$value['dokumen']) | empty($value['dokumen'])) { 
					$value['dokumen'] = '<img src="images/icon-warning-16x16.gif">';
				}else{
					$value['dokumen'] = '<a href='.GTFWConfiguration::GetValue( 'application', 'file_download_path').$value['dokumen'].' target="_Blank"><img src="images/button-simpan.gif"></a>';
				}
				$value['URL_UPDATE_STATUS'] = $data['url_select'].'&id='.Dispatcher::Instance()->Encrypt($value['id_value']).'&referensi='.Dispatcher::Instance()->Encrypt($value['jenis_data']).$data['extend-url'];
				$this->mrTemplate->AddVars('data_list_data_item', $value, 'DATA_');
				$this->mrTemplate->parseTemplate('data_list_data_item', 'a');
			}
		}
		
		foreach ($data['arrStatusData'] as $key => $value) {
			$this->mrTemplate->AddVars('data_list_petunjuk_status', $value, 'DATA_');
			$this->mrTemplate->parseTemplate('data_list_petunjuk_status', 'a');
		}
    }
}
?>
