<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/pendapatan_lain/business/popup_pegawai.class.php';

class ViewPopupPegawai extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'].'module/pendapatan_lain/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_popup_pegawai.html');
	}
   
    function TemplateBase() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') . 'main/template/');
      $this->SetTemplateFile('document-common-popup.html');
      $this->SetTemplateFile('layout-common-popup.html');
   }
	
	function ProcessRequest() {
		$Obj = new PopupPegawai();
		if($_POST || isset($_GET['cari'])) {
			if(isset($_POST['nama'])) {
				$nama = $_POST['nama'];
			} elseif(isset($_GET['nama'])) {
				$nama = Dispatcher::Instance()->Decrypt($_GET['nama']);
			} else {
				$nama = '';
			}
		}
		
		//view
		$totalData = $Obj->GetCountData($nama);
		$itemViewed = 15;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataPegawai = $Obj->getData($startRec, $itemViewed,$nama);
		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType.'&nama='.Dispatcher::Instance()->Encrypt($nama).'&cari='.Dispatcher::Instance()->Encrypt(1));
		$dest = "popup-subcontent";
    Messenger::Instance()->SendToComponent('paging2', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage, $dest), Messenger::CurrentRequest);

		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
				
		$return['dataPegawai'] = $dataPegawai;
		//print_r($dataKomponen);
		$return['start'] = $startRec+1;
		$return['search']['nama'] = $nama;

		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$search = $data['search'];
		$this->mrTemplate->AddVar('content', 'NAMA', $search['nama']);
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('pendapatan_lain', 'popupPegawai', 'view', 'html'));
		
    if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
      if(empty($data['dataPegawai'])) {
			$this->mrTemplate->AddVar('data_pegawai', 'DATA_EMPTY', "YES");
      } else {
			$this->mrTemplate->AddVar('data_pegawai', 'DATA_EMPTY', "NO");
         $dataPegawai = $data['dataPegawai'];
         for ($i=0; $i<sizeof($dataPegawai); $i++) {
            $no = $i+$data['start'];
            $dataPegawai[$i]['number'] = $no;
            if ($no % 2 != 0) $dataPegawai[$i]['class_name'] = 'table-common-even';
            else $dataPegawai[$i]['class_name'] = '';
			$dataPegawai[$i]['set_parent'] ='<a href="javascript:void(0)" onclick="addPegawaiItem(this, \''.$dataPegawai[$i]['id'].'\',\''.$dataPegawai[$i]['kode'].'\',\''.$dataPegawai[$i]['nama'].'\')" onmouseover="status=\'Set preferences...\';return true" ><img src="images/button-check.gif" alt="Pilih"/></a>';
            $this->mrTemplate->AddVars('data_pegawai_item', $dataPegawai[$i], 'DATA_');
            $this->mrTemplate->parseTemplate('data_pegawai_item', 'a');	 
         }
      }
	}
}
?>