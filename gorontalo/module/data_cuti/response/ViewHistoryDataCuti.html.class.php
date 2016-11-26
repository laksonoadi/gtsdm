<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_cuti/business/cuti.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/periode_cuti_pegawai/business/periode_cuti.class.php';

class ViewHistoryDataCuti extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/data_cuti/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_history_data_cuti.html');
	}
	
	function ProcessRequest() {
		$ObjDatPeg = new DataPegawai();
		$_GET['dataId'] = $ObjDatPeg->GetPegIdByUserName();
      
		$Obj = new Cuti();
		$ObjPeriode = new PeriodeCuti();
		
		if($_POST || isset($_GET['cari'])) {
			if(isset($_POST['year'])) {
				$year = $_POST['year'];
			} elseif(isset($_GET['year'])) {
				$year = Dispatcher::Instance()->Decrypt($_GET['year']);
			} else {
				$year = date('Y');
			}
			
			if(isset($_POST['nomor'])) {
				$nomor = $_POST['nomor'];
			} elseif(isset($_GET['nomor'])) {
				$nomor = Dispatcher::Instance()->Decrypt($_GET['nomor']);
			} else {
				$nomor = '';
			}
			
			if(isset($_POST['tampilkan'])) {
				$tampilkan = $_POST['tampilkan'];
			} elseif(isset($_GET['tampilkan'])) {
				$tampilkan = Dispatcher::Instance()->Decrypt($_GET['tampilkan']);
			} else {
				$tampilkan = 'all';
			}
		}
    
		$this->decDataId = Dispatcher::Instance()->Decrypt($_GET['dataId']);
		$this->encDataId = Dispatcher::Instance()->Encrypt($this->decDataId);
		$now = date('Y-m-d');
		
		$dataPegawai = $Obj->GetDataById($this->decDataId);
		$dataPeriode = $ObjPeriode->GetDataPeriodeCutiIdPegawai($this->decDataId, $now, $now);
		$this->periodeCuti = $Obj->GetPeriodeCutiByPegId($this->decDataId);
      
		$totalData = $Obj->GetCountCuti($this->decDataId, $tampilkan,$year,$nomor);
		$itemViewed = $totalData;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataHistory = $Obj->GetDataCuti($startRec, $itemViewed, $this->decDataId, $tampilkan,$year,$nomor);
		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . '&dataId=' . $this->encDataId . '&tampilkan=' . Dispatcher::Instance()->Encrypt($tampilkan) . '&cari=' . Dispatcher::Instance()->Encrypt(1));
		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
    
		//combo tipe cuti
		$tipe[0]['id'] = "request";
		$tipe[0]['name'] = "Dalam Proses";
		$tipe[1]['id'] = "approved";
		$tipe[1]['name'] = "Disetujui";
		$tipe[2]['id'] = "rejected";
		$tipe[2]['name'] = "Ditolak";
		//print_r($tipe);
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tampilkan', array('tampilkan',$tipe,$tampilkan,'true',' style="width:130px;"'), Messenger::CurrentRequest);
		
		//Combo tahun cuti
		$arrYear=$Obj->GetComboTahunCuti($this->decDataId);
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'year', array('year',$arrYear,$year,'',' style="width:130px;"'), Messenger::CurrentRequest);

		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];

		$return['dataHistory'] = $dataHistory;
		$return['dataPeriode'] = $dataPeriode;
		$return['start'] = $startRec+1;
		$return['dataPegawai'] = $dataPegawai;
		$return['idPegawai'] = $this->encDataId;
		return $return;
	}
	
	function ParseTemplate($data = NULL) { 
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('data_cuti', 'historyDataCuti', 'view', 'html') . "&dataId=" . $this->encDataId);
		$this->mrTemplate->AddVar('content', 'URL_PERIODE_CUTI', Dispatcher::Instance()->GetUrl('periode_cuti', 'periodeCuti', 'view', 'html') . "&pegId=" . $this->encDataId);
		$this->mrTemplate->AddVar('content', 'URL_TAMBAH_CUTI', Dispatcher::Instance()->GetUrl('data_cuti', 'dataCuti', 'view', 'html') . "&dataId=" . $this->encDataId.'&op=add');
		$this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('data_cuti', 'pegawai', 'view', 'html'));
		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}

		//tampilkan history cuti
		$jumlah=0;
		$jumlahApproved=0;
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
				
				//$dataHistory[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('data_cuti', 'deleteDataCuti', 'view', 'html') . '&dataId=' . $idEnc;
				$dataHistory[$i]['url_detail'] = Dispatcher::Instance()->GetUrl('data_cuti', 'detailDataCuti', 'view', 'html') . '&dataId=' . $data['idPegawai'] . '&dataId2=' . $idEnc;
				$dataHistory[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('data_cuti', 'dataCuti', 'view', 'html') . '&op=edit' . '&dataId=' . $data['idPegawai'] . '&dataId2=' . $idEnc;
				$dataHistory[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('data_cuti', 'dataCuti', 'view', 'html') . '&stat=cancel&op=edit' . '&dataId=' . $data['idPegawai'] . '&dataId2=' . $idEnc;
				
				$idEnc = Dispatcher::Instance()->Encrypt($dataHistory[$i]['id']);
				$urlAccept = 'data_cuti|deleteDataCuti|do|html';
				$urlKembali = 'data_cuti|historyDataCuti|view|html';
				$label = 'Data Cuti';
				$dataName = 'Cuti dengan nomor '.$dataHistory[$i]['no'];
				
				$cekLewat=$this->dateDiff("-",$dataHistory[$i]['mulai'],date('Y-m-d'));
				if(($dataHistory[$i]['status']=='request')&&($cekLewat>0)){
					$this->mrTemplate->AddVar('hidden_aksi', 'AKSI_HIDDEN', 'NO');
					$this->mrTemplate->AddVar('hidden_aksi', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName);
					$this->mrTemplate->AddVar('hidden_aksi', 'URL_EDIT', Dispatcher::Instance()->GetUrl('data_cuti', 'dataCuti', 'view', 'html') . '&op=edit' . '&dataId=' . $data['idPegawai'] . '&dataId2=' . $idEnc);
				}elseif(($cekLewat<=0)){
					$this->mrTemplate->AddVar('hidden_aksi', 'AKSI_HIDDEN', 'YES');
					$this->mrTemplate->AddVar('hidden_aksi', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName);
					$this->mrTemplate->AddVar('hidden_aksi', 'URL_EDIT', Dispatcher::Instance()->GetUrl('data_cuti', 'dataCuti', 'view', 'html') . '&op=edit' . '&dataId=' . $data['idPegawai'] . '&dataId2=' . $idEnc);
				}else{
					$this->mrTemplate->AddVar('hidden_aksi', 'AKSI_HIDDEN', 'YES');
					$this->mrTemplate->AddVar('hidden_aksi', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName);
					$this->mrTemplate->AddVar('hidden_aksi', 'URL_EDIT', Dispatcher::Instance()->GetUrl('data_cuti', 'dataCuti', 'view', 'html') . '&op=edit' . '&dataId=' . $data['idPegawai'] . '&dataId2=' . $idEnc);
				}
				
				$this->mrTemplate->AddVar('data_history_item', 'URL_DETAIL', Dispatcher::Instance()->GetUrl('data_cuti', 'detailDataCuti', 'view', 'html') . '&dataId=' . $data['idPegawai'] . '&dataId2=' . $idEnc);
				$dataHistory[$i]['mulai'] = $this->periode2string($dataHistory[$i]['mulai']);
				$dataHistory[$i]['selesai'] = $this->periode2string($dataHistory[$i]['selesai']);
				
				$status['request']='<img src="images/lamp-off.gif" alt="Dalam Proses"/>';
				$status['approved']='<img src="images/lamp-green.gif" alt="Disetujui"/>';
				$status['rejected']='<img src="images/lamp-red.gif" alt="Ditolak"/>';
				
				$dataHistory[$i]['status']=$status[$dataHistory[$i]['status']];
				$dataHistory[$i]['durasi']=$dataHistory[$i]['durasi'].' hari';
				
				$this->mrTemplate->AddVars('edit_data', $dataHistory[$i], 'DATA_');
				$this->mrTemplate->AddVars('data_history_item', $dataHistory[$i], 'DATA_');
				$this->mrTemplate->parseTemplate('data_history_item', 'a');	 
				$jumlah=$jumlah+$dataHistory[$i]['durasi'];
				
			}  
		}
		if (is_array($this->periodeCuti[0])){
			$this->mrTemplate->AddVars('balance_cuti', $this->periodeCuti[0], 'BALANCE_');
		}
	}
	
	function dateDiff($dformat, $endDate, $beginDate)	{
		$date_parts1=explode($dformat, $beginDate);
		$date_parts2=explode($dformat, $endDate);
		$start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
		$end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
		return $end_date - $start_date + 1;
	}
	
	function periode2string($date) {
		$tanggal = substr($date,8,2);
		$bulan = substr($date,5,2);
		$tahun = substr($date,0,4);
		return $tanggal.'/'.$bulan.'/'.$tahun;
	}
}
?>
