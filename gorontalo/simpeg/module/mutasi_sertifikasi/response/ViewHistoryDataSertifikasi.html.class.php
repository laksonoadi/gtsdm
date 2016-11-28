<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_sertifikasi/business/sertifikasi.class.php';

class ViewHistoryDataSertifikasi extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/mutasi_sertifikasi/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_history_sertifikasi.html');
	}
	
	function ProcessRequest() {
		$Obj = new Sertifikasi();
		
		if(isset($_GET['cari'])) {
			$this->srtfkPeriodeAwal=$_GET['srtfkPeriodeAwal'];
			$this->srtfkPeriodeAkhir=$_GET['srtfkPeriodeAkhir'];
			$this->srtfkTahun=$_GET['srtfkTahun'];
		} else if(isset($_POST['cari'])) {
			$this->srtfkPeriodeAwal=$_POST['srtfkPeriodeAwal'];
			$this->srtfkPeriodeAkhir=$_POST['srtfkPeriodeAkhir'];
			$this->srtfkTahun=$_POST['srtfkTahun'];
		} else {
			$this->srtfkPeriodeAwal=date("Y-")."01-01";
			$this->srtfkPeriodeAkhir=date("Y-")."12-31";
			$this->srtfkTahun=date("Y");
		}
        
		$y1=date('Y')+4;
		Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'srtfkPeriodeAwal', array($this->srtfkPeriodeAwal,'2003',$y1,'',''), Messenger::CurrentRequest);
		Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'srtfkPeriodeAkhir', array($this->srtfkPeriodeAkhir,'2003',$y1,'',''), Messenger::CurrentRequest);
      
		$totalData = $Obj->GetCountUsulanSertifikasi();
		$itemViewed = 15;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataHistory = $Obj->GetUsulanSertifikasi($startRec, $itemViewed);
		//print_r($dataHistory);
		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . '&cari=' . Dispatcher::Instance()->Encrypt(1));

		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);

		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];

		$return['dataHistory'] = $dataHistory;
		$return['start'] = $startRec+1;

		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('mutasi_sertifikasi', 'historyDataSertifikasi', 'view', 'html') . "&dataId=" . $this->encDataId);
		$this->mrTemplate->AddVar('content', 'URL_TAMBAH_SERTIFIKASI', Dispatcher::Instance()->GetUrl('mutasi_sertifikasi', 'dataSertifikasi', 'view', 'html'));
		$this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('mutasi_sertifikasi', 'detailSertifikasi', 'view', 'xls').'&srtfkId=ALL&srtfkdetHasilAkhir=ALL');
		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}

		//tampilkan history
		if (empty($data['dataHistory'])) {
			$this->mrTemplate->AddVar('data_history', 'HISTORY_EMPTY', 'YES');
		} else {
			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
			$encPage = Dispatcher::Instance()->Encrypt($decPage);
			$this->mrTemplate->AddVar('data_history', 'HISTORY_EMPTY', 'NO');
			$dataHistory = $data['dataHistory'];
			
			for ($i=0; $i<sizeof($dataHistory); $i++) {
				$no = $i+$data['start'];
				$dataHistory[$i]['number'] = $no;
				if ($no % 2 != 0) {
					$dataHistory[$i]['class_name'] = 'table-common-even';
				}else{
					$dataHistory[$i]['class_name'] = '';
				}
				
				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
				if($i == sizeof($dataHistory)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);

				$idEnc = Dispatcher::Instance()->Encrypt($dataHistory[$i]['id']);
				
				$dataHistory[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('mutasi_sertifikasi', 'deleteDataSertifikasi', 'view', 'html') . '&srtfkId=' . $idEnc;
				$dataHistory[$i]['url_detail'] = Dispatcher::Instance()->GetUrl('mutasi_sertifikasi', 'detailSertifikasi', 'view', 'html') . '&srtfkId=' . $idEnc;
				$dataHistory[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('mutasi_sertifikasi', 'dataSertifikasi', 'view', 'html') . '&srtfkId=' . $idEnc;
				$dataHistory[$i]['url_verifikasi'] = Dispatcher::Instance()->GetUrl('mutasi_sertifikasi', 'verifikasiSertifikasi', 'view', 'html') . '&srtfkId=' . $idEnc;
				$dataHistory[$i]['url_penilaian'] = Dispatcher::Instance()->GetUrl('mutasi_sertifikasi', 'penilaianSertifikasi', 'view', 'html') . '&srtfkId=' . $idEnc;
        
				$dataHistory[$i]['mulai'] = $this->periode2string($dataHistory[$i]['mulai']);
				$dataHistory[$i]['selesai'] = $this->periode2string($dataHistory[$i]['selesai']);
        
				$this->mrTemplate->AddVars('data_history_item', $dataHistory[$i], 'DATA_');
				$this->mrTemplate->parseTemplate('data_history_item', 'a');	 
			}   
		}
	}
	
	function periode2string($date) {
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return $tanggal.'/'.$bulan.'/'.$tahun;
	}
}
?>
