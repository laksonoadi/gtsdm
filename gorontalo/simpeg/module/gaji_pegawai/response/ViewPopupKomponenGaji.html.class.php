<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/business/AppPopupKomponenGaji.class.php';
//require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user_unit_kerja/business/UserUnitKerja.class.php';

class ViewPopupKomponenGaji extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'].'module/gaji_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_popup_komponengaji.html');
	}
   
    function TemplateBase() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') . 'main/template/');
      $this->SetTemplateFile('document-common-popup.html');
      $this->SetTemplateFile('layout-common-popup.html');
   }
	
	function ProcessRequest() {
		$Obj = new AppPopupKomponenGaji();
		$arrKomp = $Obj->GetComboKomponen();
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
		$itemViewed = 10;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataKomponen = $Obj->getData($startRec, $itemViewed, $nama);
		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . '&nama=' . Dispatcher::Instance()->Encrypt($nama). '&cari=' . Dispatcher::Instance()->Encrypt(1));
		$dest = "popup-subcontent";
    Messenger::Instance()->SendToComponent('paging2', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage, $dest), Messenger::CurrentRequest);
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'nama', array('nama', $arrKomp, $return['cari'], "false", 'id="nama"'), Messenger::CurrentRequest);
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
				
		$return['dataKomponen'] = $dataKomponen;
		//print_r($dataKomponen);
		$return['start'] = $startRec+1;
		$return['search']['nama'] = $nama;

		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$search = $data['search'];
		$this->mrTemplate->AddVar('content', 'NAMA', $search['nama']);
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('gaji_pegawai', 'popupKomponenGaji', 'view', 'html'));
		
		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
      if(empty($data['dataKomponen'])) {
			$this->mrTemplate->AddVar('data_komponen_gaji', 'DATA_EMPTY', "YES");
      } else {
			$this->mrTemplate->AddVar('data_komponen_gaji', 'DATA_EMPTY', "NO");
         $dataKomponen = $data['dataKomponen'];
         for ($i=0; $i<sizeof($dataKomponen); $i++) {
            $no = $i+$data['start'];
            $dataKomponen[$i]['number'] = $no;
            if ($no % 2 != 0) $dataKomponen[$i]['class_name'] = 'table-common-even';
            else $dataKomponen[$i]['class_name'] = '';
			      $dataKomponen[$i]['set_parent'] ='<a href="javascript:void(0)" onclick="addKomponenGajiItem(this, \''.$dataKomponen[$i]['id'].'\',\''.$dataKomponen[$i]['kode'].'\',\''.$dataKomponen[$i]['nama'].'\')" onmouseover="status=\'Set preferences...\';return true" ><img src="images/button-check.gif" alt="Pilih"/></a>';
            $this->mrTemplate->AddVars('data_komponen_gaji_item', $dataKomponen[$i], 'DATA_');
            $this->mrTemplate->parseTemplate('data_Komponen_gaji_item', 'a');	 
         }
      }
	}
}
?>
