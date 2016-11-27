<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_absensi/business/AppAbsensi.class.php';

class ViewAbsensiHarian extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/data_absensi/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_absensi_harian.html');
	}
	
	function ProcessRequest() {
		$Obj = new AppAbsensi();
		if($_POST || isset($_GET['cari'])) {
  			if(isset($_POST['nama'])) {
  				$nama = $_POST['nama'];
  			} elseif(isset($_GET['nama'])) {
  				$nama = Dispatcher::Instance()->Decrypt($_GET['nama']);
  			} else {
  				$nama = '';
  			}
  		}
      
		$totalData = $Obj->GetCountAbsensiHarianTemp($nama);
		$itemViewed = 100;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataAbsensi = $Obj->GetDataAbsensiHarianTemp($startRec, $itemViewed, $nama);

    $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . 
            '&nama=' . Dispatcher::Instance()->Encrypt($nama) .
            '&cari=' . Dispatcher::Instance()->Encrypt(1));

		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);

		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];

		$return['dataAbsensi'] = $dataAbsensi;
		$return['start'] = $startRec+1;
    $return['search']['nama'] = $nama;
    
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('data_absensi', 'rekapAbsensiHarian', 'view', 'html') . "&dataId=" . $this->encDataId);
		$this->mrTemplate->AddVar('content', 'URL_PROSES', Dispatcher::Instance()->GetUrl('data_absensi', 'prosesAbsensiHarian', 'do', 'html'));
		$this->mrTemplate->AddVar('content', 'URL_REKAP', Dispatcher::Instance()->GetUrl('data_absensi', 'rekapAbsensiHarian', 'view', 'html'));
    $this->mrTemplate->AddVar('content', 'TANGGAL', $this->periode2stringEng(date("Y-m-d")));
    if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
		$search = $data['search'];
    $this->mrTemplate->AddVar('content', 'NAMA', $search['nama']);
    $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('data_absensi', 'absensiHarian', 'view', 'html') );
    
    //tampilkan history cuti
		if (empty($data['dataAbsensi'])) {
			$this->mrTemplate->AddVar('data_absensi', 'ABSENSI_EMPTY', 'YES');
		} else {
			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
			$encPage = Dispatcher::Instance()->Encrypt($decPage);
			$this->mrTemplate->AddVar('data_absensi', 'ABSENSI_EMPTY', 'NO');
			$dataAbsensi = $data['dataAbsensi'];

      for ($i=0; $i<sizeof($dataAbsensi); $i++) {
				$no = $i+$data['start'];
				$dataAbsensi[$i]['number'] = $no;
				if ($no % 2 != 0) {
          $dataAbsensi[$i]['class_name'] = 'table-common-even';
        }else{
          $dataAbsensi[$i]['class_name'] = '';
        }
				
				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
				if($i == sizeof($dataAbsensi)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);

				$idEnc = Dispatcher::Instance()->Encrypt($dataAbsensi[$i]['id']);
				
        $dataAbsensi[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('data_absensi', 'deleteRekapAbsensiHarianTemp', 'view', 'html') . '&dataId=' . $idEnc;
        $dataAbsensi[$i]['url_detail'] = Dispatcher::Instance()->GetUrl('data_absensi', 'detailRekapAbsensiHarianTemp', 'view', 'html') . '&dataId=' . $idEnc;
        
				$this->mrTemplate->AddVars('data_absensi_item', $dataAbsensi[$i], 'DATA_');
				$this->mrTemplate->AddVar('data_absensi_item', 'DATA_TGL', $this->periode2string($dataAbsensi[$i]['tgl']));
				
        $this->mrTemplate->parseTemplate('data_absensi_item', 'a');	 
			}   
		}
	}
	
	function periode2stringEng($date) {
	   $bln = array(
	        1  => 'January',
					2  => 'February',
					3  => 'March',
					4  => 'April',
					5  => 'May',
					6  => 'June',
					7  => 'July',
					8  => 'August',
					9  => 'September',
					10 => 'October',
					11 => 'November',
					12 => 'December'					
	               );
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return (int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
	}
	
	function periode2string($date) {
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return $tanggal.'-'.$bulan.'-'.$tahun;
	}
}
?>
