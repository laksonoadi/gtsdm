<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/persetujuan_lembur/business/lembur.class.php';

class ViewAppDataLembur extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/persetujuan_lembur/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_app_data_lembur.html');
	}
	
	function ProcessRequest() {
		$Obj = new Lembur();
		if($_POST || isset($_GET['cari'])) {
  			if(isset($_POST['nip_nama'])) {
  				$nip_nama = $_POST['nip_nama'];
  			} elseif(isset($_GET['nip_nama'])) {
  				$nip_nama = Dispatcher::Instance()->Decrypt($_GET['nip_nama']);
  			} else {
  				$nip_nama = '';
  			}
  		}
	//view
    $datenow = date("Y-m-d");
		$totalData = $Obj->GetCountLembur($nip_nama);
		$itemViewed = 15;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataHistory = $Obj->GetDataLembur($nip_nama, $startRec, $itemViewed);

    $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . '&dataId=' . $this->encDataId . '&tampilkan=' . Dispatcher::Instance()->Encrypt($tampilkan) . '&cari=' . Dispatcher::Instance()->Encrypt(1));

		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);

		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];

		$return['dataHistory'] = $dataHistory;
		$return['search']['nip_nama'] = $nip_nama;
		$return['start'] = $startRec+1;
    return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('persetujuan_lembur', 'appDataLembur', 'view', 'html'));
		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
		$this->mrTemplate->AddVar('content', 'NIP_NAMA', $search['nip_nama']);
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

				$idEnc = Dispatcher::Instance()->Encrypt($dataHistory[$i]['id']);
				$idEnc2 = Dispatcher::Instance()->Encrypt($dataHistory[$i]['idPeg']);
				
        $dataHistory[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('persetujuan_lembur', 'deleteDataLembur', 'view', 'html') . '&dataId=' . $idEnc;
        $dataHistory[$i]['url_detail'] = Dispatcher::Instance()->GetUrl('persetujuan_lembur', 'inputAppLembur', 'view', 'html') . '&dataId=' . $idEnc2 . '&dataId2=' . $idEnc;
        
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
	   return $jam[(int)$hour].' hour '.$menit.' minutes';
	}
	
	function periode2string($date) {
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return $tanggal.'/'.$bulan.'/'.$tahun;
	}
}
?>
