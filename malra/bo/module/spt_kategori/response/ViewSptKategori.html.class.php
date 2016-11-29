<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/spt_kategori/business/sptkategori.class.php';
   
class ViewSptKategori extends HtmlResponse{
	public function TemplateModule(){
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
		'module/spt_kategori/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_spt_kategori.html');
	}
   
	public function ProcessRequest(){
		$obj = new SptKategori;

		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
		
		// inisialisasi data filter
		$return['input'] = $_POST->AsArray();

		//inisialisasi paging
		$itemViewed = 20;
		$currPage = 1;
		$startRec = 0 ;

		if(isset($_GET['page'])){
			$currPage = $_GET['page']->Integer()->Raw();
			if ($currPage > 0)$startRec =($currPage-1) * $itemViewed;
			else $currPage = 1;
		}

		$return['start'] = $startRec+1;
		$totalData = $obj->count($return['input']);
		$return['dataSheet'] = $obj->get($startRec,$itemViewed,$return['input']);
		
		// links
		$this->title = 'Referensi kategori SPT';
		$this->listUrl = Dispatcher::Instance()->GetUrl('spt_kategori','sptKategori','view','html');
		$this->inputUrl = Dispatcher::Instance()->GetUrl('spt_kategori','inputSptKategori','view','html');
		$this->doDeleteUrl = Dispatcher::Instance()->GetUrl('spt_kategori', 'deleteSptKategori', 'do', 'html');
		$this->deleteUrl = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
		"&urlDelete=".Dispatcher::Instance()->Encrypt('spt_kategori|deleteSptKategori|do|html').
		"&urlReturn=".Dispatcher::Instance()->Encrypt('spt_kategori|sptKategori|view|html').
		"&label=".Dispatcher::Instance()->Encrypt($this->title);
		
		// end of links
		
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
		$this->mrTemplate->AddVars('content', $data['input']);

		// Filter Form
		$this->mrTemplate->AddVar('content', 'CARI', $data['cari']);
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', $this->listUrl);
		$this->mrTemplate->AddVar('content', 'URL_ADD', $this->inputUrl);
		// ---------
		
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
				$data[$i]['encid'] = $encId;
				$data[$i]['class_name'] = ($no % 2 == 0) ? '' : 'table-common-even';
				$data[$i]['url_update'] = $this->inputUrl .'&id='.$encId;
				$data[$i]['url_delete'] = $this->deleteUrl .
					"&id=" . $encId .
					"&dataName=" . Dispatcher::Instance()->Encrypt($data[$i]['nama']);
				$this->mrTemplate->AddVars('data_item', $data[$i], '');
				$this->mrTemplate->parseTemplate('data_item', 'a');
			}
		}
	}
}
?>