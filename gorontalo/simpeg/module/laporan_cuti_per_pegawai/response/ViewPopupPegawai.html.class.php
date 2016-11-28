<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_cuti_per_pegawai/business/laporan.class.php';

class ViewPopupPegawai extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'].'module/laporan_cuti_per_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_popup_pegawai.html');
	}
   
    function TemplateBase() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') . 'main/template/');
      $this->SetTemplateFile('document-common-popup.html');
      $this->SetTemplateFile('layout-common-popup.html');
	}
	
	function ProcessRequest() {
		$Obj = new Laporan();
		if(isset($_GET['dataGroup'])) {
			$dataGroup = Dispatcher::Instance()->Decrypt($_GET['dataGroup']);
		}
	
		if($_POST || isset($_GET['cari'])) {
			if(isset($_POST['nama'])) {
				$nama = $_POST['nama'];
				$dataGroup = $_POST['dataGroup'];
				$dataPeg = $_POST['dataPeg'];
			} elseif(isset($_GET['nama'])) {
				$nama = Dispatcher::Instance()->Decrypt($_GET['nama']);
				$dataGroup = Dispatcher::Instance()->Decrypt($_GET['dataGroup']);
				$dataPeg = Dispatcher::Instance()->Decrypt($_GET['dataPeg']);
			} else {
				$nama = '';
			}
		}
		
		$return['dataGroup'] = $dataGroup;
		$return['dataPeg'] = $dataPeg;
		
		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType.
		'&nama='.Dispatcher::Instance()->Encrypt($nama).
		'&dataPeg='.Dispatcher::Instance()->Encrypt($dataPeg).
		'&dataGroup='.Dispatcher::Instance()->Encrypt($dataGroup).
		'&cari='.Dispatcher::Instance()->Encrypt(1));
		$dest = "popup-subcontent";
		
		$totalData = $Obj->GetCountData($nama,$dataGroup);
		$itemViewed = 15;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataPegawai = $Obj->getData($nama,$dataGroup,$startRec, $itemViewed);
		Messenger::Instance()->SendToComponent('paging2', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage, $dest), Messenger::CurrentRequest);
		
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
				
		$return['dataPegawai'] = $dataPegawai;
		$return['start'] = $startRec+1;
		$return['search']['nama'] = $nama;
		
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$search = $data['search'];
		$this->mrTemplate->AddVar('content', 'NAMA', $search['nama']);
		$this->mrTemplate->AddVar('content', 'PEG', $data['dataPeg']);
		$this->mrTemplate->AddVar('content', 'DATAGROUP', $data['dataGroup']);
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('user_portal', 'popupPegawai', 'view', 'html'));
		
		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
		
		$cariData = $data['dataPegawai'];
		
		//print_r($cariData);
	  
		if(empty($cariData)) {
			$this->mrTemplate->AddVar('data_pegawai', 'DATA_EMPTY', "YES");
		} else {
			$this->mrTemplate->AddVar('data_pegawai', 'DATA_EMPTY', "NO");
			for ($i=0; $i<sizeof($cariData); $i++) {
				$no = $i+$data['start'];
				$cariData[$i]['number'] = $no;
				if ($no % 2 != 0) $cariData[$i]['class_name'] = 'table-common-even';
				else $cariData[$i]['class_name'] = '';
				$cariData[$i]['data'] = $data['dataPeg'];
			    $this->mrTemplate->AddVars('data_pegawai_item', $cariData[$i], 'DATA_');
				$this->mrTemplate->parseTemplate('data_pegawai_item', 'a');	 
			}
		}
	}
}
?>
