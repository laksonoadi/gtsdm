<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/ref_potongan_pph/business/PotonganPph.class.php';

class ViewPotonganPph extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
		'module/ref_potongan_pph/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_potonganpph.html');
	}
	
	function ProcessRequest() {
		$pphObj = new PotonganPph();
		if($_POST || isset($_GET['cari'])) {
			if(isset($_POST['pph'])) {
				$pph = $_POST['pph'];
			} elseif(isset($_GET['pph'])) {
				$pph = Dispatcher::Instance()->Decrypt($_GET['pph']);
			} else {
				$pph = '';
			}
		}
		
	//view
		$totalData = $pphObj->GetCountDataPph($pph);
		$itemViewed = 20;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataPph = $pphObj->getDataPph($startRec, $itemViewed, $pph);
//		print_r($dataPph);
		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . '&pph=' . Dispatcher::Instance()->Encrypt($pph) . '&cari=' . Dispatcher::Instance()->Encrypt(1));

		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
		
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];

		$return['dataPph'] = $dataPph;
		$return['start'] = $startRec+1;

		$return['search']['pph'] = $pph;
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$search = $data['search'];
		$this->mrTemplate->AddVar('content', 'PPH', $search['pph']);
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('ref_potongan_pph', 'potonganPph', 'view', 'html'));
		$this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('ref_potongan_pph', 'inputPotonganPph', 'view', 'html'));
		$this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('ref_potongan_pph', 'excelPotongan', 'view', 'xls'). '&cari=' . $search['pph']);
		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}

		if (empty($data['dataPph'])) {
			$this->mrTemplate->AddVar('data_pph', 'PPH_EMPTY', 'YES');
		} else {
			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
			$encPage = Dispatcher::Instance()->Encrypt($decPage);
			$this->mrTemplate->AddVar('data_pph', 'PPH_EMPTY', 'NO');
			$dataPph = $data['dataPph'];
		
//mulai bikin tombol delete
			$label = "Manajemen Pph";
			$urlDelete = Dispatcher::Instance()->GetUrl('ref_potongan_pph', 'deletePotonganPph', 'do', 'html');
			$urlReturn = Dispatcher::Instance()->GetUrl('ref_potongan_pph', 'potonganPph', 'view', 'html');
			Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
			$this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
			$this->mrTemplate->AddVar('content', 'SET_ORDER', Dispatcher::Instance()->GetUrl('ref_potongan_pph', 'setOrder', 'do', 'html'));

			for ($i=0; $i<sizeof($dataPph); $i++) {
				$no = $i+$data['start'];
				$dataPph[$i]['number'] = $no;
				if ($no % 2 != 0) $dataPph[$i]['class_name'] = 'table-common-even';
				else $dataPph[$i]['class_name'] = '';
				
				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);				
				if($i == sizeof($dataPph)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
				$idEnc = Dispatcher::Instance()->Encrypt($dataPph[$i]['pphrp_id']);
				
				$urlAccept = 'ref_potongan_pph|deletePotonganPph|do|html-cari-'.$cari;
				$urlReturn = 'ref_potongan_pph|potonganPph|view|html-cari-'.$cari;
				$label = 'Range Potongan PPh-21';
				$dataPph[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('ref_potongan_pph', 'inputPotonganPph', 'view', 'html') . '&dataId=' . $idEnc . '&page=' . $encPage . '&cari='.$cari;
				$dataPph[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlReturn.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataPph[$i]['pphrp_nama'];               
				
				$dataPph[$i]['pphrp_nominal'] = number_format($dataPph[$i]['pphrp_nominal'], 2, ',', '.');
				$this->mrTemplate->AddVars('data_pph_item', $dataPph[$i], 'PPH_');
				$this->mrTemplate->parseTemplate('data_pph_item', 'a');	 
			}
		}
	}
}
?>
