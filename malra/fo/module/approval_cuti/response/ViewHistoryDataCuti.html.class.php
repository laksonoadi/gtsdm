<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_cuti/business/cuti.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';

class ViewHistoryDataCuti extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/approval_cuti/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_history_data_cuti.html');
	}
	
	function ProcessRequest() {
	  $ObjDatPeg = new DataPegawai();
    $_GET['dataId'] = $ObjDatPeg->GetPegIdByUserName();
      
		$Obj = new Cuti();
		if($_POST || isset($_GET['cari'])) {
			if(isset($_POST['year'])) {
				$tampilkan = $_POST['year'];
			} elseif(isset($_GET['year'])) {
				$tampilkan = Dispatcher::Instance()->Decrypt($_GET['year']);
			} else {
				$tampilkan = date('Y');
			}
			
			if(isset($_POST['status'])) {
				$status = $_POST['status'];
			} elseif(isset($_GET['status'])) {
				$status = Dispatcher::Instance()->Decrypt($_GET['status']);
			} else {
				$status = 'request';
			}
			
			if(isset($_POST['pegawai'])) {
				$pilihpegawai = $_POST['pegawai'];
			} elseif(isset($_GET['pegawai'])) {
				$pilihpegawai = Dispatcher::Instance()->Decrypt($_GET['pegawai']);
			} elseif(isset($_GET['pilihpegawai'])) {
				$pilihpegawai = Dispatcher::Instance()->Decrypt($_GET['pilihpegawai']);
			} else {
				$pilihpegawai = 0;
			}
		}
		$this->decDataId = Dispatcher::Instance()->Decrypt($_GET['dataId']);
		$this->encDataId = Dispatcher::Instance()->Encrypt($this->decDataId);
		
		//Combo tahun cuti
    $year=$Obj->GetComboTahunCuti($this->decDataId);
    $pegawai=$Obj->GetComboPegawaiBawahan($this->decDataId);
    $arrStatus[0]['id']='request'; $arrStatus[0]['name']='request';
    $arrStatus[1]['id']='approved'; $arrStatus[1]['name']='approved';
    $arrStatus[2]['id']='rejected'; $arrStatus[2]['name']='rejected';
    
    
	  //view
    $dataPegawai = $Obj->GetDataById($pilihpegawai);  
		$totalData = $Obj->GetCountCuti($pilihpegawai, $tampilkan,$status);
		$itemViewed = 15;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataHistory = $Obj->GetDataCuti($startRec, $itemViewed, $pilihpegawai, $tampilkan, $status);
    //print_r($dataHistory);
    $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . '&dataId=' . $this->encDataId . '&year=' . Dispatcher::Instance()->Encrypt($tampilkan) . '&pegawai=' . Dispatcher::Instance()->Encrypt($pilihpegawai). '&cari=' . Dispatcher::Instance()->Encrypt(1));

		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
    
    
    //print_r($tipe);
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'year', array('year',$year,$tampilkan,'false',''), Messenger::CurrentRequest);
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegawai', array('pegawai',$pegawai,$pilihpegawai,'false',''), Messenger::CurrentRequest);
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status',$arrStatus,$status,'true',''), Messenger::CurrentRequest);

		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];

		$return['dataHistory'] = $dataHistory;
		$return['start'] = $startRec+1;
    $return['dataPegawai'] = $dataPegawai;
    $return['idPegawai'] = $this->encDataId;
    $return['pilihPegawai'] = $pilihpegawai;
    $return['tahun']=$tampilkan;
    $return['sisa_cuti'] = $Obj->GetSisaCuti($pilihpegawai, $tampilkan);
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('approval_cuti', 'historyDataCuti', 'view', 'html') . "&dataId=" . $this->encDataId);
		$this->mrTemplate->AddVar('content', 'URL_PERIODE_CUTI', Dispatcher::Instance()->GetUrl('periode_cuti', 'periodeCuti', 'view', 'html') . "&pegId=" . $this->encDataId);
		$this->mrTemplate->AddVar('content', 'URL_TAMBAH_CUTI', Dispatcher::Instance()->GetUrl('approval_cuti', 'dataCuti', 'view', 'html') . "&dataId=" . $this->encDataId.'&op=add');
    $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('approval_cuti', 'pegawai', 'view', 'html'));
		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
		
		//tampilkan data pegawai
		$dataPegawai = $data['dataPegawai'];
		$this->mrTemplate->AddVar('content', 'TAHUN', $data['tahun']);

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
				
        $dataHistory[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('approval_cuti', 'deleteDataCuti', 'view', 'html') . '&dataId=' . $idEnc;
        $dataHistory[$i]['url_detail'] = Dispatcher::Instance()->GetUrl('approval_cuti', 'detailDataCuti', 'view', 'html') . '&dataId=' . $data['idPegawai'] . '&pilihpegawai=' . $data['pilihPegawai']. '&year=' . $data['tahun'] . '&dataId2=' . $idEnc;
        $dataHistory[$i]['url_approved'] = Dispatcher::Instance()->GetUrl('approval_cuti', 'dataCuti', 'view', 'html') . '&op=edit' . '&dataId=' . $data['idPegawai'] . '&pilihpegawai=' . $data['pilihPegawai'] . '&year=' . $data['tahun'] . '&dataId2=' . $idEnc. '&approved=yes';
        $dataHistory[$i]['url_unapproved'] = Dispatcher::Instance()->GetUrl('approval_cuti', 'dataCuti', 'view', 'html') . '&op=edit' . '&dataId=' . $data['idPegawai'] . '&pilihpegawai=' . $data['pilihPegawai'] . '&year=' . $data['tahun'] . '&dataId2=' . $idEnc. '&approved=no';
        $dataHistory[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('approval_cuti', 'dataCuti', 'view', 'html') . '&op=edit' . '&dataId=' . $data['idPegawai'] . '&pilihpegawai=' . $data['pilihPegawai'] . '&year=' . $data['tahun'] . '&dataId2=' . $idEnc;
        
        $dataHistory[$i]['mulai'] = $this->periode2string($dataHistory[$i]['mulai']);
        $dataHistory[$i]['selesai'] = $this->periode2string($dataHistory[$i]['selesai']);
        if (($dataHistory[$i]['status']=='request')&&($dataHistory[$i]['status_periode']=='Active')){
          $dataHistory[$i]['tglstatus']='-';
          $dataHistory[$i]['url_action']='<a class="xhr dest_subcontent-element" href="'.$dataHistory[$i]['url_approved'].'" title="Approved"><img src="images/button-check.gif" alt="Approved"/></a>';
        } else 
        if (($dataHistory[$i]['status_periode']=='Active')){
          $dataHistory[$i]['url_action']='<a class="xhr dest_subcontent-element" href="'.$dataHistory[$i]['url_unapproved'].'" title="Cancel Approved"><img src="images/button-cancel-tindaklanjut.gif" alt="Cancel Appproved"/></a>';
        } else
        if (($dataHistory[$i]['status']=='request')){
          $dataHistory[$i]['tglstatus']='-';
        }
        
        if ($dataHistory[$i]['status']=='approved'){
          $jumlahApproved=$jumlahApproved+$dataHistory[$i]['durasi'];
        }
        
				$this->mrTemplate->AddVars('edit_data', $dataHistory[$i], 'DATA_');
        $this->mrTemplate->AddVars('data_history_item', $dataHistory[$i], 'DATA_');
				$this->mrTemplate->parseTemplate('data_history_item', 'a');	 
				$jumlah=$jumlah+$dataHistory[$i]['durasi'];
				
			}  
		}
		$this->mrTemplate->AddVar('content', 'JUMLAH_PENGAJUAN', $jumlah);
    $this->mrTemplate->AddVar('content', 'JUMLAH_DISETUJUI', $jumlahApproved);
    $this->mrTemplate->AddVar('content', 'JUMLAH_SISA', $data['sisa_cuti']);
	}
	
	function periode2string($date) {
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return $tanggal.'/'.$bulan.'/'.$tahun;
	}
}
?>
