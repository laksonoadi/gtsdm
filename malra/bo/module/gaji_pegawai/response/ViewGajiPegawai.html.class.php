<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/business/AppGajiPegawai.class.php';

class ViewGajiPegawai extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/gaji_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_gaji_pegawai.html');
	}
	
	function ProcessRequest() {
		
		$Obj = new AppGajiPegawai();
		if($_POST || isset($_GET['cari'])) {
			if(isset($_POST['nip_nama'])) {
				$nip_nama = $_POST['nip_nama'];
				$satkerja = $_POST['satkerja'];
				$satkerja_label = $_POST['satkerja_label'];
				$jenis = $_POST['jenis'];
				$idBulan = $_POST['periode_bulan'];
				$idTahun = $_POST['periode_tahun'];
			} elseif(isset($_GET['nip_nama'])) {
				$nip_nama = Dispatcher::Instance()->Decrypt($_GET['nip_nama']);
				$satkerja = Dispatcher::Instance()->Decrypt($_GET['satkerja']);
				$satkerja_label = Dispatcher::Instance()->Decrypt($_GET['satkerja_label']);
				$jenis = Dispatcher::Instance()->Decrypt($_GET['jenis']);
				$idBulan = Dispatcher::Instance()->Decrypt($_GET['periode_bulan']);
				$idTahun = Dispatcher::Instance()->Decrypt($_GET['periode_tahun']);
			} else {
				$nip_nama = '';
				$satkerja = '';
				$satkerja_label = '';
				$jenis = '';
				$idBulan=date('m');
				$idTahun=date('Y');
			}
		}
	//view
	  //print_r($idBulan);
		$totalData = $Obj->GetCountData($nip_nama, $satkerja, $jenis);
		$itemViewed = 150;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		
		$dataGajiPegawai = $Obj->getData($startRec, $itemViewed, $nip_nama, $satkerja, $jenis, $idBulan, $idTahun);
		
		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . '&gaji_pegawai=' . Dispatcher::Instance()->Encrypt($gaji_pegawai) . 
      '&nip_nama=' . Dispatcher::Instance()->Encrypt($nip_nama).
      '&satkerja=' . Dispatcher::Instance()->Encrypt($satkerja).
      '&satkerja_label=' . Dispatcher::Instance()->Encrypt($satkerja_label).
      '&jenis=' . Dispatcher::Instance()->Encrypt($jenis).
      '&cari=' . Dispatcher::Instance()->Encrypt(1).
      '&periode_bulan=' . Dispatcher::Instance()->Encrypt($idBulan).
      '&periode_tahun=' . Dispatcher::Instance()->Encrypt($idTahun))
      ;

		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
		
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
	  $return['lang']=$lang;
     if ($lang=='eng'){
        $bulan = $Obj->GetBulanEng();
     }else{
        $bulan = $Obj->GetBulan();  
     }
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_bulan', 
	     array('periode_bulan', $bulan, $idBulan, 'none', ''), 
		 Messenger::CurrentRequest);
		
		$year = $Obj->GetTahun();
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_tahun', 
	     array('periode_tahun', $year, $idTahun, 'none', ''), 
		 Messenger::CurrentRequest);
		 
	 //startof kombo StatusGaji
    $arrJenis[0]['id'] = "sudah";
    $arrJenis[1]['id'] = "belum";
    
    if ($lang=='eng'){
        $arrJenis[2]['id'] = "ns";
        $arrJenis[0]['name'] = "Already paid"; 
        $arrJenis[1]['name'] = "Unpaid";
        $arrJenis[2]['name'] = "Not specified";	
     }else{
        $arrJenis[0]['name'] = "Sudah Dibayar"; 
        $arrJenis[1]['name'] = "Belum Dibayar";
        $arrJenis[2]['name'] = "Belum Ditentukan";
     }
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis', array('jenis', $arrJenis, $jenis, 'true', ' style="width:130px;" '), Messenger::CurrentRequest);

	  //endof kombo StatusGaji
		

		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
      //total pegawai aktif
		$total_pegawai_aktif = $Obj->getTotalPegawaiAktif();
      //total
		$total_seluruh = $Obj->getTotalSeluruh($idBulan,$idTahun);

		$return['dataGajiPegawai'] = $dataGajiPegawai;
		$return['total_pegawai_aktif'] = $total_pegawai_aktif;
		$return['total_seluruh'] = $total_seluruh;
		$return['tahun_periode']=$idTahun.'-'.$idBulan;
		$return['tahun']=$idTahun;
		$return['bulan']=$idBulan;
		$return['start'] = $startRec+1;
      
      $return['search']['nip_nama'] = $nip_nama;
      $return['search']['satkerja'] = $satkerja;
      $return['search']['satkerja_label'] = $satkerja_label;
		return $return;
	}
	
	function GetBulanIndonesia($tanggal) {
      $blnarr=array();
	   $blnarr[1]="Januari";
	   $blnarr[2]="Februari";
	   $blnarr[3]="Maret";
	   $blnarr[4]="April";
	   $blnarr[5]="Mei";
	   $blnarr[6]="Juni";
	   $blnarr[7]="Juli";
	   $blnarr[9]="September";
	   $blnarr[8]="Agustus";
	   $blnarr[10]="Oktober";
	   $blnarr[11]="November";
	   $blnarr[12]="Desember";
	
	   $tanggal=explode("-",$tanggal);	   
      $ret['bulan'] = $blnarr[intval($tanggal[1])];
      $ret['tahun'] = $tanggal[0];
	   return $ret;
   }
   
   function GetBulanEnglish($tanggal) {
      $blnarr=array();
	   $blnarr[1]="January";
	   $blnarr[2]="February";
	   $blnarr[3]="March";
	   $blnarr[4]="April";
	   $blnarr[5]="May";
	   $blnarr[6]="June";
	   $blnarr[7]="July";
	   $blnarr[9]="September";
	   $blnarr[8]="August";
	   $blnarr[10]="October";
	   $blnarr[11]="November";
	   $blnarr[12]="December";
	
	   $tanggal=explode("-",$tanggal);	   
      $ret['bulan'] = $blnarr[intval($tanggal[1])];
      $ret['tahun'] = $tanggal[0];
	   return $ret;
   }

	function ParseTemplate($data = NULL) {
	if($_POST || isset($_GET['cari'])) {
			if(isset($_POST['nip_nama'])) {
				$nip_nama = $_POST['nip_nama'];
				$satkerja = $_POST['satkerja'];
				$satkerja_label = $_POST['satkerja_label'];
				$jenis = $_POST['jenis'];
				$idBulan = $_POST['periode_bulan'];
				$idTahun = $_POST['periode_tahun'];
			} elseif(isset($_GET['nip_nama'])) {
				$nip_nama = Dispatcher::Instance()->Decrypt($_GET['nip_nama']);
				$satkerja = Dispatcher::Instance()->Decrypt($_GET['satkerja']);
				$satkerja_label = Dispatcher::Instance()->Decrypt($_GET['satkerja_label']);
				$jenis = Dispatcher::Instance()->Decrypt($_GET['jenis']);
				$idBulan = Dispatcher::Instance()->Decrypt($_GET['periode_bulan']);
				$idTahun = Dispatcher::Instance()->Decrypt($_GET['periode_tahun']);
			} else {
				$nip_nama = '';
				$satkerja = '';
				$satkerja_label = '';
				$jenis = '';
				$idBulan=date('m');
				$idTahun=date('Y');
			}
		}
		$search = $data['search'];
		$this->mrTemplate->AddVar('content', 'NIP_NAMA', $search['nip_nama']);
	
		$this->mrTemplate->AddVar('content', 'UNITKERJA_LABEL', $search['satkerja_label']);
		$this->mrTemplate->AddVar('content', 'URL_IMPORT', Dispatcher::Instance()->GetUrl('gaji_pegawai', 'importGajiPegawai', 'view', 'html'));
		$this->mrTemplate->AddVar('content', 'URL_REKAP', Dispatcher::Instance()->GetUrl('rekap_gaji_pegawai', 'rekapGajiPegawai', 'view', 'html'));
      
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('gaji_pegawai', 'gajiPegawai', 'view', 'html'));
		$this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('gaji_pegawai', 'laporanGajiPegawai', 'view', 'xls').'&nip_nama=' .$nip_nama.'&satkerja=' . $satkerja.'&jenis=' . $jenis.'&periode_bulan=' . $idBulan.'&periode_tahun=' . $idTahun);
		$this->mrTemplate->AddVar('content', 'URL_UPDATE', Dispatcher::Instance()->GetUrl('gaji_pegawai', 'addGajiPegawai', 'do', 'html'));
		$this->mrTemplate->AddVar('content', 'URL_POPUP_UNITKERJA', Dispatcher::Instance()->GetUrl('gaji_pegawai', 'popupUnitkerja', 'view', 'html'));
		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}

		if (empty($data['dataGajiPegawai'])) {
			$this->mrTemplate->AddVar('data_gaji_pegawai', 'GAJIPEGAWAI_EMPTY', 'YES');
		} else {
			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
			$encPage = Dispatcher::Instance()->Encrypt($decPage);
			$this->mrTemplate->AddVar('data_gaji_pegawai', 'GAJIPEGAWAI_EMPTY', 'NO');
			$dataGajiPegawai = $data['dataGajiPegawai'];

		//mulai bikin tombol delete
      $lang=$data['lang'];
  	   if ($lang=='eng'){
          $label = "Employee Salary";
       }else{
          $label = "Gaji Pegawai";  
       }
			
			$urlDelete = Dispatcher::Instance()->GetUrl('gaji_pegawai', 'deleteGajiPegawai', 'do', 'html');
			$urlReturn = Dispatcher::Instance()->GetUrl('gaji_pegawai', 'gajiPegawai', 'view', 'html');
			Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
			$this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
			$total=0;
			for ($i=0; $i<sizeof($dataGajiPegawai); $i++) {
				$no = $i+$data['start'];
				$dataGajiPegawai[$i]['number'] = $no;
				if ($no % 2 != 0) {
               $dataGajiPegawai[$i]['class_name'] = 'table-common-even';
            }
				else {
               $dataGajiPegawai[$i]['class_name'] = '';
            }
			if($dataGajiPegawai[$i]['is_aktif'] == "Tidak") {
               $dataGajiPegawai[$i]['class_name'] = 'table-common-even1';
            } else {
               $total += $dataGajiPegawai[$i]['gaji'];
            }
				
				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
				if($i == sizeof($dataGajiPegawai)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);

				$idEnc = Dispatcher::Instance()->Encrypt($dataGajiPegawai[$i]['id']);
                #$dataGajiPegawai[$i]['gaji'] =  base64_decode($dataGajiPegawai[$i]['gaji']);
				if ($dataGajiPegawai[$i]['gaji']<0){
					$dataGajiPegawai[$i]['hutang'] = number_format(-1*$dataGajiPegawai[$i]['gaji'], 2, ',', '.');
					$dataGajiPegawai[$i]['gaji'] = '-';
				}else{
					$dataGajiPegawai[$i]['hutang'] = '-';
					$dataGajiPegawai[$i]['gaji'] = number_format($dataGajiPegawai[$i]['gaji'], 2, ',', '.');
				}
				
                $dataGajiPegawai[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('gaji_pegawai', 'inputGajiPegawai', 'view', 'html') . '&dataId=' . $idEnc;// . '&page=' . $encPage . '&cari='.$cari;
				$dataGajiPegawai[$i]['url_detil'] = Dispatcher::Instance()->GetUrl('gaji_pegawai', 'detilGajiPegawai', 'view', 'html') . '&dataId=' . $idEnc;// . '&page=' . $encPage . '&cari='.$cari;
				$dataGajiPegawai[$i]['url_history'] = Dispatcher::Instance()->GetUrl('gaji_pegawai', 'historyGajiPegawai', 'view', 'html') . '&dataId=' . $idEnc;// . '&page=' . $encPage . '&cari='.$cari;
				
				if (($dataGajiPegawai[$i]['is_aktif'] == "Tidak")||($dataGajiPegawai[$i]['is_aktif'] == "")){
					$dataGajiPegawai[$i]['url_payrol'] = '';// . '&page=' . $encPage . '&cari='.$cari;
					$dataGajiPegawai[$i]['hidden'] = 'none';
				}elseif ($dataGajiPegawai[$i]['status']==0){
					$dataGajiPegawai[$i]['url_payrol'] = Dispatcher::Instance()->GetUrl('gaji_pegawai', 'payrollPegawai', 'view', 'html') . '&dataId=' . $idEnc.'&periode_bulan='.$data['bulan'].'&periode_tahun='.$data['tahun'];// . '&page=' . $encPage . '&cari='.$cari;
					$dataGajiPegawai[$i]['url_payrol'] = '<a class="xhr dest_subcontent-element" href="'.$dataGajiPegawai[$i]['url_payrol'].'" title="Hitung Payroll"><img src="images/msg_new.gif" alt="Hitung Payroll"/></a>';
				}else{
					$dataGajiPegawai[$i]['url_payrol'] = '';
					$dataGajiPegawai[$i]['hidden'] = 'none';
				}
        
        if ($lang=='eng'){
          $periode = $this->GetBulanEnglish($data['tahun_periode']);
        }else{
          $periode = $this->GetBulanIndonesia($data['tahun_periode']); 
        }
				
            $dataGajiPegawai[$i]['periode_bulan'] = $periode['bulan'];
            $dataGajiPegawai[$i]['periode_tahun'] = $periode['tahun'];
        
        $dataGajiPegawai[$i]['tahun_bulan'] = $data['tahun'].$data['bulan'];
        
        if($dataGajiPegawai[$i]['status'] == ""){
          $dataGajiPegawai[$i]['status'] = "";
        }else{
          if($dataGajiPegawai[$i]['status'] == 1){
            $dataGajiPegawai[$i]['status'] = "Sudah";
          }elseif($dataGajiPegawai[$i]['status'] == 0){
            $dataGajiPegawai[$i]['status'] = "Belum";
          }
        }
        
				$this->mrTemplate->AddVars('data_gaji_pegawai_item', $dataGajiPegawai[$i], 'GAJIPEGAWAI_');
				$this->mrTemplate->parseTemplate('data_gaji_pegawai_item', 'a');	 
			}

         $this->mrTemplate->AddVar('content', 'TOTAL_PEGAWAI_AKTIF', number_format($data['total_pegawai_aktif'], 0, '', '.'));
         $this->mrTemplate->AddVar('content', 'TOTAL', number_format($total, 2, ',', '.'));
         $this->mrTemplate->AddVar('content', 'TOTAL_SELURUH', base64_decode(number_format($data['total_seluruh'], 2, ',', '.')));
		}
	}
}
?>
