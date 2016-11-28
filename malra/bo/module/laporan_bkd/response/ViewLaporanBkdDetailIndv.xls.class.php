<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_bkd/business/laporan.class.php';
   
class ViewLaporanBkdDetailIndv extends XlsResponse
{
	var $mWorksheets = array('Bkd');
   
	function GetFileName() {
		// name it whatever you want
		return 'laporan_detail_BKD_individu_'.date('Ymd').'.xls';
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
  		if(isset($_GET['idBkd'])) {
  			$this->id_bkd = $_GET['idBkd'];
  		}
		  		
// CREATE XLS START ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$totalData		= $this->Obj->GetCountDataBkdDetailIndividu($this->id_bkd);
		$dataPegawai	= $this->Obj->GetDataBkdDetailIndividu(0, $totalData, $this->id_bkd);
  		$data	 		= $dataPegawai;

		$totalDataPend	= $this->Obj->GetCountDataBkdPendidikan($this->id_bkd);
		$dataPend		= $this->Obj->GetDataBkdPendidikan(0, $totalDataPend, $this->id_bkd);
  		$row	 		= -1;
  		
		$totalDataPenl	= $this->Obj->GetCountDataBkdPenelitian($this->id_bkd);
		$dataPenl		= $this->Obj->GetDataBkdPenelitian(0, $totalDataPenl, $this->id_bkd);
  		$row	 		= -1;
  		
		$totalDataPengb	= $this->Obj->GetCountDataBkdPengabdian($this->id_bkd);
		$dataPengb		= $this->Obj->GetDataBkdPengabdian(0, $totalDataPengb, $this->id_bkd);
  		$row	 		= -1;
  		
		$totalDataPenu	= $this->Obj->GetCountDataBkdPenunjang($this->id_bkd);
		$dataPenu		= $this->Obj->GetDataBkdPenunjang(0, $totalDataPenu, $this->id_bkd);
  		$row	 		= -1;
  		
		$totalDataProf	= $this->Obj->GetCountDataBkdProfesor($this->id_bkd);
		$dataProf		= $this->Obj->GetDataBkdProfesor(0, $totalDataProf, $this->id_bkd);
  		$row	 		= -1;
  		
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
    	
    	$jumKolom=8;
  		
  		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'REKAPITULASI DATA INDIVIDU BEBAN KINERJA DOSEN', $this->fH1);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);

  		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'Semester '.$data[0][semester].' - Tahun Akademik '.$data[0][tahun_akademik], $this->fH1);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);

		// separator 
		$row++; $this->mWorksheets['Bkd']->write($row, 0, '', $this->fH2); $this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);		
		
// IDENTITAS DOSEN START ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'I. IDENTITAS', $this->fH2);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'Nama', $this->fTH);
		$this->mWorksheets['Bkd']->write($row, 2, ': '.$data[0][nama], $this->fTHB);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row, 1);
		$this->mWorksheets['Bkd']->merge_cells($row, 2, $row, 4);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'No. Sertifikat', $this->fTH);
		$this->mWorksheets['Bkd']->write($row, 2, ': '.$data[0][no_sertifikasi], $this->fTHB);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row, 1);
		$this->mWorksheets['Bkd']->merge_cells($row, 2, $row, 4);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'Nama Perguruan Tinggi', $this->fTH);
		$this->mWorksheets['Bkd']->write($row, 2, ': '.$data[0][nm_pt], $this->fTHB);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row, 1);
		$this->mWorksheets['Bkd']->merge_cells($row, 2, $row, 4);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'Alamat Perguruan Tinggi', $this->fTH);
		$this->mWorksheets['Bkd']->write($row, 2, ': '.$data[0][almt_pt], $this->fTHB);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row, 1);
		$this->mWorksheets['Bkd']->merge_cells($row, 2, $row, 4);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'Fakultas/Departemen', $this->fTH);
		$this->mWorksheets['Bkd']->write($row, 2, ': '.$data[0][fak], $this->fTHB);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row, 1);
		$this->mWorksheets['Bkd']->merge_cells($row, 2, $row, 4);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'Jenis/Status', $this->fTH);
		$this->mWorksheets['Bkd']->write($row, 2, ': '.$data[0][bkdJenis], $this->fTHB);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row, 1);
		$this->mWorksheets['Bkd']->merge_cells($row, 2, $row, 4);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'Program Studi', $this->fTH);
		$this->mWorksheets['Bkd']->write($row, 2, ': '.$data[0][prodi], $this->fTHB);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row, 1);
		$this->mWorksheets['Bkd']->merge_cells($row, 2, $row, 4);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'Jab. Fungsional/Gol', $this->fTH);
		$this->mWorksheets['Bkd']->write($row, 2, ': '.$data[0][jabfung].' / '.$data[0][golongan], $this->fTHB);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row, 1);
		$this->mWorksheets['Bkd']->merge_cells($row, 2, $row, 4);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'S1', $this->fTH);
		$this->mWorksheets['Bkd']->write($row, 2, ': '.$data[0][s1], $this->fTHB);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row, 1);
		$this->mWorksheets['Bkd']->merge_cells($row, 2, $row, 4);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'S2', $this->fTH);
		$this->mWorksheets['Bkd']->write($row, 2, ': '.$data[0][s2], $this->fTHB);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row, 1);
		$this->mWorksheets['Bkd']->merge_cells($row, 2, $row, 4);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'S2', $this->fTH);
		$this->mWorksheets['Bkd']->write($row, 2, ': '.$data[0][s2], $this->fTHB);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row, 1);
		$this->mWorksheets['Bkd']->merge_cells($row, 2, $row, 4);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'Bidang Ilmu', $this->fTH);
		$this->mWorksheets['Bkd']->write($row, 2, ': '.$data[0][bid_ilmu], $this->fTHB);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row, 1);
		$this->mWorksheets['Bkd']->merge_cells($row, 2, $row, 4);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'No. HP', $this->fTH);
		$this->mWorksheets['Bkd']->write($row, 2, ': '.$data[0][hp], $this->fTHB);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row, 1);
		$this->mWorksheets['Bkd']->merge_cells($row, 2, $row, 4);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'Asesor 1', $this->fTH);
		$this->mWorksheets['Bkd']->write($row, 2, ': '.$data[0][asesor_1], $this->fTHB);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row, 1);
		$this->mWorksheets['Bkd']->merge_cells($row, 2, $row, 4);

		$row++;
		$this->mWorksheets['Bkd']->write($row, 0, 'Asesor 2', $this->fTH);
		$this->mWorksheets['Bkd']->write($row, 2, ': '.$data[0][asesor_2], $this->fTHB);
		$this->mWorksheets['Bkd']->merge_cells($row, 0, $row, 1);
		$this->mWorksheets['Bkd']->merge_cells($row, 2, $row, 4);
		
// IDENTITAS DOSEN END ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

		//Set Header
		$header[0]=array(
						'No.','Kegiatan',
						'Beban Kerja','',
						'Masa Pelaksanaan Tugas',
						'Kinerja','',
						'Penilaian/Rekomendasi Asesor');
		$header[1]=array(
						'','',
						'Bukti Penugasan','SKS',
						'',
						'Bukti Kinerja','Capaian',
						'');
				   
		//Set Nama Variabel Yang akan ditulis
		$val=array(
				'no','nmKeg',
				'bkBukti','bkSks',
				'masa',
				'kBukti','bksks',
				'nmrekomen');


		// separator 
		$row++; $this->mWorksheets['Bkd']->write($row, 0, '', $this->fH2); $this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);		


		//Set Lebar Kolom Awal =0
		for ($i=0; $i<$jumKolom; $i++){
			$size_col[$val[$i]]=0;
		}


// LIST PENDIDIKAN START //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Header/Judul Table
		$row++; 
		$k		= 0;
		$Htemp	= $header;
		$this->mWorksheets['Bkd']->write($row, 0, 'II. BIDANG PENDIDIKAN', $this->fH2);
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
      
		// Penulisan isi data
		if (sizeof($dataPend)<=0) {
			$row++;
			$this->mWorksheets['Bkd']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
			for ($i=1; $i<$jumKolom; $i++){
				$this->mWorksheets['Bkd']->write($row, $i, '', $this->fH3);
			}
			$this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);
		} else {
			// $dataPegawai = $data;
			for ($i=0; $i<sizeof($dataPend); $i++) 
			{
				$no		= $i+1;
				$row++; 
				$cols	= 0;
				$dataPend[$i]['no'] = $no;
				for ($ii=0; $ii<sizeof($val); $ii++)
				{
					$this->mWorksheets['Bkd']->write($row, $ii, $dataPend[$i][$val[$ii]], $this->fColData2);
					//Mencari Panjang Data Maksimal
					if ($size_col[$val[$ii]]<strlen($dataPend[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataPend[$i][$val[$ii]]);}
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
// LIST PENDIDIKAN END //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// separator 
		$row++; $this->mWorksheets['Bkd']->write($row, 0, '', $this->fH2); $this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);		
        
// LIST PENELITIAN START //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Header/Judul Table
		$row++; 
		$k		= 0;
		$Htemp	= $header;
		$this->mWorksheets['Bkd']->write($row, 0, 'III. BIDANG PENELITIAN', $this->fH2);
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
      
		// Penulisan isi data
		if (sizeof($dataPenl)<=0) {
			$row++;
			$this->mWorksheets['Bkd']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
			for ($i=1; $i<$jumKolom; $i++){
				$this->mWorksheets['Bkd']->write($row, $i, '', $this->fH3);
			}
			$this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);
		} else {
			// $dataPegawai = $data;
			for ($i=0; $i<sizeof($dataPenl); $i++) 
			{
				$no		= $i+1;
				$row++; 
				$cols	= 0;
				$dataPenl[$i]['no'] = $no;
				for ($ii=0; $ii<sizeof($val); $ii++)
				{
					$this->mWorksheets['Bkd']->write($row, $ii, $dataPenl[$i][$val[$ii]], $this->fColData2);
					//Mencari Panjang Data Maksimal
					if ($size_col[$val[$ii]]<strlen($dataPenl[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataPenl[$i][$val[$ii]]);}
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
// LIST PENELITIAN END //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// separator 
		$row++; $this->mWorksheets['Bkd']->write($row, 0, '', $this->fH2); $this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);		
        
// LIST PENGABDIAN START //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Header/Judul Table
		$row++; 
		$k		= 0;
		$Htemp	= $header;
		$this->mWorksheets['Bkd']->write($row, 0, 'IV. BIDANG PENGABDIAN', $this->fH2);
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
      
		// Penulisan isi data
		if (sizeof($dataPengb)<=0) {
			$row++;
			$this->mWorksheets['Bkd']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
			for ($i=1; $i<$jumKolom; $i++){
				$this->mWorksheets['Bkd']->write($row, $i, '', $this->fH3);
			}
			$this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);
		} else {
			// $dataPegawai = $data;
			for ($i=0; $i<sizeof($dataPengb); $i++) 
			{
				$no		= $i+1;
				$row++; 
				$cols	= 0;
				$dataPengb[$i]['no'] = $no;
				for ($ii=0; $ii<sizeof($val); $ii++)
				{
					$this->mWorksheets['Bkd']->write($row, $ii, $dataPengb[$i][$val[$ii]], $this->fColData2);
					//Mencari Panjang Data Maksimal
					if ($size_col[$val[$ii]]<strlen($dataPengb[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataPengb[$i][$val[$ii]]);}
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
// LIST PENGABDIAN END //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// separator 
		$row++; $this->mWorksheets['Bkd']->write($row, 0, '', $this->fH2); $this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);		
        
// LIST PENUNJANG START //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Header/Judul Table
		$row++; 
		$k		= 0;
		$Htemp	= $header;
		$this->mWorksheets['Bkd']->write($row, 0, 'V. BIDANG PENUNJANG', $this->fH2);
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
      
		// Penulisan isi data
		if (sizeof($dataPenu)<=0) {
			$row++;
			$this->mWorksheets['Bkd']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
			for ($i=1; $i<$jumKolom; $i++){
				$this->mWorksheets['Bkd']->write($row, $i, '', $this->fH3);
			}
			$this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);
		} else {
			// $dataPegawai = $data;
			for ($i=0; $i<sizeof($dataPenu); $i++) 
			{
				$no		= $i+1;
				$row++; 
				$cols	= 0;
				$dataPenu[$i]['no'] = $no;
				for ($ii=0; $ii<sizeof($val); $ii++)
				{
					$this->mWorksheets['Bkd']->write($row, $ii, $dataPenu[$i][$val[$ii]], $this->fColData2);
					//Mencari Panjang Data Maksimal
					if ($size_col[$val[$ii]]<strlen($dataPenu[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataPenu[$i][$val[$ii]]);}
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
// LIST PENUNJANG END //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// separator 
		$row++; $this->mWorksheets['Bkd']->write($row, 0, '', $this->fH2); $this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);		
        
// LIST PROFESOR START //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Header/Judul Table
		$row++; 
		$k		= 0;
		$Htemp	= $header;
		$this->mWorksheets['Bkd']->write($row, 0, 'VI. BIDANG PROFESOR', $this->fH2);
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
      
		// Penulisan isi data
		if (sizeof($dataProf)<=0) {
			$row++;
			$this->mWorksheets['Bkd']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
			for ($i=1; $i<$jumKolom; $i++){
				$this->mWorksheets['Bkd']->write($row, $i, '', $this->fH3);
			}
			$this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);
		} else {
			// $dataPegawai = $data;
			for ($i=0; $i<sizeof($dataProf); $i++) 
			{
				$no		= $i+1;
				$row++; 
				$cols	= 0;
				$dataProf[$i]['no'] = $no;
				for ($ii=0; $ii<sizeof($val); $ii++)
				{
					$this->mWorksheets['Bkd']->write($row, $ii, $dataProf[$i][$val[$ii]], $this->fColData2);
					//Mencari Panjang Data Maksimal
					if ($size_col[$val[$ii]]<strlen($dataProf[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataProf[$i][$val[$ii]]);}
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
// LIST PROFESOR END //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
   }

   
		function GetCol($nilai){
			$var	= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$awal	= round($nilai/26);
			$akhir	= $nilai % 26;

			return $var[$awal-1].$var[$akhir-1];
		}
   
}
   

?>