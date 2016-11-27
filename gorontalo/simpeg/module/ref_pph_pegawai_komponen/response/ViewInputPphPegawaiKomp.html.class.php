<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/ref_pph_pegawai_komponen/business/PphPegawaiKomp.class.php';

class ViewInputPphPegawaiKomp extends HtmlResponse {
	var $Data;
	var $Pesan;
	
	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') .
		'module/ref_pph_pegawai_komponen/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('input_pph_pegawai_komp.html');
	}
	
	function ProcessRequest() {
		if(isset($_GET['periode_bulan'])){  
      $periode_mon = $_GET['periode_bulan'];
    } else {
      $periode_mon = date('m');
    }
    if(isset($_GET['periode_tahun'])){  
      $periode_year = $_GET['periode_tahun'];
    } else {
      $periode_year = date('Y');
    }
    
    $idDec = Dispatcher::Instance()->Decrypt($_REQUEST['dataId']);
    $idPot = Dispatcher::Instance()->Decrypt($_REQUEST['potId']);
		$nama = Dispatcher::Instance()->Decrypt($_REQUEST['nama']);
		$pphObj = new PphPegawaiKomp();
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->Data = $msg[0][0];
		$this->css = $msg[0][2];

    $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    $return['lang'] = $lang;
    if ($lang=='eng'){
      $bulan = $pphObj->GetBulanEng();
    }else{
      $bulan = $pphObj->GetBulan();
    }
    
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_bulan', 
    array('periode_bulan', $bulan, $periode_mon, 'none', 'onChange="this.form.update_form()"'),Messenger::CurrentRequest);
    
    $year = $pphObj->GetTahun();
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_tahun', 
    array('periode_tahun', $year, $periode_year, 'none', 'onChange="this.form.update_form()"'),Messenger::CurrentRequest);
    
		$dataPph = $pphObj->GetDataKompPegById($idDec,$periode_mon,$periode_year);                                                                              
		$dataRincian = $pphObj->GetDataRincian($idDec,$periode_mon,$periode_year);
		$dataPegPot = $pphObj->GetDataPegPot($idDec,$periode_mon,$periode_year);
		$dataPegawai = $pphObj->GetDataDetailPegawai($idDec);
		
    $idPeg = $dataPegawai[0]['id'];
		$jnskelPeg = $dataPegawai[0]['kelamin'];
		$dataPtkp = $pphObj->GetDataKompPtkpByPegId($idPeg,$jnskelPeg);
    
    $dataMasaKerja = $pphObj->GetBulanMasaKerjaPerTahun($idDec);
		$comboKomponen = $pphObj->GetComboKomponen(); //combobox untuk komponen
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'formula', 
        array('formula',$comboKomponen,$idKomp,'false',''), Messenger::CurrentRequest);

    $return['dataPegawai'] = $dataPegawai;
    $return['dataMasaKerja'] = $dataMasaKerja;
		$return['decDataId'] = $idDec;
		$return['potId'] = $idPot;
		$return['nama'] = $nama;
		$return['dataPph'] = $dataPph;
		$return['dataPtkp'] = $dataPtkp;
		$return['dataRincian']=$dataRincian;
		$return['dataPegPot']=$dataPegPot;
		$return['periode_tahun']=$periode_year;
		$return['periode_bulan']=$periode_mon;
		return $return;
	}
   
	function ParseTemplate($data = NULL) {
		if ($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
		$dataPph = $data['dataPph'];
		$dataPegawai = $data['dataPegawai'];
		$dataMasaKerja = $data['dataMasaKerja'];

		$this->mrTemplate->AddVar('content', 'NAMA_PEGAWAI', Dispatcher::Instance()->Decrypt($_GET['nama']));
		$this->mrTemplate->AddVar('content', 'STATUSNPWP', $dataPegawai[0]['statusnpwp']);
		if($dataPegawai[0]['kelamin'] == 'L'){
		  $dataPegawai[0]['kelamin'] = 'Male';
		} elseif($dataPegawai[0]['kelamin'] == 'P') {
      $dataPegawai[0]['kelamin'] = 'Female';
    }
    
    if($dataPegawai[0]['statusnpwp'] == 'Ya'){
		  $dataPegawai[0]['statusnpwp'] = 'Yes';
		} elseif($dataPegawai[0]['statusnpwp'] == 'Tidak') {
      $dataPegawai[0]['statusnpwp'] = 'No';
    }
    $this->mrTemplate->AddVar('content', 'JNSKEL_PEGAWAI', $dataPegawai[0]['kelamin']);
    $this->mrTemplate->AddVar('content', 'NPWP_PEGAWAI', $dataPegawai[0]['npwp']);
    $this->mrTemplate->AddVar('content', 'STATUSNPWP_PEGAWAI', $dataPegawai[0]['statusnpwp']);
		
		$this->mrTemplate->AddVar('content', 'ID_PEGAWAI', empty($dataPph[0]['id_pegawai'])?$this->Data['id_pegawai']:$dataPph[0]['id_pegawai']);
		
		$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('ref_pph_pegawai_komponen', 'addPphPegawaiKomp', 'do', 'html') . "&dataId=" . Dispatcher::Instance()->Encrypt($data['decDataId']));
		$this->mrTemplate->AddVar('content', 'URL_UPDATE', Dispatcher::Instance()->GetUrl('ref_pph_pegawai_komponen', 'inputPphPegawaiKomp', 'view', 'html').'&dataId='.$data['decDataId'].'&potId='.$data['potId'].'&page=&cari=&nama='.$data['nama']);
    $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('ref_pph_pegawai_komponen', 'pphPegawaiKomp', 'view', 'html'));

		$this->mrTemplate->AddVar('content', 'DATAID', Dispatcher::Instance()->Decrypt($_GET['dataId']));
		$this->mrTemplate->AddVar('content', 'KELAMIN', $dataPegawai[0]['kelamin']);
		$this->mrTemplate->AddVar('content', 'MASA_KERJA', $dataMasaKerja . ' months');
		$this->mrTemplate->AddVar('content', 'POTID', $data['dataPegPot'][0]['pot_id']);
		$this->mrTemplate->AddVar('content', 'PAGE', Dispatcher::Instance()->Decrypt($_GET['page'])); 
		if($data['lang'] == 'eng'){
		  if($data['periode_bulan'] == '01'){ $data['periode_bulan'] = "January"; }
		  if($data['periode_bulan'] == '02'){ $data['periode_bulan'] = "February"; }
		  if($data['periode_bulan'] == '03'){ $data['periode_bulan'] = "March"; }
		  if($data['periode_bulan'] == '04'){ $data['periode_bulan'] = "April"; }
		  if($data['periode_bulan'] == '05'){ $data['periode_bulan'] = "May"; }
		  if($data['periode_bulan'] == '06'){ $data['periode_bulan'] = "June"; }
		  if($data['periode_bulan'] == '07'){ $data['periode_bulan'] = "July"; }
		  if($data['periode_bulan'] == '08'){ $data['periode_bulan'] = "August"; }
		  if($data['periode_bulan'] == '09'){ $data['periode_bulan'] = "September"; }
		  if($data['periode_bulan'] == '10'){ $data['periode_bulan'] = "October"; }
		  if($data['periode_bulan'] == '11'){ $data['periode_bulan'] = "November"; }
		  if($data['periode_bulan'] == '12'){ $data['periode_bulan'] = "December"; }
		}elseif($data['lang'] == 'ind'){
      if($data['periode_bulan'] == '01'){ $data['periode_bulan'] = "Januari"; }
		  if($data['periode_bulan'] == '02'){ $data['periode_bulan'] = "Februari"; }
		  if($data['periode_bulan'] == '03'){ $data['periode_bulan'] = "Maret"; }
		  if($data['periode_bulan'] == '04'){ $data['periode_bulan'] = "April"; }
		  if($data['periode_bulan'] == '05'){ $data['periode_bulan'] = "Mei"; }
		  if($data['periode_bulan'] == '06'){ $data['periode_bulan'] = "Juni"; }
		  if($data['periode_bulan'] == '07'){ $data['periode_bulan'] = "Juli"; }
		  if($data['periode_bulan'] == '08'){ $data['periode_bulan'] = "Agustus"; }
		  if($data['periode_bulan'] == '09'){ $data['periode_bulan'] = "September"; }
		  if($data['periode_bulan'] == '10'){ $data['periode_bulan'] = "Oktober"; }
		  if($data['periode_bulan'] == '11'){ $data['periode_bulan'] = "November"; }
		  if($data['periode_bulan'] == '12'){ $data['periode_bulan'] = "Desember"; }
    }
		$this->mrTemplate->AddVar('content', 'PERIODE_BULAN', $data['periode_bulan']);
		$this->mrTemplate->AddVar('content', 'PERIODE_TAHUN', $data['periode_tahun']);
    
    if($data['dataPegPot'][0]['potongan_perbl_no_npwp'] == NULL){
      $this->mrTemplate->AddVar('content', 'STATUS_NPWP', "Yes");
    }else{
      $this->mrTemplate->AddVar('content', 'STATUS_NPWP', "No");
    }
    		
    if($data['dataPegPot'][0]['potongan_perbl_no_npwp'] == NULL){
		  if($dataPegawai[0]['jenis'] != 1){
        $potPerth = $data['dataPegPot'][0]['potongan_perbl']*($dataMasaKerja);
  		} else {
        $potPerth = $data['dataPegPot'][0]['potongan_perbl']*12;
      }
		  $this->mrTemplate->AddVar('status_npwp', 'STATUS', 'YES');
		  $potPerbl=number_format($data['dataPegPot'][0]['potongan_perbl'], 2, ',', '.');
      $potPerth=number_format($potPerth, 2, ',', '.');
		  $this->mrTemplate->AddVar('nominal_pajak_dengan_npwp', 'MASA_KERJA', $dataMasaKerja . ' months');
      $this->mrTemplate->AddVar('nominal_pajak_dengan_npwp', 'POT_PERBL', $potPerbl);
		  $this->mrTemplate->AddVar('nominal_pajak_dengan_npwp', 'POT_PERTH', $potPerth);
		}else{
      $this->mrTemplate->AddVar('status_npwp', 'STATUS', 'NO');
      if($dataPegawai[0]['jenis'] != 1){
        $potPerth = ($data['dataPegPot'][0]['potongan_perbl'] + $data['dataPegPot'][0]['potongan_perbl_no_npwp'])*($dataMasaKerja);
  		} else {
        $potPerth = ($data['dataPegPot'][0]['potongan_perbl'] + $data['dataPegPot'][0]['potongan_perbl_no_npwp'])*12;
      }
      $potPerblTotal = ($data['dataPegPot'][0]['potongan_perbl'] + $data['dataPegPot'][0]['potongan_perbl_no_npwp']);
      $potPerbl=number_format($data['dataPegPot'][0]['potongan_perbl'], 2, ',', '.');
      $potPerblNoNPWP=number_format($data['dataPegPot'][0]['potongan_perbl_no_npwp'], 2, ',', '.'); 
      $potPerblTotal=number_format($potPerblTotal, 2, ',', '.');
      $potPerth=number_format($potPerth, 2, ',', '.');
      $this->mrTemplate->AddVar('nominal_pajak_tanpa_npwp', 'MASA_KERJA', $dataMasaKerja . ' months');
      $this->mrTemplate->AddVar('nominal_pajak_tanpa_npwp', 'POT_PERBL', $potPerbl);
      $this->mrTemplate->AddVar('nominal_pajak_tanpa_npwp', 'POT_PERBL_NO_NPWP', $potPerblNoNPWP);
      $this->mrTemplate->AddVar('nominal_pajak_tanpa_npwp', 'POT_PERBL_TOTAL', $potPerblTotal);
		  $this->mrTemplate->AddVar('nominal_pajak_tanpa_npwp', 'POT_PERTH', $potPerth);
    }
    
		//Komponen Penghasilan Bruto
		if (empty($data['dataPph'])) {
			$this->mrTemplate->AddVar('data_formula', 'DATA_EMPTY', 'YES');
		} else {
			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
			$encPage = Dispatcher::Instance()->Encrypt($decPage);
			$this->mrTemplate->AddVar('data_formula', 'DATA_EMPTY', 'NO');
			$dataPph = $data['dataPph'];
			for ($i=0; $i<sizeof($dataPph); $i++) {
					$dataPph[$i]['number'] = $i+1;
					if ($no % 2 != 0) $dataPph[$i]['class_name'] = 'table-common-even';
					else $dataPph[$i]['class_name'] = '';
					
					if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);				
					if($i == sizeof($dataPph)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
					
					$totalNominal +=$dataPph[$i]['nominal_komponen'];
					if($dataPegawai[0]['jenis'] != 1){
            $totalNominalTahun = $totalNominal*($dataMasaKerja);
            $dataPph[$i]['nominal_tahun_komponen'] = $dataPph[$i]['nominal_komponen']*($dataMasaKerja);
					} else {
            $totalNominalTahun = $dataMasaKerja*$totalNominal;
            $dataPph[$i]['nominal_tahun_komponen'] = 12*($dataPph[$i]['nominal_komponen']);
          }
					
					$dataPph[$i]['nominal_komponen'] = number_format($dataPph[$i]['nominal_komponen'], 2, ',', '.');
					$dataPph[$i]['nominal_tahun_komponen'] = number_format($dataPph[$i]['nominal_tahun_komponen'], 2, ',', '.');
					
					$this->mrTemplate->AddVars('data_pph_item', $dataPph[$i], 'DATA_');
					$this->mrTemplate->parseTemplate('data_pph_item', 'a');	 
				}
			$totalNominal=number_format($totalNominal, 2, ',', '.');
			$totalNominalTahun=number_format($totalNominalTahun, 2, ',', '.');
			$this->mrTemplate->AddVar('content', 'TOTAL_NOMINAL', $totalNominal);
			$this->mrTemplate->AddVar('content', 'TOTAL_NOMINAL_TAHUN', $totalNominalTahun);
		}
		
		//Komponen PTKP
		
		if (empty($data['dataPtkp'])) {
			$this->mrTemplate->AddVar('data_komponen_ptkp', 'DATA_EMPTY', 'YES');
		} else {
			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
			$encPage = Dispatcher::Instance()->Encrypt($decPage);
			$this->mrTemplate->AddVar('data_komponen_ptkp', 'DATA_EMPTY', 'NO');
			$dataPtkp = $data['dataPtkp'];
			for ($i=0; $i<sizeof($dataPtkp); $i++) {
					$dataPtkp[$i]['number'] = $i+1;
					if ($no % 2 != 0) $dataPtkp[$i]['class_name'] = 'table-common-even';
					else $dataPtkp[$i]['class_name'] = '';
					
					if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);				
					if($i == sizeof($dataPtkp)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
					
					$totalPtkpNominal +=$dataPtkp[$i]['nominal'];
					if($dataPegawai[0]['jenis'] != 1){
            $totalPtkpNominalTahun = $totalPtkpNominal*($dataMasaKerja);
            $dataPtkp[$i]['nominal_tahun'] = $dataPtkp[$i]['nominal']*($dataMasaKerja);
					} else {
            $totalPtkpNominalTahun = $dataMasaKerja*$totalPtkpNominal;
            $dataPtkp[$i]['nominal_tahun'] = 12*($dataPtkp[$i]['nominal']);
          }
					
					$dataPtkp[$i]['nominal'] = number_format($dataPtkp[$i]['nominal'], 2, ',', '.');
					$dataPtkp[$i]['nominal_tahun'] = number_format($dataPtkp[$i]['nominal_tahun'], 2, ',', '.');
					
					$this->mrTemplate->AddVars('data_komponen_ptkp_item', $dataPtkp[$i], 'DATA_KOMPONEN_PTKP_');
					$this->mrTemplate->parseTemplate('data_komponen_ptkp_item', 'a');	 
				}
			$totalPtkpNominal=number_format($totalPtkpNominal, 2, ',', '.');
			$totalPtkpNominalTahun=number_format($totalPtkpNominalTahun, 2, ',', '.');
			$this->mrTemplate->AddVar('content', 'TOTAL_KOMPONEN_PTKP_NOMINAL', $totalPtkpNominal);
			$this->mrTemplate->AddVar('content', 'TOTAL_KOMPONEN_PTKP_NOMINAL_TAHUN', $totalPtkpNominalTahun);
		}
		
		//----untuk rincian data yg telah tersimpan-----
			if (empty($data['dataRincian'])) {
				$this->mrTemplate->AddVar('data_rincian', 'DATA_EMPTY', 'YES');
			} else {
				$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
				$encPage = Dispatcher::Instance()->Encrypt($decPage);
				$this->mrTemplate->AddVar('data_rincian', 'DATA_EMPTY', 'NO');
				$dataRincian = $data['dataRincian'];
				for ($i=0; $i<sizeof($dataRincian); $i++) {
						$dataRincian[$i]['number'] = $i+1;
						if ($no % 2 != 0) $dataRincian[$i]['class_name'] = 'table-common-even';
						else $dataRincian[$i]['class_name'] = '';
						
						if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);				
						if($i == sizeof($dataRincian)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
						
						$totalNominalRincian +=$dataRincian[$i]['nominal_komp_peg'];
						$dataRincian[$i]['nominal_tahun_komp_peg'] = $dataMasaKerja*($dataRincian[$i]['nominal_komp_peg']);
            
						$dataRincian[$i]['nominal_komp_peg'] = number_format($dataRincian[$i]['nominal_komp_peg'], 2, ',', '.');
						
						$urlAccept = 'ref_pph_pegawai_komponen|deletePphPegawaiKomp|do|html-dataId-'.$cari;
						$urlReturn = 'ref_pph_pegawai_komponen|inputPphPegawaiKomp|view|html-dataId-'.$cari;
						$label = 'Komponen Pph Pegawai';
						$dataRincian[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.$dataRincian[$i]['id_pegawai'].'&urlReturn='.$urlReturn.$dataRincian[$i]['id_pegawai'].'&id='.$dataRincian[$i]['id_komp_peg'].'|'.$dataRincian[$i]['id_pegawai'].'|'.$data['dataPegPot'][0]['pot_id'].'|'.$data['nama'].'&label='.$label.'&dataName='.$dataRincian[$i]['komp_form_nama'];               

            $dataRincian[$i]['nominal_tahun_komp_peg'] = number_format($dataRincian[$i]['nominal_tahun_komp_peg'], 2, ',', '.');
						$this->mrTemplate->AddVars('data_rincian_item', $dataRincian[$i], 'DATA_');
						$this->mrTemplate->parseTemplate('data_rincian_item', 'a');	 
					}
				$totalNominalTahunRincian = $dataMasaKerja*($totalNominalRincian);
        $totalNominalRincian=number_format($totalNominalRincian, 2, ',', '.');
				$totalNominalTahunRincian=number_format($totalNominalTahunRincian, 2, ',', '.');
				
				$this->mrTemplate->AddVar('content', 'TOTAL_NOMINAL_RINCIAN', $totalNominalRincian);
				$this->mrTemplate->AddVar('content', 'TOTAL_NOMINAL_TAHUN_RINCIAN', $totalNominalTahunRincian);
			}
	}
}
?>
