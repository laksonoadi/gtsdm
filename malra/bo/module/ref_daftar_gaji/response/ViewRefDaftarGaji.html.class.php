<?php
// ini_set('display_errors',1);
// error_reporting(E_ALL);
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/ref_daftar_gaji/business/RefDaftarGaji.class.php';
   
class ViewRefDaftarGaji extends HtmlResponse{
	public function TemplateModule(){
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
		'module/ref_daftar_gaji/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_ref_daftar_gaji.html');
	}
   
	public function ProcessRequest(){
		$obj = new refDaftarGaji;

		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Data = isset($msg[0][0]) ? $msg[0][0] : array();
		$this->Pesan = isset($msg[0][1]) ? $msg[0][1] : NULL;
		$this->css = isset($msg[0][2]) ? $msg[0][2] : NULL;
		
		// inisialisasi data filter
		$return['filter'] = $_POST->AsArray();
		
		$filter = $this->Data;
		foreach($return['filter'] as $key => $value) {
			$filter[$key] = $value;
		}
		Messenger::Instance()->Send(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType, array($filter), Messenger::UntilFetched);

		//inisialisasi paging
		$itemViewed = 50;
		$currPage = 1;
		$startRec = 0 ;

		if(isset($_GET['page'])){
			$currPage = $_GET['page']->Integer()->Raw();
			if ($currPage > 0)$startRec =($currPage-1) * $itemViewed;
			else $currPage = 1;
		}

		$return['start'] = $startRec+1;
		$totalData = $obj->count($filter);
		$return['dataSheet'] = $obj->get($startRec, $itemViewed, $filter);
		
		// links
		$this->title = 'Referensi Daftar Gaji';
		$this->listUrl = Dispatcher::Instance()->GetUrl('ref_daftar_gaji','refDaftarGaji','view','html');
		$this->inputUrl = Dispatcher::Instance()->GetUrl('ref_daftar_gaji','inputRefDaftarGaji','view','html');
		$this->detailUrl = Dispatcher::Instance()->GetUrl('ref_daftar_gaji','detailRefDaftarGaji','view','html');
		$this->doDeleteUrl = Dispatcher::Instance()->GetUrl('ref_daftar_gaji', 'deleteRefDaftarGaji', 'do', 'html');
		$this->deleteUrl = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
		"&urlDelete=".Dispatcher::Instance()->Encrypt('ref_daftar_gaji|deleteRefDaftarGaji|do|html').
		"&urlReturn=".Dispatcher::Instance()->Encrypt('ref_daftar_gaji|refDaftarGaji|view|html').
		"&label=".Dispatcher::Instance()->Encrypt($this->title);
		
		// end of links
		
		// combo
		$arrMasaKerja = $obj->getComboMasaKerja();
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'masa_kerja', array('masa_kerja', $arrMasaKerja, (isset($filter['masa_kerja']) ? $filter['masa_kerja'] : null), 'true', ''), Messenger::CurrentRequest);
		// $arrGolRuang = $obj->getComboGolonganRuang();
		// Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'gol_ruang', array('gol_ruang', $arrGolRuang, (isset($filter['gol_ruang']) ? $filter['gol_ruang'] : null), 'true', ''), Messenger::CurrentRequest);
		$arrGolongan = $obj->getComboGolongan();
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'golongan', array('golongan', $arrGolongan, (isset($filter['golongan']) ? $filter['golongan'] : null), 'true', ''), Messenger::CurrentRequest);
		$arrRuang = $obj->getComboRuang();
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'ruang', array('ruang', $arrRuang, (isset($filter['ruang']) ? $filter['ruang'] : null), 'true', ''), Messenger::CurrentRequest);
		// end of combo
		
		$url = $this->listUrl;
		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
		
		return $return;
	}
   
	public function ParseTemplate($data = NULL){
		if($this->Pesan){
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
		$this->mrTemplate->AddVar('content', 'TITLE', strtoupper($this->title));

		// Filter Form
		$this->mrTemplate->AddVar('search', 'URL_SEARCH', $this->listUrl);
		$this->mrTemplate->AddVars('search', $data['filter']);
		// ---------
		$this->mrTemplate->AddVar('content', 'URL_ADD', $this->inputUrl);
		
		Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($this->title, $this->doDeleteUrl,$this->listUrl), Messenger::NextRequest);
		$this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
		
		if(empty($data['dataSheet'])){
			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
			return NULL;
		}else{
			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
		
			$no = $data['start'];
			$data = $data['dataSheet'];
			for($i = 0, $m = count($data); $i < $m; ++$i, ++$no){
				$encId = Dispatcher::Instance()->Encrypt($data[$i]['id']);
				$data[$i]['no'] = $no;
				$data[$i]['nama'] = 'Pangkat Golongan/Ruang: '. $data[$i]['gol_ruang'] .
					', Masa Kerja: '. $data[$i]['masa_kerja'] .
					', Nominal Gaji: '. $data[$i]['nominal'];
				$data[$i]['encid'] = $encId;
				$data[$i]['class_name'] = ($no % 2 == 0) ? '' : 'table-common-even';
				$data[$i]['url_detail'] = $this->detailUrl .'&id='.$encId;
				$data[$i]['url_update'] = $this->inputUrl .'&id='.$encId;
				$data[$i]['url_delete'] = $this->deleteUrl .
					"&id=" . $encId .
					"&dataName=". urlencode($data[$i]['nama']);
				$data[$i]['nominal_nice'] = number_format($data[$i]['nominal'], 0, ',', '.');
				$this->mrTemplate->AddVars('data_item', $data[$i], '');
				$this->mrTemplate->parseTemplate('data_item', 'a');
			}
		}
	}
}
?>