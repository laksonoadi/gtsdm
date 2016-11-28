<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/business/AppHistoryGajiPegawai.class.php';

class ViewHistoryGajiPegawai extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/gaji_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_history_gaji_pegawai.html');
	}
	
	function ProcessRequest() {
		$Obj = new AppHistoryGajiPegawai();
		if($_POST || isset($_GET['cari'])) {
			if(isset($_POST['tampilkan'])) {
				$tampilkan = $_POST['tampilkan'];
				$periode_mon = $_POST['periode_bulan'];
				$periode_year = $_POST['periode_tahun'];
			} elseif(isset($_GET['tampilkan'])) {
				$tampilkan = Dispatcher::Instance()->Decrypt($_GET['tampilkan']);
				$periode_mon = Dispatcher::Instance()->Decrypt($_GET['periode_bulan']);
				$periode_year = Dispatcher::Instance()->Decrypt($_GET['periode_tahun']);
			} else {
				$tampilkan = 'satu';
				$periode_mon = date('m');
				$periode_year = date('Y');
			}
		}
		$this->decDataId = Dispatcher::Instance()->Decrypt($_GET['dataId']);
		$this->encDataId = Dispatcher::Instance()->Encrypt($this->decDataId);
    
    $dataPegawai = $Obj->GetDataById($this->decDataId);
	//view
      
		$totalData = $Obj->GetCountData($this->decDataId, $tampilkan, $periode_year, $periode_mon);
		$itemViewed = 20;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataHistory = $Obj->getData($startRec, $itemViewed, $this->decDataId, $tampilkan, $periode_year, $periode_mon);
    
      #menyesuaikan paging nya dari data
      for($i=0;$i<sizeof($dataHistory);$i++) {
         #$dataHistory[$i]['total'] = base64_decode($dataHistory[$i]['total']);
         $dataHistory[$i]['total'] = $dataHistory[$i]['total'];
         $kolom[$dataHistory[$i]['kolom']][$dataHistory[$i]['periode']] = $dataHistory[$i]['nominal'];
         $newdata2[$dataHistory[$i]['periode']][$dataHistory[$i]['kolom']] = $dataHistory[$i]['nominal'];
         $newdata2[$dataHistory[$i]['periode']]['total'] = $dataHistory[$i]['total'];
         $newdata2[$dataHistory[$i]['periode']]['id'] = $dataHistory[$i]['id'];
         $newdata2[$dataHistory[$i]['periode']]['plain'] = $dataHistory[$i]['plain'];
      }
      $totalData = sizeof($newdata2);
    
    //print_r($newdata2);
		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . '&dataId=' . $this->encDataId . '&tampilkan=' . Dispatcher::Instance()->Encrypt($tampilkan) . '&periode_bulan=' . Dispatcher::Instance()->Encrypt($periode_mon) . '&periode_tahun=' . Dispatcher::Instance()->Encrypt($periode_year) . '&cari=' . Dispatcher::Instance()->Encrypt(1));

		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
  
    if($tampilkan == "semua") {
       $status = array('disabled="disabled"');
    } else {
       $status = array();
    }
    
    $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    if ($lang=='eng'){
      $bulan = $Obj->GetBulanEng();
    }else{
      $bulan = $Obj->GetBulan(); 
    }
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_bulan', 
	     array('periode_bulan', $bulan, $periode_mon, 'none', $status), 
		 Messenger::CurrentRequest);
		
		$year = $Obj->GetTahun();
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_tahun', 
	     array('periode_tahun', $year, $periode_year, 'none', ''), 
		 Messenger::CurrentRequest);

		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];

		$return['dataHistory'] = $dataHistory;
		$return['search']['tampilkan'] = $tampilkan;
		$return['start'] = $startRec+1;
    $return['periode'] = $periode;
    $return['dataPegawai'] = $dataPegawai;
    $return['lang'] = $lang;
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
    $search = $data['search'];
    if($search['tampilkan'] == "semua") {
	   $this->mrTemplate->AddVar('content', 'SEMUA_CHECKED', 'checked="checked"');
    } else {
	   $this->mrTemplate->AddVar('content', 'SATU_CHECKED', 'checked="checked"');
    }
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('gaji_pegawai', 'historyGajiPegawai', 'view', 'html') . "&dataId=" . $this->encDataId);
		$this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('gaji_pegawai', 'gajiPegawai', 'view', 'html'));
		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
		
		$dataPegawai = $data['dataPegawai'];
		$this->mrTemplate->AddVar('content', 'NIP', $dataPegawai['nip']);
    $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai['nama']);
    $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai['alamat']);
    if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai['foto']) | empty($dataPegawai['foto'])) { 
	 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
  	}else{
      $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai['foto']);
    }

		if (empty($data['dataHistory'])) {
			$this->mrTemplate->AddVar('data_history', 'HISTORY_EMPTY', 'YES');
		} else {
			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
			$encPage = Dispatcher::Instance()->Encrypt($decPage);
			$this->mrTemplate->AddVar('data_history', 'HISTORY_EMPTY', 'NO');
			$dataHistory = $data['dataHistory'];
         
       for($i=0;$i<sizeof($dataHistory);$i++) {
          $kolom[$dataHistory[$i]['kolom']][$dataHistory[$i]['periode']] = $dataHistory[$i]['nominal'];
          $newdata2[$dataHistory[$i]['periode']][$dataHistory[$i]['kolom']] = $dataHistory[$i]['nominal'];
          $newdata2[$dataHistory[$i]['periode']]['total'] = $dataHistory[$i]['total'];
          $newdata2[$dataHistory[$i]['periode']]['plain'] = $dataHistory[$i]['plain'];
       }
       
       //membuat kolom dinamis sesuai formula
       $lang=$data['lang'];
       foreach($kolom as $kunci => $value) {
        if($kunci==NULL){
    	    if ($lang=='eng'){
            $kunci="Basic Salary";
          }else{
            $kunci="Gaji Pokok"; 
          }
        }
        $kol['nama'] = $kunci;
        //print_r($kunci);
  			$this->mrTemplate->AddVars('kolom_item', $kol, 'KOLOM_');
  			$this->mrTemplate->parseTemplate('kolom_item', 'a');
       }
       //sampe sini
       
       //membuat baris dinamis utk nominalnya
       $no = 1;
       foreach($newdata2 as $kunci => $nominal) {
        // $jumlah = 0;menurun
        //$this->mrTemplate->clearAttribute('data_nominal_item', 'loop');
        $this->mrTemplate->clearTemplate('data_nominal_item');
        foreach($kolom as $kunci_kolom => $value_kolom) {
        //print_r($kunci_kolom);
          foreach($nominal as $kunci_nominal => $value_nominal) {
          //print_r($kunci_nominal);
            if($kunci_nominal == $kunci_kolom) {
            //$jumlah += $value_nominal;
            //echo "test<br />";
            $this->mrTemplate->AddVar('data_nominal_item', 'DATA_NOMINAL', number_format($value_nominal, 2, ',', '.'));
            break;
            }
          }
          $this->mrTemplate->parseTemplate('data_nominal_item', 'a');
        }
        
        if ($lang=='eng'){
          $kol['periode'] = $this->periode2stringEng($kunci);
        }else{
          $kol['periode'] = $this->periode2string($kunci); 
        }
        
        $kol['number'] = $no;
        $kol['total'] = number_format($nominal['total'], 2, ',', '.');
        $kol['plain'] = number_format($nominal['plain'], 2, ',', '.');
        if($no % 2 == 0) {
          $kol['class_name'] = "";
        } else {
          $kol['class_name'] = "table-common-even1";
        }
        $kol['url_cetak'] = Dispatcher::Instance()->GetUrl('gaji_pegawai', 'CetakSlipGajiPegawai', 'view', 'html') . '&dataId=' . $this->encDataId . '&periode=' . Dispatcher::Instance()->Encrypt($kunci) . '&cetak=' . Dispatcher::Instance()->Encrypt('yes');
        //$kol['lain'] = number_format($lain['nominal'], 2, ',', '.');
        $this->mrTemplate->AddVars('data_history_item', $kol, 'DATA_');
        $this->mrTemplate->parseTemplate('data_history_item', 'a');
        $no++;
       }  
         
		}
	}
	
	function periode2string($date) {
	   $bln = array(
	        1  => 'Januari',
					2  => 'Februari',
					3  => 'Maret',
					4  => 'April',
					5  => 'Mei',
					6  => 'Juni',
					7  => 'Juli',
					8  => 'Agustus',
					9  => 'September',
					10 => 'Oktober',
					11 => 'November',
					12 => 'Desember'					
	               );
	   $bulan = substr($date,-2);
	   $tahun = substr($date,0,4);
	   return $bln[(int) $bulan].' '.$tahun;
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
	   $bulan = substr($date,-2);
	   $tahun = substr($date,0,4);
	   return $bln[(int) $bulan].' '.$tahun;
	}
}
?>
