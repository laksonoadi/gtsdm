<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_taf/business/taf.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';

class ViewHistoryDataTaf extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/approval_taf/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_history_data_taf.html');
	}
	
	function ProcessRequest() {
		$Obj = new Taf();
		$ObjDatPeg = new DataPegawai();
    $_GET['dataId'] = $ObjDatPeg->GetPegIdByUserName();
		if($_POST || isset($_GET['cari'])) {
			if(isset($_POST['pegawai'])) {
				$pilihpegawai = $_POST['pegawai'];
			} elseif(isset($_GET['pegawai'])) {
				$pilihpegawai = Dispatcher::Instance()->Decrypt($_GET['pegawai']);
			} elseif(isset($_GET['pilihpegawai'])) {
				$pilihpegawai = Dispatcher::Instance()->Decrypt($_GET['pilihpegawai']);
			} else {
				$pilihpegawai = 0;
			}
			
			$tampilkan='all';
		}
		
		$this->decDataId = Dispatcher::Instance()->Decrypt($_GET['dataId']);
		$this->encDataId = Dispatcher::Instance()->Encrypt($this->decDataId);
    
    $dataPegawai = $Obj->GetDataById($pilihpegawai);
	  //view
      
		$totalData = $Obj->GetCountTaf($pilihpegawai, $tampilkan);
		$itemViewed = 15;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}

		$dataHistory = $Obj->GetDataTaf($startRec, $itemViewed, $pilihpegawai, $tampilkan);
    
    //print_r($dataHistory);
    $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . '&dataId=' . $this->encDataId . '&tampilkan=' . Dispatcher::Instance()->Encrypt($tampilkan) . '&cari=' . Dispatcher::Instance()->Encrypt(1));

		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
		
		$pegawai=$ObjDatPeg->GetComboPegawaiBawahan($_GET['dataId']);
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegawai', array('pegawai',$pegawai,$pilihpegawai,'false',''), Messenger::CurrentRequest);
    
    //combo tipe taf
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

		$return['dataHistory'] = $dataHistory;
		$return['start'] = $startRec+1;
    $return['dataPegawai'] = $dataPegawai;
    $return['idPegawai'] = $this->encDataId;
    $return['pilihpegawai'] = $pilihpegawai;
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
	  $pilihpegawai=$data['pilihpegawai'];
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('approval_taf', 'historyDataTaf', 'view', 'html') . "&dataId=" . $this->encDataId);
    $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('approval_taf', 'pegawai', 'view', 'html'));
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
    
		$dataBalance['data_balance'] = number_format($dataBalance['data_balance'], 2, ',', '.');
    $this->mrTemplate->AddVar('content', 'URL_TAMBAH_TAF', Dispatcher::Instance()->GetUrl('approval_taf', 'dataTaf', 'view', 'html') . "&dataId=" . $this->encDataId.'&op=add');  
		
		
    //tampilkan history taf
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
				
				$urlAccept = 'approval_taf|deleteDataTaf|do|html-dataId-'.$idEnc;
        $urlKembali = 'approval_taf|historyDataTaf|view|html';
        $dataName = $dataHistory[$i]['tipe'].' No : '.$dataHistory[$i]['no'];
          
				$dataHistory[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
        $dataHistory[$i]['url_detail'] = Dispatcher::Instance()->GetUrl('approval_taf', 'detailDataTaf', 'view', 'html') . '&dataId=' . $data['idPegawai'] . '&pilihpegawai=' . $pilihpegawai. '&dataId2=' . $idEnc;
        $dataHistory[$i]['url_print'] = Dispatcher::Instance()->GetUrl('approval_taf', 'cetakDataTaf', 'view', 'html') . '&dataId=' . $data['idPegawai'] . '&pilihpegawai=' . $pilihpegawai. '&dataId2=' . $idEnc;
        $dataHistory[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('approval_taf', 'dataTaf', 'view', 'html') . '&op=edit' . '&dataId=' . $data['idPegawai'] . '&pilihpegawai=' . $pilihpegawai. '&dataId2=' . $idEnc;
				
				if ($dataHistory[$i]['supv_status']==''){
          $this->mrTemplate->SetAttribute('edit_data', 'visibility', 'visible');
          $this->mrTemplate->AddVars('edit_data', $dataHistory[$i], 'DATA_');
        }
        
        if ($dataHistory[$i]['supv_status']==''){ $dataHistory[$i]['supv_button']='lamp-off'; } else
        if ($dataHistory[$i]['supv_status']=='Approved'){ $dataHistory[$i]['supv_button']='lamp-green'; } else
        if ($dataHistory[$i]['supv_status']=='Not Approved'){ $dataHistory[$i]['supv_button']='lamp-red'; }
        
        if ($dataHistory[$i]['hrd_status']==''){ $dataHistory[$i]['hrd_button']='lamp-off'; } else
        if ($dataHistory[$i]['hrd_status']=='Approved'){ $dataHistory[$i]['hrd_button']='lamp-green'; } else
        if ($dataHistory[$i]['hrd_status']=='Not Approved'){ $dataHistory[$i]['hrd_button']='lamp-red'; }
        
        if ($dataHistory[$i]['fin_status']==''){ $dataHistory[$i]['fin_button']='lamp-off'; } else
        if ($dataHistory[$i]['fin_status']=='Approved'){ $dataHistory[$i]['fin_button']='lamp-green'; } else
        if ($dataHistory[$i]['fin_status']=='Not Approved'){ $dataHistory[$i]['fin_button']='lamp-red'; }else
        if ($dataHistory[$i]['fin_status']=='On Hold'){ $dataHistory[$i]['fin_button']='lamp-yellow'; } 
        
        
        $dataHistory[$i]['tgl_aju'] = $this->periode2string($dataHistory[$i]['tgl_aju']);
        $dataHistory[$i]['total_anggaran'] = number_format($dataHistory[$i]['total_anggaran'], 2, ',', '.');
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
