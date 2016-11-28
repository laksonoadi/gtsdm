<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_pph_21/business/AppKomponenPph.class.php';

class ViewInputKomponen extends HtmlResponse {
	var $Data;
	var $Pesan;
	
	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
		'module/komponen_pph_21/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('input_komponen.html');
	}
	
	function ProcessRequest() {
		$idDec = Dispatcher::Instance()->Decrypt($_REQUEST['dataId']);
		$komponenObj = new AppKomponen();
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->Data = $msg[0][0];

		$dataKomponen = $komponenObj->GetDataKomponenById($idDec);

      $return['komponenPph'] = $komponenObj->GetKomponenPph();
	  $return['komponenGaji'] = $komponenObj->GetKomponenGaji();
	   
		$return['decDataId'] = $idDec;
		$return['dataKomponen'] = $dataKomponen;
		
		$this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
		if ($this->lang=='eng'){
			$return['tambah']="Add";
			$return['ubah']="Edit";		
		}
		else {
			$return['tambah']="Tambah";
			$return['ubah']="Ubah";
		}
		
		//startof kombo jenis
    $arrJenis[0]['id'] = "tambah";
    $arrJenis[1]['id'] = "kurang";
     if ($this->lang=='eng'){
        $arrJenis[0]['name'] = "Income Tax Increment Factor";
        $arrJenis[1]['name'] = "Income Tax Decrement Factor";
     }else{
        $arrJenis[0]['name'] = "Faktor Penambah PPH";  
        $arrJenis[1]['name'] = "Faktor Pengurang PPH";
     }
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis', array('jenis', $arrJenis, (empty($this->Data['jenis'])?$dataGaji[0]['jenis']:$this->Data['jenis']), '-', ' style="width:200px;" '), Messenger::CurrentRequest);
    //endof kombo jenis
		
		return $return;
	}

	function ParseTemplate($data = NULL) {
		if ($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
		}
		$dataKomponen = $data['dataKomponen'];
		
		if ($_REQUEST['dataId']=='') {
			$url="addKomponen";
			$param=$data['tambah'];
		} else {
			$url="updateKomponen";
			$param=$data['ubah'];
		}
		$this->mrTemplate->AddVar('content', 'JUDUL', $param);
		$this->mrTemplate->AddVar('content', 'KOMPONEN_NAMA', empty($dataKomponen[0]['nama'])?$this->Data['nama']:$dataKomponen[0]['nama']);
		$this->mrTemplate->AddVar('content', 'FORMULA', empty($dataKomponen[0]['formula'])?$this->Data['formula']:$dataKomponen[0]['formula']);
		$this->mrTemplate->AddVar('content', 'MAX_VALUE', empty($dataKomponen[0]['max_value'])?$this->Data['max_value']:$dataKomponen[0]['max_value']);

		$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('komponen_pph_21', $url, 'do', 'html') . "&dataId=" . Dispatcher::Instance()->Encrypt($data['decDataId']));

      $komponenPph = $data['komponenPph'];
	  $komponenGaji = $data['komponenGaji'];

      
      for($i=0;$i<count($komponenPph);$i++){
         $this->mrTemplate->AddVars('formula-list', $komponenPph[$i], 'FORMULA_');
         $button[]=$komponenPph[$i]['buttonPph'];
         $this->mrTemplate->parseTemplate('formula-list', 'a');
      }
	  
	  for($i=0;$i<count($komponenGaji);$i++){
         $this->mrTemplate->AddVars('formula-list_gaji', $komponenGaji[$i], 'FORMULA_');
         $buttonGaji[]=$komponenGaji[$i]['buttonGaji'];
         $this->mrTemplate->parseTemplate('formula-list_gaji', 'a');
      }
	  
      $button = implode('|',$button);
	  $buttonGaji = implode('|',$buttonGaji);

      $this->mrTemplate->AddVar('content', 'FORMULA_BUTTON', $button);
	  $this->mrTemplate->AddVar('content', 'FORMULA_BUTTON_GAJI', $buttonGaji);
   
		$this->mrTemplate->AddVar('content', 'DATAID', Dispatcher::Instance()->Decrypt($_GET['dataId']));
		$this->mrTemplate->AddVar('content', 'PAGE', Dispatcher::Instance()->Decrypt($_GET['page']));
	}
}
?>
