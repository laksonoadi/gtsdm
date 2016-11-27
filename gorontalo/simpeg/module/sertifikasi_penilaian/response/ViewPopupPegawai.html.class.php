<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_sertifikasi/business/popup_pegawai.class.php';

class ViewPopupPegawai extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'].'module/sertifikasi_penilaian/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_popup_pegawai.html');
	}
   
    function TemplateBase() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') . 'main/template/');
		$this->SetTemplateFile('document-common-popup.html');
		$this->SetTemplateFile('layout-common-popup.html');
	}
	
	function ProcessRequest() {
		$Obj = new PopupPegawai();
		if(isset($_GET['dataPeg'])) {
			$dataPeg = Dispatcher::Instance()->Decrypt($_GET['dataPeg']);
		}
	
	
		if($_POST || isset($_GET['cari'])) {
			if(isset($_POST['nama'])) {
				$nama = $_POST['nama'];
				$dataPeg = $_POST['dataPeg'];
			} elseif(isset($_GET['nama'])) {
				$nama = Dispatcher::Instance()->Decrypt($_GET['nama']);
				$dataPeg = Dispatcher::Instance()->Decrypt($_GET['dataPeg']);
			} else {
				$nama = '';
			}
		}
		
		$return['dataPeg'] = $dataPeg;
		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType.
		'&nama='.Dispatcher::Instance()->Encrypt($nama).
		'&dataPeg='.Dispatcher::Instance()->Encrypt($dataPeg).
		'&cari='.Dispatcher::Instance()->Encrypt(1));
		$dest = "popup-subcontent";
		
		
		$totalData = $Obj->GetCountData($nama);
		$itemViewed = 15;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataPegawai = $Obj->getData($startRec, $itemViewed,$nama);
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
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('mutasi_sertifikasi', 'popupPegawai', 'view', 'html'));
		
		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
		
		$cariData = $data['dataPegawai'];
	  
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
				
				$filter = "'".$cariData[$i]['srtfkdetId']."','".$cariData[$i]['srtfkdetPegId']."','".$cariData[$i]['srtfkdetNip']."'";
				$filter .= ",'".$cariData[$i]['srtfkdetNama']."','".$cariData[$i]['srtfkdetTempatLahir']."','".$cariData[$i]['srtfkdetTanggalLahir']."'";
				$filter .= ",'".$cariData[$i]['srtfkdetJabfungrId']."','".$cariData[$i]['srtfkdetJabfungrNama']."','".$cariData[$i]['srtfkdetPktgolrId']."'";
				$filter .= ",'".$cariData[$i]['srtfkdetPktgolrNama']."','".$cariData[$i]['srtfkdetBidangKode']."','".$cariData[$i]['srtfkdetBidangNama']."'";
				$filter .= ",'".$cariData[$i]['srtfkdetS1']."','".$cariData[$i]['srtfkdetS2']."','".$cariData[$i]['srtfkdetS3']."'";
				
				$cariData[$i]['set_parent'] ="<a href=\"javascript:void(0)\" onclick=\"addPegawaiItem(this, ".$filter.")\" onmouseover=\"status='Set preferences...';return true\" ><img src=\"images/button-check.gif\" alt=\"Pilih\"/></a>";
			    $this->mrTemplate->AddVars('data_pegawai_item', $cariData[$i], 'DATA_');
				$this->mrTemplate->parseTemplate('data_pegawai_item', 'a');	 
			}
		}
	}
}
?>
