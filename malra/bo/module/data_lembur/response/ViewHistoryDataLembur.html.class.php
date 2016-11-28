<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_lembur/business/lembur.class.php';

class ViewHistoryDataLembur extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/data_lembur/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_history_data_lembur.html');
	}
	
	function ProcessRequest() {
		$Obj = new Lembur();
		if($_POST || isset($_GET['cari'])) {
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
    
		$dataPegawai = $Obj->GetDataById($this->decDataId);
		$dataPegawai['detail'] = $dataPegawai[0];
		$dataPegawai['spv'] = $dataPegawai[1];
		$dataPegawai['mor'] = $dataPegawai[2];
		
		//view
		$totalData = $Obj->GetCountLembur($this->decDataId, $tampilkan);
		$itemViewed = 15;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
				$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
				$startRec =($currPage-1) * $itemViewed;
		}
		$dataHistory = $Obj->GetDataLembur($startRec, $itemViewed, $this->decDataId, $tampilkan);
		//print_r($dataHistory);
		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . '&dataId=' . $this->encDataId . '&tampilkan=' . Dispatcher::Instance()->Encrypt($tampilkan) . '&cari=' . Dispatcher::Instance()->Encrypt(1));

		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
    
		//combo tipe lembur
		$tipe[0]['id'] = "request";
		$tipe[0]['name'] = "Dalam Proses";
		$tipe[1]['id'] = "approved";
		$tipe[1]['name'] = "Disetujui";
		$tipe[2]['id'] = "rejected";
		$tipe[2]['name'] = "Ditolak";
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tampilkan', 
		array('tampilkan',$tipe,$tampilkan,'true',' style="width:130px;"'), Messenger::CurrentRequest);

		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];

		$return['dataHistory'] = $dataHistory;
		$return['start'] = $startRec+1;
		$return['dataPegawai'] = $dataPegawai['detail'];
		$return['dataPegawaiSpv'] = $dataPegawai['spv'];
		$return['dataPegawaiMor'] = $dataPegawai['mor'];
		$return['idPegawai'] = $this->encDataId;
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('data_lembur', 'historyDataLembur', 'view', 'html') . "&dataId=" . $this->encDataId);
		$this->mrTemplate->AddVar('content', 'URL_TAMBAH_LEMBUR', Dispatcher::Instance()->GetUrl('data_lembur', 'dataLembur', 'view', 'html') . "&dataId=" . $this->encDataId);
		$this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('data_lembur', 'pegawai', 'view', 'html'));
		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
		
		//tampilkan data pegawai
		$dataPegawai = $data['dataPegawai'];
		$dataPegawaiSpv = $data['dataPegawaiSpv'];
		$dataPegawaiMor = $data['dataPegawaiMor'];
		
		$this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['nip']);
		$this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['nama']);
		$this->mrTemplate->AddVar('content', 'SPV', $dataPegawaiSpv[0]['spv']);
		$this->mrTemplate->AddVar('content', 'MOR', $dataPegawaiMor[0]['mor']);
		$this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
		if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai[0]['foto']) | empty($dataPegawai[0]['foto'])) { 
			$this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
		}else{
			$this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai[0]['foto']);
		}

		//tampilkan history lembur
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

				$idEnc = Dispatcher::Instance()->Encrypt($this->encDataId);
				$idEnc2 = Dispatcher::Instance()->Encrypt($dataHistory[$i]['id']);
				
				$urlAccept = 'data_lembur|deleteDataLembur|do|html-dataId-'.$idEnc;
				$urlKembali = 'data_lembur|historyDataLembur|view|html-dataId-'.$idEnc;
				$dataName = $dataHistory[$i]['no'];
				$label = 'Lembur';
				$url_delete = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc2.'&label='.$label.'&dataName='.$dataName;
				
				$url_approval=Dispatcher::Instance()->GetUrl('data_lembur', 'dataLembur', 'view', 'html') . '&dataId=' . $data['idPegawai'] . '&dataId2=' . $idEnc2;
				$dataHistory[$i]['url_detail'] = Dispatcher::Instance()->GetUrl('data_lembur', 'detailDataLembur', 'view', 'html') . '&dataId=' . $data['idPegawai'] . '&dataId2=' . $idEnc2;
        
				#$dataHistory[$i]['mulai'] = $this->periode2string($dataHistory[$i]['mulai']);
				#$dataHistory[$i]['selesai'] = $this->periode2string($dataHistory[$i]['selesai']);
				if ($dataHistory[$i]['status']=='approved') { $dataHistory[$i]['gambar']='lamp-green.gif'; }else
				if ($dataHistory[$i]['status']=='rejected') { $dataHistory[$i]['gambar']='lamp-red.gif'; }else
				if ($dataHistory[$i]['status']=='request') { 
					$dataHistory[$i]['gambar']='lamp-off.gif'; 
					
					$dataHistory[$i]['url_approval']='<a class="xhr dest_subcontent-element" href="'.$url_approval.'" title="Persetujuan"><img src="images/button-check.gif" alt="Persetujuan"/></a> ';
					$dataHistory[$i]['url_delete']='<a class="xhr dest_subcontent-element" href="'.$url_delete.'" title="Hapus"><img src="images/button-delete.gif" alt="Hapus"/></a> ';
				}
				
				$dataHistory[$i]['tglaju'] = $this->periode2string($dataHistory[$i]['tglaju']);
				$dataHistory[$i]['durasi'] = $this->time2string($dataHistory[$i]['durasi']);
				$this->mrTemplate->AddVars('data_history_item', $dataHistory[$i], 'DATA_');
				
				$this->mrTemplate->parseTemplate('data_history_item', 'a');	 
			}   
		}
	}
	
	
	function time2string($time) {
	   $hour = array(
					'00'  => '0',
					'01'  => '1',
					'02'  => '2',
					'03'  => '3',
					'04'  => '4',
					'05'  => '5',
					'06'  => '6',
					'07'  => '7',
					'08'  => '8',
					'09'  => '9',
					'10' => '10',
					'11'  => '11',
					'12'  => '12',
					'13'  => '13',
					'14'  => '14',
					'15'  => '15',
					'16'  => '16',
					'17'  => '17',
					'18'  => '18',
					'19'  => '19',
					'20' => '20',
					'21'  => '21',
					'22'  => '22',
					'23'  => '23'					
	               );
		$jam = substr($time,0,2);
		$menit = substr($time,-2);
		return $jam[(int)$hour].' Jam '.$menit.' Menit';
	}
	
	function periode2string($date) {
	   $bln = array(
					1  => 'Januari',
					2  => 'Feb',
					3  => 'Mar',
					4  => 'Apr',
					5  => 'Mei',
					6  => 'Jun',
					7  => 'Jul',
					8  => 'Agu',
					9  => 'Sep',
					10 => 'Okt',
					11 => 'Nov',
					12 => 'Des'					
	               );
		$tanggal = substr($date,8,2);
		$bulan = substr($date,5,2);
		$tahun = substr($date,0,4);
		return (int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
	}
}
?>
