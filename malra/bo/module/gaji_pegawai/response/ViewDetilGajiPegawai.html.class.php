<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/business/AppDetilGajiPegawai.class.php';

class ViewDetilGajiPegawai extends HtmlResponse {
	var $Data;
	var $Pesan;
	
	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') . 'module/gaji_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('detil_gaji_pegawai.html');
	}
	
	function ProcessRequest() {
		$idDec = Dispatcher::Instance()->Decrypt($_REQUEST['dataId']);
		$Obj = new AppDetilGajiPegawai();
		//$msg = Messenger::Instance()->Receive(__FILE__);
		//$this->Pesan = $msg[0][1];
		//$this->Data = $msg[0][0];

		$info = $Obj->GetInfo($idDec);
		$dataKomponen = $Obj->GetData($idDec);
      //print_r($info);

		//$return['decDataId'] = $idDec;
		$return['info'] = $info;
		$return['dataKomponen'] = $dataKomponen;
		return $return;
	}

	function ParseTemplate($data = NULL) {
		//if ($this->Pesan) {
		//	$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
		//	$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
		//}
		$info = $data['info'];
		//print_r($dataGajiPegawai);
		//$this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
		$this->mrTemplate->AddVar('content', 'NIP', $info['nip']);
		$this->mrTemplate->AddVar('content', 'SATKER_UNIT', $info['satker_unit']);
		$this->mrTemplate->AddVar('content', 'NAMA', $info['nama']);
		$this->mrTemplate->AddVar('content', 'ALAMAT', $info['alamat']);
		$this->mrTemplate->AddVar('content', 'HP', $info['hp']);
		$this->mrTemplate->AddVar('content', 'TELP', $info['telp']);
		$this->mrTemplate->AddVar('content', 'REKENING', $info['rekening']);
		$this->mrTemplate->AddVar('content', 'RESIPIEN', $info['resipien']);
		$this->mrTemplate->AddVar('content', 'BANK', $info['bank']);
		$this->mrTemplate->AddVar('content', 'BANK_LABEL', $info['bank_label']);
		$this->mrTemplate->AddVar('content', 'IS_CASH', $info['cash']);
		$this->mrTemplate->AddVar('content', 'TGL_GAJI', $info['tgl_gaji']);
		$this->mrTemplate->AddVar('content', 'IS_AKTIF', $info['aktif']);

      if(empty($data['dataKomponen'])) {
         $this->mrTemplate->AddVar('data', 'IS_EMPTY', 'YES');
      } else {
		   $dataKomponen = $data['dataKomponen'];
         $this->mrTemplate->AddVar('data', 'IS_EMPTY', 'NO');
         for($i=0; $i<sizeof($dataKomponen); $i++) {
            if($dataKomponen[$i]['id'] == $dataKomponen[$i-1]['id']) {
               $dataKomponen[$i]['nama'] = "";
            }
				$this->mrTemplate->AddVars('data_item', $dataKomponen[$i], 'DATA_');
				$this->mrTemplate->parseTemplate('data_item', 'a');	 
         }
      }
		//$this->mrTemplate->AddVar('content', 'GAJIPEGAWAI_NAMA', empty($dataGajiPegawai[0]['gaji_pegawai_nama'])?$this->Data['gaji_pegawai_nama']:$dataGajiPegawai[0]['gaji_pegawai_nama']);
/*
		if ($_REQUEST['dataId']=='') {
			$url="addGajiPegawai";
			$tambah="Tambah";
		} else {
			$url="updateGajiPegawai";
			$tambah="Ubah";
		}
      */
		//$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('gaji_pegawai', $url, 'do', 'html') . "&dataId=" . Dispatcher::Instance()->Encrypt($data['decDataId']));
		$this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('gaji_pegawai', 'gajiPegawai', 'view', 'html'));

		//$this->mrTemplate->AddVar('content', 'DATAID', Dispatcher::Instance()->Decrypt($_GET['dataId']));
		//$this->mrTemplate->AddVar('content', 'PAGE', Dispatcher::Instance()->Decrypt($_GET['page']));
	}
}
?>
