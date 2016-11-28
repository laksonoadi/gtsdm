<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_gaji/business/AppGaji.class.php';

class ViewGaji extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/komponen_gaji/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_gaji.html');
	}
	
	function ProcessRequest() {
		$gajiObj = new AppGaji();
		if($_POST || isset($_GET['cari'])) {
			if(isset($_POST['nama'])) {
				$nama = $_POST['nama'];
				$jenis = $_POST['jenis'];
			} elseif(isset($_GET['nama'])) {
				$nama = Dispatcher::Instance()->Decrypt($_GET['nama']);
				$jenis = Dispatcher::Instance()->Decrypt($_GET['jenis']);
			} else {
				$nama = '';
				$jenis = '';
			}
		}
		
		//view
		$totalData = $gajiObj->GetCountData($nama, $jenis);
		$itemViewed = 20;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataGaji = $gajiObj->getData($startRec, $itemViewed, $nama, $jenis);
		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . '&nama=' . Dispatcher::Instance()->Encrypt($nama) . '&jenis=' . Dispatcher::Instance()->Encrypt($jenis) . '&cari=' . Dispatcher::Instance()->Encrypt(1));
      
		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);

		//startof kombo jenis
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
		$arrJenis[0]['id'] = "tambah";
		$arrJenis[1]['id'] = "kurang";
		if ($lang=='eng'){
			$arrJenis[0]['name'] = "Salary Increment Factor";
			$arrJenis[1]['name'] = "Salary Decrement Factor";
		}else{
			$arrJenis[0]['name'] = "Faktor Penambah Gaji";  
			$arrJenis[1]['name'] = "Faktor Pengurang Gaji";
		}
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis', array('jenis', $arrJenis, $jenis, 'true', ' style="width:150px;" '), Messenger::CurrentRequest);
		//endof kombo jenis

		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];

		$return['dataGaji'] = $dataGaji;
		$return['start'] = $startRec+1;

		$return['search']['nama'] = $nama;
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$search = $data['search'];
		$this->mrTemplate->AddVar('content', 'NAMA', $search['nama']);
		$this->mrTemplate->AddVar('content', 'URL_EXPORT', Dispatcher::Instance()->GetUrl('komponen_gaji', 'excelGaji', 'view', 'xls'));
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('komponen_gaji', 'gaji', 'view', 'html'));
		$this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('komponen_gaji', 'inputGaji', 'view', 'html'));
		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}

		if (empty($data['dataGaji'])) {
			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
		} else {
			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
			$encPage = Dispatcher::Instance()->Encrypt($decPage);
			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
			$dataGaji = $data['dataGaji'];
		
			//mulai bikin tombol delete
			$lang=GTFWConfiguration::GetValue('application', 'button_lang');
			if ($lang=='eng'){
				$label="Salary Component";
			}else{
				$label="Komponen Gaji";  
			}
			$urlDelete = Dispatcher::Instance()->GetUrl('komponen_gaji', 'deleteGaji', 'do', 'html');
			$urlReturn = Dispatcher::Instance()->GetUrl('komponen_gaji', 'gaji', 'view', 'html');
			Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
			$this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
			//selesai bikin tombol delete

			for ($i=0; $i<sizeof($dataGaji); $i++) {
				$no = $i+$data['start'];
				$dataGaji[$i]['number'] = $no;
				if ($no % 2 != 0) $dataGaji[$i]['class_name'] = 'table-common-even';
				else $dataGaji[$i]['class_name'] = '';
				
				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);				
				if($i == sizeof($dataGaji)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
				$idEnc = Dispatcher::Instance()->Encrypt($dataGaji[$i]['id']);
				$dataGaji[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('komponen_gaji', 'inputGaji', 'view', 'html') . '&dataId=' . $idEnc . '&page=' . $encPage . '&cari='.$cari;
				$dataGaji[$i]['url_detil'] = Dispatcher::Instance()->GetUrl('komponen_gaji', 'detilGaji', 'view', 'html') . '&dataId=' . $idEnc;
				
				if ($dataGaji[$i]['id']==1){
					//$this->mrTemplate->AddVar('data_item', 'DATA_1', 'YES');
				}elseif($dataGaji[$i]['otomatis']==1){
					$this->mrTemplate->AddVar('data_item', 'DATA_1', 'YESNO');
				}else{
					$this->mrTemplate->AddVar('data_item', 'DATA_1', 'NO');
				}
            
				$this->mrTemplate->AddVars('data_item', $dataGaji[$i], 'DATA_');
				if($dataGaji[$i]['jenis'] == 'tambah'){
					$dataGaji[$i]['jenis'] = 'Penambah';
					$this->mrTemplate->AddVar('data_item', 'DATA_JENIS', $dataGaji[$i]['jenis']);
				} else {
					$dataGaji[$i]['jenis'] = 'Pengurang';
					$this->mrTemplate->AddVar('data_item', 'DATA_JENIS', $dataGaji[$i]['jenis']);
				}
				$this->mrTemplate->parseTemplate('data_item', 'a');
            		 
			}
		}
	}
}
?>
