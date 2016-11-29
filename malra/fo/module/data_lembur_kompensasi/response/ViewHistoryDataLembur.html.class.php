<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_lembur_kompensasi/business/lembur.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';

class ViewHistoryDataLembur extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/data_lembur_kompensasi/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_history_data_lembur.html');
	}
	
	function ProcessRequest() {
	  $ObjDatPeg = new DataPegawai();
    $_GET['dataId'] = $ObjDatPeg->GetPegIdByUserName();
    $return['is_supervisor']=$ObjDatPeg->IsSupervisor($_GET['dataId']);
    
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
    $tipe[0]['name'] = "request";
    #$tipe[1]['id'] = "submit to approved";
    #$tipe[1]['name'] = "submit to approved";
    $tipe[1]['id'] = "approved";
    $tipe[1]['name'] = "approved";
    $tipe[2]['id'] = "rejected";
    $tipe[2]['name'] = "rejected";
    //print_r($tipe);
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
	  
	  if ($data['is_supervisor']==false) $this->mrTemplate->SetAttribute('approval', 'visibility', 'hidden');
	  $this->mrTemplate->AddVar('approval', 'URL_APPROVAL_LEMBUR', Dispatcher::Instance()->GetUrl('approval_lembur_kompensasi', 'historyDataLembur', 'view', 'html') . "&dataId=" . $data['idPegawai']);
	  
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('data_lembur_kompensasi', 'historyDataLembur', 'view', 'html') . "&dataId=" . $this->encDataId);
		$this->mrTemplate->AddVar('content', 'URL_TAMBAH_LEMBUR', Dispatcher::Instance()->GetUrl('data_lembur_kompensasi', 'dataLembur', 'view', 'html') . "&op=add"."&dataId=" . $this->encDataId);
    $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('data_lembur_kompensasi', 'pegawai', 'view', 'html'));
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
			  $idEnc = Dispatcher::Instance()->Encrypt($dataHistory[$i]['id']);
				$no = $i+$data['start'];
				$dataHistory[$i]['number'] = $no;
				if ($no % 2 != 0) {
          $dataHistory[$i]['class_name'] = 'table-common-even';
        }else{
          $dataHistory[$i]['class_name'] = '';
        }
				
				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
				if($i == sizeof($dataHistory)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);

				
				
        $dataHistory[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('data_lembur_kompensasi', 'deleteDataLembur', 'do', 'html') . '&dataId=' . $idEnc;
        $dataHistory[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('data_lembur_kompensasi', 'dataLembur', 'view', 'html') . '&op=edit' . '&dataId=' . $data['idPegawai'] . '&dataId2=' . $idEnc;
        $dataHistory[$i]['url_detail'] = Dispatcher::Instance()->GetUrl('data_lembur_kompensasi', 'detailDataLembur', 'view', 'html') . '&dataId=' . $data['idPegawai'] . '&dataId2=' . $idEnc;
        
        #$dataHistory[$i]['mulai'] = $this->periode2string($dataHistory[$i]['mulai']);
        #$dataHistory[$i]['selesai'] = $this->periode2string($dataHistory[$i]['selesai']);
        
        if ($dataHistory[$i]['status']=='request'){
          $this->mrTemplate->SetAttribute('edit_data', 'visibility', 'visible');
          $dataHistory[$i]['tglstatus']='-';
        }
        
        $this->mrTemplate->AddVars('edit_data', $dataHistory[$i], 'DATA_');
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
	   return $hour[$jam].' hour '.$menit.' minutes';
	}
}
?>