<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pak_kumulatif/business/mutasi_pak.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pak_kumulatif/business/mutasi_pak_komponen.class.php';

class ViewPopupKegiatan extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'].'module/mutasi_pak_kumulatif/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_popup_kegiatan.html');
	}
   
    function TemplateBase() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') . 'main/template/');
      $this->SetTemplateFile('document-common-popup.html');
      $this->SetTemplateFile('layout-common-popup.html');
   }
	
	function ProcessRequest() {
		$Obj = new MutasiPak();
		$ObjOto = new MutasiPakKomponen();
	
		if($_POST || isset($_GET['cari'])) {
			if(isset($_POST['nama'])) {
				$nama = $_POST['nama'];
			} elseif(isset($_GET['nama'])) {
				$nama = Dispatcher::Instance()->Decrypt($_GET['nama']);
			} else {
				$nama = '';
			}
		}
		
		$currPage = 1;
		$startRec = 0 ;
		
		$dest = "popup-subcontent";
		
		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType.
		'&nama='.Dispatcher::Instance()->Encrypt($nama).
		'&cari='.Dispatcher::Instance()->Encrypt(1));
		
		if ($_GET['tipe']=='otomatis'){
			$itemViewed = sizeof($dataKegiatan);
		} else {
			$itemViewed = 15;
		}
		
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		
		if ($_GET['tipe']=='otomatis'){
			$dataKegiatan = $ObjOto->GetKegiatanOtomatis($_GET['pegId']);
			$totalData = sizeof($dataKegiatan);
		} else {
			$totalData = $Obj->GetCountDataKegiatan($nama);
			$dataKegiatan = $Obj->getDataKegiatan($startRec,$itemViewed,$nama);
		}
		
		Messenger::Instance()->SendToComponent('paging2', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData,$url,$currPage,$dest), Messenger::CurrentRequest);
		
		
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
				
		$return['dataKegiatan'] = $dataKegiatan;
		$return['start'] = $startRec+1;
		$return['search']['nama'] = $nama;
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$search = $data['search'];
		$this->mrTemplate->AddVar('content', 'NAMA', $search['nama']);
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif', 'popupKegiatan', 'view', 'html').'&tipe='.$_GET['tipe'].'&pegid='.$_GET['pegId']);
		
		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
		
		$cariData = $data['dataKegiatan'];
		if ($_GET['tipe']=='otomatis'){
			$this->mrTemplate->AddVar('otomatis', 'IS_OTOMATIS', "YES");
			if(empty($cariData)) {
				$this->mrTemplate->AddVar('data_kegiatan_otomatis', 'DATA_EMPTY', "YES");
			} else {
				$this->mrTemplate->AddVar('data_kegiatan_otomatis', 'DATA_EMPTY', "NO");
				for ($i=0; $i<sizeof($cariData); $i++) {
					$no = $i+$data['start'];
					$cariData[$i]['number'] = $no;
					if ($no % 2 != 0) $cariData[$i]['class_name'] = 'table-common-even';
					else $cariData[$i]['class_name'] = '';
					$cariData[$i]['data'] = $data['dataKeg'];
				    $this->mrTemplate->AddVars('data_kegiatan_item_otomatis', $cariData[$i], 'DATA_');
					$this->mrTemplate->parseTemplate('data_kegiatan_item_otomatis', 'a');	 
				}
			}
		}else{
			$this->mrTemplate->AddVar('otomatis', 'IS_OTOMATIS', "NO");
			if(empty($cariData)) {
				$this->mrTemplate->AddVar('data_kegiatan', 'DATA_EMPTY', "YES");
			} else {
				$this->mrTemplate->AddVar('data_kegiatan', 'DATA_EMPTY', "NO");
				for ($i=0; $i<sizeof($cariData); $i++) {
					$no = $i+$data['start'];
					$cariData[$i]['number'] = $no;
					if ($no % 2 != 0) $cariData[$i]['class_name'] = 'table-common-even';
					else $cariData[$i]['class_name'] = '';
					$cariData[$i]['data'] = $data['dataKeg'];
				    $this->mrTemplate->AddVars('data_kegiatan_item', $cariData[$i], 'DATA_');
					$this->mrTemplate->parseTemplate('data_kegiatan_item', 'a');	 
				}
			}
		}
	}
}
?>
