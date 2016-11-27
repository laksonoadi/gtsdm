<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/ref_pph_pegawai_komponen/business/PphPegawaiKomp.class.php';

class ViewPphPegawaiKomp extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
		'module/ref_pph_pegawai_komponen/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_pph_pegawai_komp.html');
	}
	
	function ProcessRequest() {
		$namaObj = new PphPegawaiKomp();
		if($_POST || isset($_GET['cari'])) {
			if(isset($_POST['nama'])) {
				$nama = $_POST['nama'];
				$nip = $_POST['nip'];
				$idTahun = $_POST['periode_tahun'];
			} elseif(isset($_GET['nama'])) {
				$nama = Dispatcher::Instance()->Decrypt($_GET['nama']);
				$nip = Dispatcher::Instance()->Decrypt($_GET['nip']);
				$idTahun = Dispatcher::Instance()->Decrypt($_GET['periode_tahun']);
			} else {
				$nama = '';
				$nip = '';
				$idTahun=date('Y');
			}
		}
		
	//view
		$totalData = $namaObj->getCountKompPeg($nip, $nama, $idTahun);
		$itemViewed = 50;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}

		$dataPph = $namaObj->getDataKompPeg($startRec, $itemViewed, $nip, $nama, $idTahun);
		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . '&nama=' . Dispatcher::Instance()->Encrypt($nama) .
			   '&nip=' . Dispatcher::Instance()->Encrypt($nip) .
         '&cari=' . Dispatcher::Instance()->Encrypt(1) .
         '&periode_tahun=' . Dispatcher::Instance()->Encrypt($idTahun));
        

		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
		
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
	  $return['lang']=$lang;
		
		$year = $namaObj->GetTahun();
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_tahun', 
	     array('periode_tahun', $year, $idTahun, 'none', ''), 
		 Messenger::CurrentRequest);
		 
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];

		$return['dataPph'] = $dataPph;
		$return['start'] = $startRec+1;
		$return['tahun']=$idTahun;

		$return['search']['nama'] = $nama;
		$return['search']['nip'] = $nip;
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
		$search = $data['search'];
		$this->mrTemplate->AddVar('content', 'NIP', $search['nip']);
		$this->mrTemplate->AddVar('content', 'NAMA', $search['nama']);
		$this->mrTemplate->AddVar('content', 'PERIODE_TAHUN', $data['tahun']);
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('ref_pph_pegawai_komponen', 'pphPegawaiKomp', 'view', 'html'));
		$this->mrTemplate->AddVar('content', 'URL_SPT_MASSAL', Dispatcher::Instance()->GetUrl('ref_pph_pegawai_komponen', 'sptMassal', 'print', 'html'));
		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}

		if (empty($data['dataPph'])) {
			$this->mrTemplate->AddVar('data_pph', 'DATA_EMPTY', 'YES');
		} else {
			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
			$encPage = Dispatcher::Instance()->Encrypt($decPage);
			$this->mrTemplate->AddVar('data_pph', 'DATA_EMPTY', 'NO');
			$dataPph = $data['dataPph'];
		
//mulai bikin tombol delete
			$label = "Manajemen Pph";
			$urlDelete = Dispatcher::Instance()->GetUrl('ref_pph_pegawai_komponen', 'deletePphPegawaiKomp', 'do', 'html');
			$urlReturn = Dispatcher::Instance()->GetUrl('ref_pph_pegawai_komponen', 'pphPegawaiKomp', 'view', 'html');
			Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
			$this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
			$this->mrTemplate->AddVar('content', 'SET_ORDER', Dispatcher::Instance()->GetUrl('ref_pph_pegawai_komponen', 'setOrder', 'do', 'html'));

			for ($i=0; $i<sizeof($dataPph); $i++) {
				$no = $i+$data['start'];
				$dataPph[$i]['number'] = $no;
				if ($no % 2 != 0) $dataPph[$i]['class_name'] = 'table-common-even';
				else $dataPph[$i]['class_name'] = '';
				
				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);				
				if($i == sizeof($dataPph)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
				$idEnc = Dispatcher::Instance()->Encrypt($dataPph[$i]['pegawai_id']);
				$idEncPot = Dispatcher::Instance()->Encrypt($dataPph[$i]['pot_id']);
				$nip = Dispatcher::Instance()->Encrypt($dataPph[$i]['nip_pegawai']);
				$nama = Dispatcher::Instance()->Encrypt($dataPph[$i]['nama_pegawai']);
				$periode_tahun = $data['tahun'];
				
				$urlAccept = 'ref_pph_pegawai_komponen|deletePphPegawaiKomp|do|html-cari-'.$cari;
				$urlReturn = 'ref_pph_pegawai_komponen|PphPegawaiKomp|view|html-cari-'.$cari;
				$label = 'Komponen Pph Pegawai';
				$dataPph[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('ref_pph_pegawai_komponen', 'inputPphPegawaiKomp', 'view', 'html') . '&dataId=' . $idEnc. '&potId=' . $idEncPot . '&page=' . $encPage . '&cari='.$search.'&nama='.$nama.'&periode_tahun='.$periode_tahun;
				$dataPph[$i]['url_print'] = Dispatcher::Instance()->GetUrl('ref_pph_pegawai_komponen', 'spt', 'print', 'html') . '&dataId=' . $idEnc. '&nip=' . $nip;
				
				//$dataPph[$i]['komp_pegawai_nominal'] = number_format($dataPph[$i]['komp_pegawai_nominal'], 2, ',', '.');
				$dataPph[$i]['potongan_perbl'] = number_format($dataPph[$i]['potongan_perbl'], 2, ',', '.');
				$dataPph[$i]['nominal_januari'] = number_format($dataPph[$i]['nominal_januari'], 2, ',', '.');
				$dataPph[$i]['nominal_februari'] = number_format($dataPph[$i]['nominal_februari'], 2, ',', '.');
				$dataPph[$i]['nominal_maret'] = number_format($dataPph[$i]['nominal_maret'], 2, ',', '.');
				$dataPph[$i]['nominal_april'] = number_format($dataPph[$i]['nominal_april'], 2, ',', '.');
				$dataPph[$i]['nominal_mei'] = number_format($dataPph[$i]['nominal_mei'], 2, ',', '.');
				$dataPph[$i]['nominal_juni'] = number_format($dataPph[$i]['nominal_juni'], 2, ',', '.');
				$dataPph[$i]['nominal_juli'] = number_format($dataPph[$i]['nominal_juli'], 2, ',', '.');
				$dataPph[$i]['nominal_agustus'] = number_format($dataPph[$i]['nominal_agustus'], 2, ',', '.');
				$dataPph[$i]['nominal_september'] = number_format($dataPph[$i]['nominal_september'], 2, ',', '.');
				$dataPph[$i]['nominal_oktober'] = number_format($dataPph[$i]['nominal_oktober'], 2, ',', '.');
				$dataPph[$i]['nominal_november'] = number_format($dataPph[$i]['nominal_november'], 2, ',', '.');
				$dataPph[$i]['nominal_desember'] = number_format($dataPph[$i]['nominal_desember'], 2, ',', '.');
				
				if ($lang=='eng'){
          $periode = $this->GetBulanEnglish($dataPph[$i]['periode']);
        }else{
          $periode = $this->GetBulanIndonesia($dataPph[$i]['periode']); 
        }

        $dataPph[$i]['periode_bulan'] = $periode['bulan'];
        $dataPph[$i]['periode_tahun'] = $periode['tahun'];   
        $dataPph[$i]['tahun_bulan'] = $data['tahun'].$data['bulan'];
        
				$this->mrTemplate->AddVars('data_pph_item', $dataPph[$i], 'DATA_');
				$this->mrTemplate->parseTemplate('data_pph_item', 'a');	 
			}
		}
	}
}
?>
