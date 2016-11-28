<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_pph_21/business/AppKomponenPph.class.php';

class ViewKomponenPph extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
		'module/komponen_pph_21/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_komponen_pph.html');
	}
	
	function ProcessRequest() {
		$komponenObj = new AppKomponen();
		if($_POST || isset($_GET['cari'])) {
			if(isset($_POST['nama_komponen'])) {
				$komponen = $_POST['nama_komponen'];
			} elseif(isset($_GET['nama_komponen'])) {
				$komponen = Dispatcher::Instance()->Decrypt($_GET['nama_komponen']);
			} else {
				$komponen = '';
			}
		}
		
	//view
		$totalData = $komponenObj->GetCountDataKomponen($komponen);
		$itemViewed = 20;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataKomponen = $komponenObj->getDataKomponen($startRec, $itemViewed, $komponen);
		#print_r($dataKomponen);
		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . '&nama_komponen=' . Dispatcher::Instance()->Encrypt($komponen) . '&cari=' . Dispatcher::Instance()->Encrypt(1));

		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);

		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];

		$return['dataKomponen'] = $dataKomponen;
		$return['start'] = $startRec+1;

		$return['search']['komponen'] = $komponen;
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$search = $data['search'];
		$this->mrTemplate->AddVar('content', 'NAMA_KOMPONEN', $search['komponen']);
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('komponen_pph_21', 'komponenPph', 'view', 'html'));
		$this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('komponen_pph_21', 'inputKomponen', 'view', 'html'));
		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}

		if (empty($data['dataKomponen'])) {
			$this->mrTemplate->AddVar('data_komponen', 'KOMPONEN_EMPTY', 'YES');
		} else {
			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
			$encPage = Dispatcher::Instance()->Encrypt($decPage);
			$this->mrTemplate->AddVar('data_komponen', 'KOMPONEN_EMPTY', 'NO');
			$dataKomponen = $data['dataKomponen'];
		
//mulai bikin tombol delete
			$label = "Manajemen Komponen Pph";
			$urlDelete = Dispatcher::Instance()->GetUrl('komponen_pph_21', 'deleteKomponen', 'do', 'html');
			$urlReturn = Dispatcher::Instance()->GetUrl('komponen_pph_21', 'komponenPph', 'view', 'html');
			Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
			$this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

			for ($i=0; $i<sizeof($dataKomponen); $i++) {
				$no = $i+$data['start'];
				$dataKomponen[$i]['number'] = $no;
				if ($no % 2 != 0) $dataKomponen[$i]['class_name'] = 'table-common-even';
				else $dataKomponen[$i]['class_name'] = '';
				
				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);				
				if($i == sizeof($dataKomponen)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
				$idEnc = Dispatcher::Instance()->Encrypt($dataKomponen[$i]['komponen_id']);
				
				$dataKomponen[$i]['max_value']=number_format($dataKomponen[$i]['max_value'], 2, ',', '.');
				
				$dataKomponen[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('komponen_pph_21', 'inputKomponen', 'view', 'html') . '&dataId=' . $idEnc . '&page=' . $encPage . '&cari='.$cari;

				$this->mrTemplate->AddVars('data_komponen_item', $dataKomponen[$i], 'KOMPONEN_');
				if($dataKomponen[$i]['jenis'] == 'tambah'){
          $dataKomponen[$i]['jenis'] = 'increment';
          $this->mrTemplate->AddVar('data_komponen_item', 'KOMPONEN_JENIS', $dataKomponen[$i]['jenis']);
        } elseif($dataKomponen[$i]['jenis'] == 'kurang') {
          $dataKomponen[$i]['jenis'] = 'decrement';
          $this->mrTemplate->AddVar('data_komponen_item', 'KOMPONEN_JENIS', $dataKomponen[$i]['jenis']);
        } else {
          $dataKomponen[$i]['jenis'] = '-';
          $this->mrTemplate->AddVar('data_komponen_item', 'KOMPONEN_JENIS', $dataKomponen[$i]['jenis']);
        }
				$this->mrTemplate->parseTemplate('data_komponen_item', 'a');	 
			}
		}
	}
}
?>
