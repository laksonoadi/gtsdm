<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_cuti_massal/business/cuti_massal.class.php';

class ViewHistoryDataCutiMassal extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/data_cuti_massal/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_history_data_cuti_massal.html');
	}
	
	function ProcessRequest() {
		$Obj = new CutiMassal();
		if($_POST || isset($_GET['cari'])) {
		}
      
		$totalData = $Obj->GetCountCutiMassal();
		$itemViewed = 15;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataHistory = $Obj->GetDataCutiMassal($startRec, $itemViewed);
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
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('data_cuti_massal', 'historyDataCutiMassal', 'view', 'html') . "&dataId=" . $this->encDataId);
		$this->mrTemplate->AddVar('content', 'URL_TAMBAH_CUTI_MASSAL', Dispatcher::Instance()->GetUrl('data_cuti_massal', 'dataCutiMassal', 'view', 'html'));
    if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}

    //tampilkan history cuti
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
				
        $dataHistory[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('data_cuti_massal', 'deleteDataCutiMassal', 'view', 'html') . '&dataId=' . $idEnc;
        $dataHistory[$i]['url_detail'] = Dispatcher::Instance()->GetUrl('data_cuti_massal', 'detailDataCutiMassal', 'view', 'html') . '&dataId=' . $idEnc;
        
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
