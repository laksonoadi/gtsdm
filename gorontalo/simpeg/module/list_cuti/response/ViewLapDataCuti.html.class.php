<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/list_cuti/business/cuti.class.php';

class ViewLapDataCuti extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/list_cuti/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_lap_data_cuti.html');
	}
	
	function ProcessRequest() {
		$Obj = new Cuti();
		if($_POST || isset($_GET['cari'])) {
			if(isset($_POST['tampilkan'])) {
				$tampilkan = $_POST['tampilkan'];
				$tipe = $_POST['tipe'];
			} elseif(isset($_GET['tampilkan'])) {
				$tampilkan = Dispatcher::Instance()->Decrypt($_GET['tampilkan']);
				$tipe = Dispatcher::Instance()->Decrypt($_GET['tipe']);
			} else {
				$tampilkan = 'all';
				$tipe = 'all';
			}
		}
	//view
      
		$totalData = $Obj->GetCountCuti($tampilkan, $tipe);
		$itemViewed = 15;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataHistory = $Obj->GetDataCuti($startRec, $itemViewed, $tampilkan, $tipe);
    //print_r($dataHistory);
    $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . '&dataId=' . $this->encDataId . '&tampilkan=' . Dispatcher::Instance()->Encrypt($tampilkan) . '&cari=' . Dispatcher::Instance()->Encrypt(1));

		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
    
    //combo status cuti
    $tipeS[0]['id'] = "request";
    $tipeS[0]['name'] = "request";
    $tipeS[1]['id'] = "submit to approved";
    $tipeS[1]['name'] = "submit to approved";
    $tipeS[2]['id'] = "approved";
    $tipeS[2]['name'] = "approved";
    $tipeS[3]['id'] = "rejected";
    $tipeS[3]['name'] = "rejected";
    //print_r($tipe);
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tampilkan', 
    array('tampilkan',$tipeS,$tampilkan,'true',' style="width:130px;"'), Messenger::CurrentRequest);
    
    //combo tipe cuti
    $tipeCuti = $Obj->GetComboTipe();
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tipe', 
    array('tipe',$tipeCuti,$tipe,'true',' style="width:130px;"'), Messenger::CurrentRequest);

		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];

		$return['dataHistory'] = $dataHistory;
		$return['start'] = $startRec+1;
		$return['search1'] = $tipe;
		$return['search2'] = $tampilkan;
				
    return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$this->mrTemplate->AddVar('content', 'SEARCH1', $data['search1']);
    $this->mrTemplate->AddVar('content', 'SEARCH2', $data['search2']);
    $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('list_cuti', 'lapDataCuti', 'view', 'html'));
		$this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('list_cuti', 'cetakLapCuti', 'view', 'html') . '&dataId=' . Dispatcher::Instance()->Encrypt($data['search1']) . '&dataId2=' . Dispatcher::Instance()->Encrypt($data['search2']) . '&cetak=' . Dispatcher::Instance()->Encrypt('yes'));
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
				$idEnc2 = Dispatcher::Instance()->Encrypt($dataHistory[$i]['idpeg']);
				
        $dataHistory[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('list_cuti', 'deleteDataCuti', 'view', 'html') . '&dataId=' . $idEnc;
        $dataHistory[$i]['url_detail'] = Dispatcher::Instance()->GetUrl('list_cuti', 'lapCutiDetail', 'view', 'html') . '&dataId=' . $idEnc2 . '&dataId2=' . $idEnc;
        
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
