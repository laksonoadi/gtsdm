<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_benefit/business/benefit.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';

class ViewHistoryDataBenefit extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/data_benefit/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_history_data_benefit.html');
	}
	
	function ProcessRequest() {
		$Obj = new Benefit();
		$ObjDatPeg = new DataPegawai();
    $_GET['dataId'] = $ObjDatPeg->GetPegIdByUserName();
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
	//view
      
		$totalData = $Obj->GetCountBenefit($this->decDataId, $tampilkan);
		$itemViewed = 15;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataBalance = $Obj->GetBalanceBenefitLeft($this->decDataId);
		$dataHistory = $Obj->GetDataBenefit($startRec, $itemViewed, $this->decDataId, $tampilkan);
    //print_r($dataHistory);
    $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . '&dataId=' . $this->encDataId . '&tampilkan=' . Dispatcher::Instance()->Encrypt($tampilkan) . '&cari=' . Dispatcher::Instance()->Encrypt(1));

		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
    
    //combo tipe benefit
    $tipe[0]['id'] = "request";
    $tipe[0]['name'] = "request";
    $tipe[1]['id'] = "approved";
    $tipe[1]['name'] = "approved";
    $tipe[2]['id'] = "rejected";
    $tipe[2]['name'] = "rejected";
    //print_r($tipe);
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tampilkan', array('tampilkan',$tipe,$tampilkan,'true',' style="width:130px;"'), Messenger::CurrentRequest);

		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];

    $return['dataBalance'] = $dataBalance;
		$return['dataHistory'] = $dataHistory;
		$return['start'] = $startRec+1;
    $return['dataPegawai'] = $dataPegawai;
    $return['idPegawai'] = $this->encDataId;
    $return['is_supervisor']=$ObjDatPeg->IsSupervisor($this->decDataId);
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
	  if ($data['is_supervisor']===false) $this->mrTemplate->SetAttribute('approval', 'visibility', 'hidden');
	  $this->mrTemplate->AddVar('approval', 'URL_APPROVAL_BENEFIT', Dispatcher::Instance()->GetUrl('approval_benefit', 'historyDataBenefit', 'view', 'html') . "&dataId=" . $this->encDataId);
	  
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('data_benefit', 'historyDataBenefit', 'view', 'html') . "&dataId=" . $this->encDataId);
		$this->mrTemplate->AddVar('content', 'URL_BALANCE_BENEFIT', Dispatcher::Instance()->GetUrl('balance_benefit', 'balanceBenefit', 'view', 'html') . "&pegId=" . $this->encDataId);
		//$this->mrTemplate->AddVar('content', 'URL_TAMBAH_BENEFIT', Dispatcher::Instance()->GetUrl('data_benefit', 'dataBenefit', 'view', 'html') . "&dataId=" . $this->encDataId.'&op=add');
    $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('data_benefit', 'pegawai', 'view', 'html'));
		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
		
		//tampilkan data pegawai
		$dataPegawai = $data['dataPegawai'];
		$this->mrTemplate->AddVar('content', 'NIP', $dataPegawai['nip']);
    $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai['nama']);
    $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai['alamat']);
    if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai['foto']) | empty($dataPegawai['foto'])) { 
	 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
  	}else{
      $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai['foto']);
    }
    
    $dataBalance = $data['dataBalance'];
    if (empty($dataBalance)) {
			$dataBalance['data_balance'] = "--";
      $this->mrTemplate->AddVar('content', 'URL_TAMBAH_BENEFIT', '#');  
		} else {
		  $dataBalance['data_balance'] = number_format($dataBalance['data_balance'], 2, ',', '.');
      $this->mrTemplate->AddVar('content', 'URL_TAMBAH_BENEFIT', Dispatcher::Instance()->GetUrl('data_benefit', 'dataBenefit', 'view', 'html') . "&dataId=" . $this->encDataId.'&op=add');  
		}
		$this->mrTemplate->AddVar('data_balance', 'CLASS_BALANCE', $this->css);
		$this->mrTemplate->AddVar('data_balance', 'DATA_BALANCE', $dataBalance['data_balance']);
		
    //tampilkan history benefit
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
				
				$urlAccept = 'data_benefit|deleteDataBenefit|do|html-dataId-'.$idEnc;
        $urlKembali = 'data_benefit|historyDataBenefit|view|html';
        $dataName = $dataHistory[$i]['tipe_klaim'];
          
				$dataHistory[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
        $dataHistory[$i]['url_detail'] = Dispatcher::Instance()->GetUrl('data_benefit', 'detailDataBenefit', 'view', 'html') . '&dataId=' . $data['idPegawai'] . '&dataId2=' . $idEnc;
        $dataHistory[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('data_benefit', 'dataBenefit', 'view', 'html') . '&op=edit' . '&dataId=' . $data['idPegawai'] . '&dataId2=' . $idEnc;
				
				if ($dataHistory[$i]['status']=='request'){
          $this->mrTemplate->SetAttribute('edit_data', 'visibility', 'visible');
          $this->mrTemplate->AddVars('edit_data', $dataHistory[$i], 'DATA_');
        }
        
        $dataHistory[$i]['tgl_benefit'] = $this->periode2string($dataHistory[$i]['tgl_benefit']);
        $dataHistory[$i]['tgl_klaim'] = $this->periode2string($dataHistory[$i]['tgl_klaim']);
        $dataHistory[$i]['total_klaim'] = number_format($dataHistory[$i]['total_klaim'], 2, ',', '.');
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
