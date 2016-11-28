<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_bkd/business/laporan.class.php';
   
class ViewRekapitulasiBkd extends XlsResponse
{
	var $mWorksheets = array('Bkd');
   
	function GetFileName() {
		// name it whatever you want
		return 'laporan_rekapitulasi_bkd_'.date('Ymd').'.xls';
	}
   
	function GetLabelFromCombo($ArrData,$Nilai){
		for ($i=0; $i<sizeof($ArrData); $i++){
			if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
		}
		return '--Semua--';
	}
   
	function ProcessRequest()
	{
		$this->Obj=new Laporan;
  		if(isset($_POST['unit_kerja'])) {
  			$this->unit_kerja = $_POST['unit_kerja'];
  		} elseif(isset($_GET['unit_kerja'])) {
  			$this->unit_kerja = Dispatcher::Instance()->Decrypt($_GET['unit_kerja']);
  		} else {
  			$this->unit_kerja = 'all';
  		}
			
		if(isset($_POST['pangkat_golongan'])) {
  			$this->pangkat_golongan = $_POST['pangkat_golongan'];
  		} elseif(isset($_GET['pangkat_golongan'])) {
  			$this->pangkat_golongan = Dispatcher::Instance()->Decrypt($_GET['pangkat_golongan']);
  		} else {
  			$this->pangkat_golongan = 'all';
  		}
  				
		if(isset($_POST['fungsional'])) {
  			$this->fungsional = $_POST['fungsional'];
  		} elseif(isset($_GET['fungsional'])) {
  			$this->fungsional = Dispatcher::Instance()->Decrypt($_GET['fungsional']);
  		} else {
  			$this->fungsional = 'all';
  		}
  				
		if(isset($_POST['pendidikan'])) {
  			$this->pendidikan = $_POST['pendidikan'];
  		} elseif(isset($_GET['pendidikan'])) {
  			$this->pendidikan = Dispatcher::Instance()->Decrypt($_GET['pendidikan']);
  		} else {
  			$this->pendidikan = 'all';
  		}
  				
  		if ($_SESSION['unit_id']==1) {
			$true='true';
		}else{
			if ($this->unit_kerja=='all') $this->unit_kerja=$_SESSION['unit_kerja'];
		}
  		
  		$this->ComboUnitKerja	= $this->Obj->GetComboUnitKerja();
  		$this->label_unit_kerja	= $this->GetLabelFromCombo($this->ComboUnitKerja,$this->unit_kerja);
  		
  		$this->ComboPangkatGolongan		= $this->Obj->GetComboPangkatGolongan();
  		$this->label_pangkat_golongan	= $this->GetLabelFromCombo($this->ComboPangkatGolongan,$this->pangkat_golongan);
  		
		$this->ComboFungsional	= $this->Obj->GetComboFungsional();
  		$this->label_fungsional	= $this->GetLabelFromCombo($this->ComboFungsional,$this->fungsional);
		
		$this->ComboPendidikan	= $this->Obj->GetComboPendidikan();
  		$this->label_pendidikan	= $this->GetLabelFromCombo($this->ComboPendidikan,$this->pendidikan);

// CREATE XLS START ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// $totalDataPend	= $this->Obj->GetCountDataBkdPendidikan($this->id_bkd);
		// $dataPend		= $this->Obj->GetDataBkdPendidikan(0, $totalDataPend, $this->id_bkd);
		$dataFakultas	= $this->Obj->GetDataFakultas($this->unit_kerja);
  		$row	= -1;
  		  		
  		$this->fH1 = $this->mrWorkbook->add_format();
		$this->fH1->set_bold();
		$this->fH1->set_size(12);
		$this->fH1->set_align('vcenter');
		$this->fH1->set_align('center');

		$this->fH2 = $this->mrWorkbook->add_format();
		$this->fH2->set_bold();
		$this->fH2->set_size(11);
		$this->fH2->set_align('vcenter');

		#set Header
		$this->fH3 = $this->mrWorkbook->add_format();
		$this->fH3->set_border(1);
		$this->fH3->set_bold();
		$this->fH3->set_size(10);
		$this->fH3->set_align('center');
		$this->fH3->set_align('vcenter');
		$this->fH3->set_fg_color('white');
		$this->fH3->set_bg_color('grey');
		$this->fH3->set_pattern(2);
		$this->fH3->set_bottom(2);
		$this->fH3->set_top(2);
		$this->fH3->set_right(2);
		$this->fH3->set_left(2);
		$this->fH3->set_text_wrap();
         
		$this->fColData = $this->mrWorkbook->add_format();
    	$this->fColData->set_border(1);   
    	$this->fColData->set_size(10);
    	$this->fColData->set_align('right');
    	$this->fColData->set_align('top');
    	$this->fColData->set_text_wrap();
    	
    	$this->fColData2 = $this->mrWorkbook->add_format();
    	$this->fColData2->set_border(1);   
    	$this->fColData2->set_size(10);
    	$this->fColData2->set_align('left');
    	$this->fColData2->set_align('top');
    	$this->fColData2->set_text_wrap();

    	$this->fTH = $this->mrWorkbook->add_format();
    	$this->fTH->set_size(10);
    	$this->fTH->set_align('left');
    	$this->fTH->set_align('top');
    	$this->fTH->set_text_wrap();

    	$this->fTHB = $this->mrWorkbook->add_format();
//		$this->fTHB->set_bold();
    	$this->fTHB->set_size(10);
    	$this->fTHB->set_align('left');
    	$this->fTHB->set_align('top');
    	$this->fTHB->set_text_wrap();
//		$this->fTH->set_align('vcenter');
    	
    	$jumKolom=14;
  		
  		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'REKAPITULASI BEBAN KINERJA DOSEN', $this->fH1);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'Unit Kerja  : '.$this->label_unit_kerja , $this->fH2);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'Pangkat/Golongan  : '.$this->label_pangkat_golongan , $this->fH2);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'jabatan Fungsional : '.$this->label_fungsional , $this->fH2);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'Tingkat Pendidikan : '.$this->label_pendidikan , $this->fH2);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);

		// separator 
		$row++; $this->mWorksheets['Bkd']->write($row, 0, '', $this->fH2); $this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);		
		
		//Set Header
		$header[0]=array(
						'No.','No.Sertifikat','Nama Dosen',
						'Semester Gasal','','','',
						'Semester Genap','','','',
						'Kewajiban Khusus Profesor',
						'Status',
						'Kesimpulan');
		$header[1]=array(
						'','','',
						'PD','PL','PG','PK',
						'PD','PL','PG','PK',
						'',
						'',
						'');
				   
		//Set Nama Variabel Yang akan ditulis
		$val=array(
					'no','no_sertifikasi','nama',
					'sum_pend_ganjil','sum_penl_ganjil','sum_peng_ganjil','sum_penu_ganjil',
					'sum_pend_genap','sum_penl_genap','sum_peng_genap','sum_penu_genap',
					'sum_prof',
					'bkdJenis',
					'kesimpulan');


		// separator 
		$row++; $this->mWorksheets['Bkd']->write($row, 0, '', $this->fH2); $this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);		


		//Set Lebar Kolom Awal =0
		for ($i=0; $i<$jumKolom; $i++){
			$size_col[$val[$i]]=0;
		}

for ($z=0; $z<sizeof($dataFakultas); $z++) {
	$dataPegawai[$dataFakultas[$z][idFak]]	= $this->Obj->GetDataRekapitulasiBkd($this->unit_kerja, $this->pangkat_golongan, $this->fungsional, $this->pendidikan, $dataFakultas[$z][nameFak]);
	$paramQuery		= $dataPegawai[$dataFakultas[$z][idFak]];
	$pegId			= $paramQuery[0][id];
	
	// get sks ganjil '''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	$getSksPendGanjil[$dataFakultas[$z][idFak]]	= $this->Obj->getSksPendGanjil($pegId);
	$getSksPenlGanjil[$dataFakultas[$z][idFak]]	= $this->Obj->getSksPenlGanjil($pegId);
	$getSksPengGanjil[$dataFakultas[$z][idFak]]	= $this->Obj->getSksPengGanjil($pegId);
	$getSksPenuGanjil[$dataFakultas[$z][idFak]]	= $this->Obj->getSksPenuGanjil($pegId);

	// get sks genap ''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	$getSksPendGenap[$dataFakultas[$z][idFak]]	= $this->Obj->getSksPendGenap($pegId);
	$getSksPenlGenap[$dataFakultas[$z][idFak]]	= $this->Obj->getSksPenlGenap($pegId);
	$getSksPengGenap[$dataFakultas[$z][idFak]]	= $this->Obj->getSksPengGenap($pegId);
	$getSksPenuGenap[$dataFakultas[$z][idFak]]	= $this->Obj->getSksPenuGenap($pegId);

	$getSksProf[$dataFakultas[$z][idFak]]	= $this->Obj->getSksProf($pegId);
// LIST PENDIDIKAN START //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Header/Judul Table
		$row++; 
		$k		= 0;
		$Htemp	= $header;
		$this->mWorksheets['Bkd']->write($row, 0, $dataFakultas[$z][nameFak], $this->fH2);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);		
		for ($i=0; $i<sizeof($header); $i++){
			$row++;
			for ($ii=0; $ii<sizeof($val); $ii++){
				$this->mWorksheets['Bkd']->write($row, $ii, $header[$i][$ii], $this->fH3);
				if ($size_col[$val[$ii]]<strlen($header[$i][$ii])) {$size_col[$val[$ii]]=strlen($header[$i][$ii]);}

				$bottom=$i+1;
				if (($Htemp[$i][$ii]!='')&&($bottom<sizeof($Htemp))&&($Htemp[$bottom][$ii]=='')){
					$bottom=$i+1;
					$k++;
					while (($bottom<sizeof($Htemp))&&($Htemp[$bottom][$ii]=='')){
						$merger[$k]['row_awal']	= $i+$row;
						$merger[$k]['row_akhir']= $bottom+$row;
						$merger[$k]['col_awal']	= $ii;
						$merger[$k]['col_akhir']= $ii;
						$Htemp[$bottom][$ii]	= 'WHY';
						$bottom++;
					}
				}

				$left=$ii+1;
				if (($Htemp[$i][$ii]!='')&&($left<sizeof($val))&&($Htemp[$i][$left]=='')){
					$left	= $ii+1;
					$k++;
					while (($left<sizeof($val))&&($Htemp[$i][$left]=='')){
						$merger[$k]['row_awal']	= $i+$row;
						$merger[$k]['row_akhir']= $i+$row;
						$merger[$k]['col_awal']	= $ii;
						$merger[$k]['col_akhir']= $left;
						$Htemp[$i][$left]		= 'WHY';
						$left++;
					}
				}
			}
		} 

		// Merge kolom sub header
		for ($i=1; $i<=sizeof($merger); $i++)
		{
			$this->mWorksheets['Bkd']->merge_cells($merger[$i]['row_awal'], $merger[$i]['col_awal'], $merger[$i]['row_akhir'], $merger[$i]['col_akhir']);
		}
      
		// Penulisan urutan kolom (1,2,3,4,5,6,7, dst ........)
		$row++;//$row++;
		for ($i=0; $i<$jumKolom; $i++)
		{
			$this->mWorksheets['Bkd']->write($row, $i, $i+1, $this->fH3);
		}
      
		// ISI LIST DATA START ==================================================================
		$dataPegawaiTemp		= $dataPegawai[$dataFakultas[$z][idFak]];

		// deklarasi sks ganjil 
		$getSksPendGanjilTemp	= $getSksPendGanjil[$dataFakultas[$z][idFak]];
		$getSksPenlGanjilTemp	= $getSksPenlGanjil[$dataFakultas[$z][idFak]];
		$getSksPengGanjilTemp	= $getSksPengGanjil[$dataFakultas[$z][idFak]];
		$getSksPenuGanjilTemp	= $getSksPenuGanjil[$dataFakultas[$z][idFak]];
		
		// deklarasi sks genap
		$getSksPendGenapTemp	= $getSksPendGenap[$dataFakultas[$z][idFak]];
		$getSksPenlGenapTemp	= $getSksPenlGenap[$dataFakultas[$z][idFak]];
		$getSksPengGenapTemp	= $getSksPengGenap[$dataFakultas[$z][idFak]];
		$getSksPenuGenapTemp	= $getSksPenuGenap[$dataFakultas[$z][idFak]];
		
		$getSksProfTemp			= $getSksProf[$dataFakultas[$z][idFak]];
		
			if (sizeof($dataPegawaiTemp)<=0) {
				$row++;
				$this->mWorksheets['Bkd']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
				for ($i=1; $i<$jumKolom; $i++){
					$this->mWorksheets['Bkd']->write($row, $i, '', $this->fH3);
				}
				$this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);
			} else {
				for ($i=0; $i<sizeof($dataPegawaiTemp); $i++) 
				{
					$no		= $i+1;
					$row++; 
					$cols	= 0;
					$dataPegawaiTemp[$i]['no'] = $no;

					// isian sks
					$dataPegawaiTemp[$i]['sum_pend_ganjil'] = $getSksPendGanjilTemp[$i][sum_pend_ganjil];
					$dataPegawaiTemp[$i]['sum_penl_ganjil'] = $getSksPenlGanjilTemp[$i][sum_penl_ganjil];
					$dataPegawaiTemp[$i]['sum_peng_ganjil'] = $getSksPengGanjilTemp[$i][sum_peng_ganjil];
					$dataPegawaiTemp[$i]['sum_penu_ganjil'] = $getSksPenuGanjilTemp[$i][sum_penu_ganjil];

					$dataPegawaiTemp[$i]['sum_pend_genap'] = $getSksPendGenapTemp[$i][sum_pend_genap];
					$dataPegawaiTemp[$i]['sum_penl_genap'] = $getSksPenlGenapTemp[$i][sum_penl_genap];
					$dataPegawaiTemp[$i]['sum_peng_genap'] = $getSksPengGenapTemp[$i][sum_peng_genap];
					$dataPegawaiTemp[$i]['sum_penu_genap'] = $getSksPenuGenapTemp[$i][sum_penu_genap];
					
					$dataPegawaiTemp[$i]['sum_prof'] = $getSksProfTemp[$i][sum_prof];

					
					for ($ii=0; $ii<sizeof($val); $ii++)
					{
						$this->mWorksheets['Bkd']->write($row, $ii, $dataPegawaiTemp[$i][$val[$ii]], $this->fColData2);

						//Mencari Panjang Data Maksimal
						if ($size_col[$val[$ii]]<strlen($dataPegawaiTemp[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataPegawaiTemp[$i][$val[$ii]]);}
					}
				}
				//Setting Lebar Kolom
				$lebar_max=50;
				for ($i=0; $i<$jumKolom; $i++)
				{
					if ($size_col[$val[$i]]>$lebar_max) $size_col[$val[$i]]=$lebar_max;
					$this->mWorksheets['Bkd']->set_column($i,$i,$size_col[$val[$i]]+3);
				}
			}
		// ISI LIST DATA END ====================================================================

// LIST PENDIDIKAN END //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// separator 
		$row++; $this->mWorksheets['Bkd']->write($row, 0, '', $this->fH2); $this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);		
}
                
   }

   
		function GetCol($nilai){
			$var	= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$awal	= round($nilai/26);
			$akhir	= $nilai % 26;

			return $var[$awal-1].$var[$akhir-1];
		}
   
}
   

?>