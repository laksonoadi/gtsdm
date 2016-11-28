<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_gaji_karyawan/business/AppKomponenGaji.class.php';

class ViewInputKomponen extends HtmlResponse {
	var $Data;
	var $Pesan;
	
	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') . 'module/komponen_gaji_karyawan/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('input_komponen.html');
	}
	
	function ProcessRequest() {
		$idDec = Dispatcher::Instance()->Decrypt($_REQUEST['dataId']);
		$komponenObj = new AppKomponen();
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->Data = $msg[0][0];

		$insert = $komponenObj->InsertJenisPegawaiJoinKomponen($idDec);
		$return['jenis']=$komponenObj->GetJenisPegawaiJoinKomponen($idDec);
		
		$dataKomponen = $komponenObj->GetDataKomponenById($idDec);

		$return['komponenGaji'] = $komponenObj->GetKomponenGaji();

		$return['decDataId'] = $idDec;
		$return['dataKomponen'] = $dataKomponen;
		return $return;
	}

	function ParseTemplate($data = NULL) {
		if ($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
		}
		$dataKomponen = $data['dataKomponen'];
		
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
		
		if ($_REQUEST['dataId']=='') {
			$url="addKomponen";
			if ($lang=='eng'){
				$tambah="Add";
			}else{
				$tambah="Tambah";  
			}
		} else {
			$url="updateKomponen";
			if ($lang=='eng'){
				$tambah="Update";
			}else{
				$tambah="Ubah";  
			}
		}
		$this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
		$this->mrTemplate->AddVar('content', 'KOMPONEN_NAMA', empty($dataKomponen[0]['nama'])?$this->Data['nama']:$dataKomponen[0]['nama']);
		$this->mrTemplate->AddVar('content', 'FORMULA', empty($dataKomponen[0]['formula'])?$this->Data['formula']:$dataKomponen[0]['formula']);

		$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('komponen_gaji_karyawan', $url, 'do', 'html') . "&dataId=" . Dispatcher::Instance()->Encrypt($data['decDataId']));

		$komponenGaji = $data['komponenGaji'];

      
		for($i=0;$i<count($komponenGaji);$i++){
			$this->mrTemplate->AddVars('formula-list', $komponenGaji[$i], 'FORMULA_');
			$button[]=$komponenGaji[$i]['buttonGaji'];
			$this->mrTemplate->parseTemplate('formula-list', 'a');
		}
		$button = implode('|',$button);

		$this->mrTemplate->AddVar('content', 'FORMULA_BUTTON', $button);
   
		$this->mrTemplate->AddVar('content', 'DATAID', Dispatcher::Instance()->Decrypt($_GET['dataId']));
		$this->mrTemplate->AddVar('content', 'PAGE', Dispatcher::Instance()->Decrypt($_GET['page']));
		
		if (empty($data['jenis'])) {
			$this->mrTemplate->AddVar('data_jenis_pegawai', 'DATA_EMPTY', 'YES');
		} else {      
			$this->mrTemplate->AddVar('data_jenis_pegawai', 'DATA_EMPTY', 'NO');
			
			for ($i=0; $i<sizeof($data['jenis']); $i++){
				$data['jenis'][$i]['no']=$i+1;
				$this->mrTemplate->AddVars('data_jenis_pegawai_item', $data['jenis'][$i], 'MPG_');
				$this->mrTemplate->parseTemplate('data_jenis_pegawai_item', 'a');
			}
		}
	}
}
?>
