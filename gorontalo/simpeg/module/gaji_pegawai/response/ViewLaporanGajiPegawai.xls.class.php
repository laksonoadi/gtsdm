<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/gaji_pegawai/business/AppGajiPegawai.class.php';
   
class ViewLaporanGajiPegawai extends XlsResponse
{
   var $mWorksheets = array('GajiPegawai','Rekap','LaporanBank','Pajak');
   
   function GetFileName() {
      // name it whatever you want
      return 'Laporan_GajiPegawai_'.date('Ymd').'.xls';
   }
   
   function ProcessRequest()
   {
		$this->Obj=new AppGajiPegawai;
		$institusi = GTFWConfiguration::GetValue( 'application', 'company_name');
	  
		$nip_nama = $_GET['nip_nama'];
		$satkerja = $_GET['satkerja'];	
		$jenis = $_GET['jenis'];
		$idBulan = $_GET['periode_bulan'];
		$idTahun = $_GET['periode_tahun'];
		
		  $dataPegawai = $this->Obj->getDataCetak($nip_nama, $satkerja, $jenis, $idBulan, $idTahun);
		  
		  $totalDataPegawai = sizeof($dataPegawai); 
		  for ($i=0; $i<$totalDataPegawai; $i++){
			$dataPegawai[$i]['periode_text'] = $this->periode2string($dataPegawai[$i]['periode']);
		  }
		  
  		$data=$dataPegawai;
  		$row=-1;
		
  		$this->Kolom=$this->Obj->Get($this->tahun1,$this->tahun2);
		
  		$this->fH1 = $this->mrWorkbook->add_format();
		$this->fH1->set_bold();
		$this->fH1->set_size(12);
		$this->fH1->set_align('vcenter');
		$this->fH1->set_align('left');
      
		$this->fH2 = $this->mrWorkbook->add_format();
		$this->fH2->set_bold();
		$this->fH2->set_size(10);
		$this->fH2->set_align('vcenter');
         
		#set Header
		$this->fH3 = $this->mrWorkbook->add_format();
		$this->fH3->set_border(1);
		$this->fH3->set_bold();
		$this->fH3->set_size(10);
		$this->fH3->set_align('center');
		$this->fH3->set_align('vcenter');
		$this->fH3->set_fg_color('grey');
		$this->fH3->set_bg_color('grey');
		$this->fH3->set_pattern(2);
		$this->fH3->set_bottom(2);
		$this->fH3->set_top(2);
		$this->fH3->set_right(2);
		$this->fH3->set_left(2);
		$this->fH3->set_text_wrap();
		
		$this->fH32 = $this->mrWorkbook->add_format();
		$this->fH32->set_border(1);
		$this->fH32->set_bold();
		$this->fH32->set_size(10);
		$this->fH32->set_align('right');
		$this->fH32->set_align('vcenter');
		$this->fH32->set_fg_color('grey');
		$this->fH32->set_bg_color('grey');
		$this->fH32->set_pattern(2);
		$this->fH32->set_bottom(2);
		$this->fH32->set_top(2);
		$this->fH32->set_right(2);
		$this->fH32->set_left(2);
		$this->fH32->set_text_wrap();
		
		$this->fH4 = $this->mrWorkbook->add_format();
		$this->fH4->set_border(1);
		$this->fH4->set_bold();
		$this->fH4->set_size(10);
		$this->fH4->set_align('right');
		$this->fH4->set_align('vcenter');
		$this->fH4->set_fg_color('grey');
		$this->fH4->set_bg_color('grey');
		$this->fH4->set_pattern(2);
		$this->fH4->set_text_wrap();
		
		$this->fH5 = $this->mrWorkbook->add_format();
		$this->fH5->set_border(1);
		$this->fH5->set_bold();
		$this->fH5->set_size(10);
		$this->fH5->set_align('left');
		$this->fH5->set_align('vcenter');
		$this->fH5->set_fg_color('grey');
		$this->fH5->set_bg_color('grey');
		$this->fH5->set_pattern(2);
		$this->fH5->set_text_wrap();
         
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
		
		$this->Komponen = $this->Obj->getDataKomponenIsi($jenis,$data[0]['id'],$data[0]['periode']);
		
		$iumum=0; $itunj=0; $ipot=0;
		for ($i=0; $i<sizeof($this->Komponen); $i++){
			if (is_integer(strpos($this->Komponen[$i]['kolom'],'Tunjangan'))){
				$tunjangan[$itunj]=$this->Komponen[$i];
				$itunj++;
			}elseif (is_integer(strpos($this->Komponen[$i]['kolom'],'Potongan'))){
				$potongan[$ipot]=$this->Komponen[$i];
				$ipot++;
			}else{
				$umum[$iumum]=$this->Komponen[$i];
				$iumum++;
			}
		}
		
		//Set Header yang baris pertama
		$header[0]=array('No','NIP','NAMA','JABATAN','GOL','THP');
		for ($i=0; $i<sizeof($umum) ; $i++){
		    array_push($header[0],$umum[$i]['kolom']);
		}
		
		for ($i=0; $i<sizeof($tunjangan) ; $i++){
		    if ($i==0){
				array_push($header[0],'Tunjangan');
			}else{
				array_push($header[0],'');
			}
		}
		array_push($header[0],'Gaji Kotor');
		for ($i=0; $i<sizeof($potongan) ; $i++){
		    if ($i==0){
				array_push($header[0],'Potongan');
			}else{
				array_push($header[0],'');
			}
		}
		array_push($header[0],'Total Potongan');
		array_push($header[0],'Gaji Bersih');
		array_push($header[0],'Hutang Karyawan');
		
		//Untuk Header yang baris kedua
		$header[1]=array('','','','','','');
		for ($i=0; $i<sizeof($umum) ; $i++){
		    array_push($header[1],'');
		}
		
		for ($i=0; $i<sizeof($tunjangan) ; $i++){
			array_push($header[1],$tunjangan[$i]['kolom']);
			
		}
		array_push($header[1],'');
		for ($i=0; $i<sizeof($potongan) ; $i++){
		    array_push($header[1],$potongan[$i]['kolom']);
		}
		array_push($header[1],'');
		array_push($header[1],'');
		array_push($header[1],'');
		
		$val=array('no','nip','nama','jabatan','gol','thp');
		for ($i=0; $i<sizeof($umum); $i++){
		    array_push($val,$umum[$i]['kolom']);
		}
		for ($i=0; $i<sizeof($tunjangan); $i++){
		    array_push($val,$tunjangan[$i]['kolom']);
		}
		array_push($val,'Gaji Kotor');
		for ($i=0; $i<sizeof($potongan); $i++){
		    array_push($val,$potongan[$i]['kolom']);
		}
		array_push($val,'Total Potongan');
		array_push($val,'Gaji Bersih');
		array_push($val,'Hutang Karyawan');
	
    	$totalKolomKomp=sizeof($val);
    	$jumKolom=$totalKolomKomp;
  		$row++;
		$this->mWorksheets['GajiPegawai']->write($row, 0, 'DAFTAR GAJI PEGAWAI', $this->fH1);
    	$this->mWorksheets['GajiPegawai']->merge_cells($row, 0, $row,$jumKolom-1);
		$row++;
		$this->mWorksheets['GajiPegawai']->write($row, 0, $dataPegawai[0]['periode_text'], $this->fH1);
    	$this->mWorksheets['GajiPegawai']->merge_cells($row, 0, $row,$jumKolom-1);
		$row++;
		$this->mWorksheets['GajiPegawai']->write($row, 0, strtoupper($institusi), $this->fH1);
    	$this->mWorksheets['GajiPegawai']->merge_cells($row, 0, $row,$jumKolom-1);
    			  
		
		//Set Lebar Kolom Awal =0
		for ($i=0; $i<$jumKolom; $i++){
			$size_col[$val[$i]]=0;
		}
		$row++; $k=0;
		$Htemp=$header;
		for ($i=0; $i<sizeof($header); $i++){
			$row++;
			for ($ii=0; $ii<sizeof($val); $ii++){
				$this->mWorksheets['GajiPegawai']->write($row, $ii, $header[$i][$ii], $this->fH3);
    			if ($size_col[$val[$ii]]<strlen($header[$i][$ii])) {$size_col[$val[$ii]]=strlen($header[$i][$ii]);}
    			
    			$bottom=$i+1;
    			if (($Htemp[$i][$ii]!='')&&($bottom<sizeof($Htemp))&&($Htemp[$bottom][$ii]=='')){
					$bottom=$i+1;
					$k++;
					while (($bottom<sizeof($Htemp))&&($Htemp[$bottom][$ii]=='')){
						$merger[$k]['row_awal']=$i+$row;
						$merger[$k]['row_akhir']=$bottom+$row;
						$merger[$k]['col_awal']=$ii;
						$merger[$k]['col_akhir']=$ii;
						$Htemp[$bottom][$ii]='WHY';
						$bottom++;
					}
				}
          
				$left=$ii+1;
    			if (($Htemp[$i][$ii]!='')&&($left<sizeof($val))&&($Htemp[$i][$left]=='')){
					$left=$ii+1;
					$k++;
					while (($left<sizeof($val))&&($Htemp[$i][$left]=='')){
						$merger[$k]['row_awal']=$i+$row;
						$merger[$k]['row_akhir']=$i+$row;
						$merger[$k]['col_awal']=$ii;
						$merger[$k]['col_akhir']=$left;
						$Htemp[$i][$left]='WHY';
						$left++;
					}
				}
          
			}
		} 

		for ($i=1; $i<=sizeof($merger); $i++){
		    $this->mWorksheets['GajiPegawai']->merge_cells($merger[$i]['row_awal'], $merger[$i]['col_awal'], $merger[$i]['row_akhir'], $merger[$i]['col_akhir']);
		}
		//Menulis Angka sebelum data
		$row++;
		for ($i=0; $i<$jumKolom; $i++){
			$this->mWorksheets['GajiPegawai']->write($row, $i, $i+1, $this->fH3);
		}
      
		if (sizeof($data)<=0) {
			$row++;
    		$this->mWorksheets['GajiPegawai']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
    		for ($i=1; $i<$jumKolom; $i++){
				$this->mWorksheets['GajiPegawai']->write($row, $i, '', $this->fH3);
			}
    		$this->mWorksheets['GajiPegawai']->merge_cells($row, 0, $row,$jumKolom-1);
    	} else {
    		$dataPegawai = $data;
			
			$rekap=array();
			
    		for ($i=0; $i<sizeof($dataPegawai); $i++) {
    			$no = $i+1;
    					
    			$row++; $cols=0;
    			$dataPegawai[$i]['no'] = $no;
				$dataPegawai[$i]['nip']=$dataPegawai[$i]['nip'].' ';
				$dataPegawai[$i]['rekening']=$dataPegawai[$i]['rekening'].' ';
				$dataPegawai[$i]['npwp']=$dataPegawai[$i]['npwp'].' ';
				$idpeg = $dataPegawai[$i]['id'];
				$periode = $dataPegawai[$i]['periode_id'];
				$pend_lain = $this->Obj->getDataNominalPendapatanlain($idpeg,$periode);
				$dataPegawai[$i]['pend_lain'] = $pend_lain[0]['nominal'];
				$dataPegawai[$i]['total'] = $dataPegawai[$i]['gaji'];
				$dataPegawai[$i]['jml_gaji_pokok'] = $dataPegawai[$i]['gaji_pokok'];
				$this->Komponen_isi = $this->Obj->getDataKomponenIsi($jenis,$idpeg,$dataPegawai[0]['periode']);
				$totalIsiKolomKomp = sizeof($this->Komponen_isi);
				for ($ii=0; $ii<$totalIsiKolomKomp; $ii++){
					$dataPegawai[$i][$this->Komponen_isi[$ii]['kolom']] = $this->Komponen_isi[$ii]['nominal'];
				}					

				$dataPegawai[$i]['Gaji Kotor']=0;
				$dataPegawai[$i]['Lain']=0;
				$dataPegawai[$i]['Total Potongan']=0;
				for ($ii=0; $ii<sizeof($umum); $ii++) $dataPegawai[$i]['Gaji Kotor'] += $dataPegawai[$i][$umum[$ii]['kolom']];
				for ($ii=0; $ii<sizeof($tunjangan); $ii++) $dataPegawai[$i]['Gaji Kotor'] += $dataPegawai[$i][$tunjangan[$ii]['kolom']];
				for ($ii=0; $ii<sizeof($potongan); $ii++) $dataPegawai[$i]['Total Potongan'] += $dataPegawai[$i][$potongan[$ii]['kolom']];
				for ($ii=0; $ii<sizeof($tunjangan); $ii++) {
					if (is_integer(strpos($tunjangan[$ii]['kolom'],"Tunjangan Lain"))){
						$dataPegawai[$i]['Lain'] += $dataPegawai[$i][$tunjangan[$ii]['kolom']];
						$totallain += abs($dataPegawai[$i][$tunjangan[$ii]['kolom']]);
					}
				}
				$dataPegawai[$i]['Gaji Bersih']=$dataPegawai[$i]['Gaji Kotor']+$dataPegawai[$i]['Total Potongan']-$dataPegawai[$i]['Lain'];
				if ($dataPegawai[$i]['Gaji Bersih']<0){
					$dataPegawai[$i]['Hutang Karyawan']=abs($dataPegawai[$i]['Gaji Bersih']);
					$dataPegawai[$i]['Gaji Bersih']=0;
				}else{
					$dataPegawai[$i]['Gaji Bersih']=$dataPegawai[$i]['Gaji Bersih'];
					$dataPegawai[$i]['Hutang Karyawan']=0;
				}
				
				$totalhutang += $dataPegawai[$i]['Hutang Karyawan'];
				
				//Untuk Pajak
				$dataPegawai[$i]['gaji']=0;
				$dataPegawai[$i]['tunjangan']=0;
				for ($ii=0; $ii<sizeof($umum); $ii++) $dataPegawai[$i]['gaji'] += $dataPegawai[$i][$umum[$ii]['kolom']];
				for ($ii=0; $ii<sizeof($tunjangan); $ii++) {
					$tunjangantetap=array('Tunjangan Jabatan','Tunjangan Tamb. Jabatan','Tunjangan Dosen','Tunjangan Kekaryaan','Tunjangan Study','Tunjangan Komunikasi','Tunjangan Rangkap Jabatan');
					if (in_array($tunjangan[$ii]['kolom'],$tunjangantetap)) $dataPegawai[$i]['tunjangan'] += $dataPegawai[$i][$tunjangan[$ii]['kolom']];
				}
				$dataPegawai[$i]['bruto']=$dataPegawai[$i]['gaji']+$dataPegawai[$i]['tunjangan'];
				$dataPegawai[$i]['biaya_jabatan']=($dataPegawai[$i]['BJB']/100)*$dataPegawai[$i]['bruto'];
				$dataPegawai[$i]['netto']=$dataPegawai[$i]['bruto']-$dataPegawai[$i]['biaya_jabatan']-abs($dataPegawai[$i]['Potongan JAMSOSTEK']);
				$dataPegawai[$i]['pkp']=$dataPegawai[$i]['netto']-$dataPegawai[$i]['PTKP'];
				$dataPegawai[$i]['pajak']=($dataPegawai[$i]['pkp']<0?0:$dataPegawai[$i]['pkp'])*($dataPegawai[$i]['PPH']/100);
				
				//Menghitung Rekapnya
				for ($ii=0; $ii<sizeof($umum); $ii++) $rekapu[$umum[$ii]['kolom']] += abs($dataPegawai[$i][$umum[$ii]['kolom']]);
				for ($ii=0; $ii<sizeof($tunjangan); $ii++) $rekapu['Tunjangan'] += abs($dataPegawai[$i][$tunjangan[$ii]['kolom']]);
				for ($ii=0; $ii<sizeof($potongan); $ii++) $rekap[$potongan[$ii]['kolom']] += abs($dataPegawai[$i][$potongan[$ii]['kolom']]);
				
				//Menulis Datanya
				for ($ii=0; $ii<sizeof($val); $ii++){
					if ($ii>=6){
						$total[$ii] +=$dataPegawai[$i][$val[$ii]];
						if ($dataPegawai[$i][$val[$ii]]<0){
							$dataPegawai[$i][$val[$ii]] *= -1;
						}
						if ($dataPegawai[$i][$val[$ii]]==0){
							$this->mWorksheets['GajiPegawai']->write($row, $ii, '-', $this->fColData);
						}else{
							//$this->mWorksheets['GajiPegawai']->write($row, $ii, number_format($dataPegawai[$i][$val[$ii]],2,',','.'), $this->fColData);
							$this->mWorksheets['GajiPegawai']->write($row, $ii, $dataPegawai[$i][$val[$ii]], $this->fColData);
						}
					}else{
						$this->mWorksheets['GajiPegawai']->write($row, $ii, $dataPegawai[$i][$val[$ii]], $this->fColData2);
					}
					//Mencari Panjang Data Maksimal
    				if ($size_col[$val[$ii]]<strlen($dataPegawai[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataPegawai[$i][$val[$ii]]);}
				}
    		}
			
			//Menulis Total
			$row++;
			$this->mWorksheets['GajiPegawai']->write($row, 0, 'TOTAL KESELURUHAN', $this->fH3);
			for ($i=1; $i<6; $i++){
				$this->mWorksheets['GajiPegawai']->write($row, $i, '', $this->fH3);
			}
			$this->mWorksheets['GajiPegawai']->merge_cells($row, 0, $row,5);
			for ($ii=6; $ii<sizeof($val); $ii++){
				if ($total[$ii]<0){
					$total[$ii] *= -1;
				}
				//$this->mWorksheets['GajiPegawai']->write($row, $ii, number_format($total[$ii],2,',','.'), $this->fH32);
				$this->mWorksheets['GajiPegawai']->write($row, $ii,$total[$ii], $this->fH32);
			}
    				
    		//Setting Lebar Kolom
    		$lebar_max=50;
    		for ($i=0; $i<$jumKolom; $i++){
    			if ($size_col[$val[$i]]>$lebar_max) $size_col[$val[$i]]=$lebar_max;
				$this->mWorksheets['GajiPegawai']->set_column($i,$i,$size_col[$val[$i]]+3);
			}
//================================Akhir Laporan Internal=================================================================================================================================

//================================ Rekap Gaji =================================================================================================================================
			$row=0;
			$this->mWorksheets['Rekap']->write($row, 0, strtoupper($institusi), $this->fH1);
			$this->mWorksheets['Rekap']->merge_cells($row, 0, $row,4);
			
			$row++;
			$this->mWorksheets['Rekap']->write($row, 0, 'REKAP PENGELUARAN GAJI BULAN '.strtoupper($dataPegawai[0]['periode_text']), $this->fH1);
			$this->mWorksheets['Rekap']->merge_cells($row, 0, $row,4);
			
			$row++;
			$this->mWorksheets['Rekap']->write($row, 0, 'No', $this->fH3);
			$this->mWorksheets['Rekap']->write($row, 1, 'Uraian', $this->fH3);
			$this->mWorksheets['Rekap']->write($row, 2, 'Rincian', $this->fH3);
			$this->mWorksheets['Rekap']->write($row, 3, 'Jumlah', $this->fH3);
			$this->mWorksheets['Rekap']->write($row, 4, 'Total', $this->fH3);
			
			$row++;
			$this->mWorksheets['Rekap']->write($row, 0, '1', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 1, 'Gaji Pegawai', $this->fH5);
			$this->mWorksheets['Rekap']->write($row, 2, '', $this->fH4);
			$this->mWorksheets['Rekap']->write($row, 3, '', $this->fH4);
			$this->mWorksheets['Rekap']->write($row, 4, '', $this->fH4);
			$this->mWorksheets['Rekap']->merge_cells($row, 1, $row,4);
				
			$key=array_keys($rekapu);
			for ($i=0; $i<sizeof($key); $i++){
				$row++;
				$this->mWorksheets['Rekap']->write($row, 0, '', $this->fColData2);
				$this->mWorksheets['Rekap']->write($row, 1, '* '.$key[$i], $this->fColData2);
				//$this->mWorksheets['Rekap']->write($row, 2, number_format($rekapu[$key[$i]]<0?-1*$rekapu[$key[$i]]:$rekapu[$key[$i]],2,',','.'), $this->fColData);
				$this->mWorksheets['Rekap']->write($row, 2, $rekapu[$key[$i]]<0?-1*$rekapu[$key[$i]]:$rekapu[$key[$i]], $this->fColData);
				$this->mWorksheets['Rekap']->write($row, 3, '', $this->fColData2);
				$this->mWorksheets['Rekap']->write($row, 4, '', $this->fColData2);
				
				$totalrekap += $rekapu[$key[$i]]<0?-1*$rekapu[$key[$i]]:$rekapu[$key[$i]];
			}
			
			$row++;
			$this->mWorksheets['Rekap']->write($row, 0, '', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 1, 'Gaji Kotor', $this->fH5);
			$this->mWorksheets['Rekap']->write($row, 2, '', $this->fH4);
			//$this->mWorksheets['Rekap']->write($row, 3, number_format($totalrekap,2,',','.'), $this->fH4);
			//$this->mWorksheets['Rekap']->write($row, 4, number_format($totalrekap,2,',','.'), $this->fH4);
			$this->mWorksheets['Rekap']->write($row, 3, $totalrekap, $this->fH4);
			$this->mWorksheets['Rekap']->write($row, 4, $totalrekap, $this->fH4);
			$this->mWorksheets['Rekap']->merge_cells($row, 1, $row,2);
			
			$row++;
			$this->mWorksheets['Rekap']->write($row, 0, '', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 1, 'Potongan', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 2, '', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 3, '', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 4, '', $this->fColData2);
			$this->mWorksheets['Rekap']->merge_cells($row, 1, $row,4);
			$totalpotongan=0;
			$key=array_keys($rekap);
			for ($i=0; $i<sizeof($key); $i++){
				$row++;
				$this->mWorksheets['Rekap']->write($row, 0, '', $this->fColData2);
				$this->mWorksheets['Rekap']->write($row, 1, '* '.$key[$i], $this->fColData2);
				//$this->mWorksheets['Rekap']->write($row, 2, number_format($rekap[$key[$i]]<0?-1*$rekap[$key[$i]]:$rekap[$key[$i]],2,',','.'), $this->fColData);
				$this->mWorksheets['Rekap']->write($row, 2, $rekap[$key[$i]]<0?-1*$rekap[$key[$i]]:$rekap[$key[$i]], $this->fColData);
				$this->mWorksheets['Rekap']->write($row, 3, '', $this->fColData2);
				$this->mWorksheets['Rekap']->write($row, 4, '', $this->fColData2);
				
				$totalpotongan += $rekap[$key[$i]]<0?-1*$rekap[$key[$i]]:$rekap[$key[$i]];
			}
			
			$row++;
			$this->mWorksheets['Rekap']->write($row, 0, '', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 1, '', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 2, '', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 3, '', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 4, '', $this->fColData2);
			
			$row++;
			$this->mWorksheets['Rekap']->write($row, 0, '', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 1, 'Total Potongan', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 2, '', $this->fColData2);
			//$this->mWorksheets['Rekap']->write($row, 3, number_format($totalpotongan,2,',','.'), $this->fColData);
			$this->mWorksheets['Rekap']->write($row, 3, $totalpotongan, $this->fColData);
			$this->mWorksheets['Rekap']->write($row, 4, '', $this->fColData2);
			
			$row++;
			$this->mWorksheets['Rekap']->write($row, 0, '', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 1, 'Hutang Karyawan', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 2, '', $this->fColData2);
			//$this->mWorksheets['Rekap']->write($row, 3, number_format($totalhutang,2,',','.'), $this->fColData);
			$this->mWorksheets['Rekap']->write($row, 3, $totalhutang, $this->fColData);
			$this->mWorksheets['Rekap']->write($row, 4, '', $this->fColData2);
			
			$row++;
			$this->mWorksheets['Rekap']->write($row, 0, '', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 1, 'Total Potongan - Hutang Karyawan', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 2, '', $this->fColData2);
			//$this->mWorksheets['Rekap']->write($row, 3, number_format($totalpotongan-$totalhutang,2,',','.'), $this->fColData);
			$this->mWorksheets['Rekap']->write($row, 3, $totalpotongan-$totalhutang, $this->fColData);
			$this->mWorksheets['Rekap']->write($row, 4, '', $this->fColData2);
			
			$row++;
			$this->mWorksheets['Rekap']->write($row, 0, '', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 1, 'Gaji yang dibayar ke UMG (pajak+natura)', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 2, '', $this->fColData2);
			//$this->mWorksheets['Rekap']->write($row, 3, number_format($totallain,2,',','.'), $this->fColData);
			$this->mWorksheets['Rekap']->write($row, 3, $totallain, $this->fColData);
			$this->mWorksheets['Rekap']->write($row, 4, '', $this->fColData2);
			
			$row++;
			$this->mWorksheets['Rekap']->write($row, 0, '', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 1, 'Total Potongan Seluruh', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 2, '', $this->fColData2);
			//$this->mWorksheets['Rekap']->write($row, 3, number_format(($totalpotongan-$totalhutang)+$totallain,2,',','.'), $this->fColData);
			//$this->mWorksheets['Rekap']->write($row, 4, number_format(($totalpotongan-$totalhutang)+$totallain,2,',','.'), $this->fColData);
			$this->mWorksheets['Rekap']->write($row, 3, ($totalpotongan-$totalhutang)+$totallain, $this->fColData);
			$this->mWorksheets['Rekap']->write($row, 4, ($totalpotongan-$totalhutang)+$totallain, $this->fColData);
			
			$row++;
			$this->mWorksheets['Rekap']->write($row, 0, '', $this->fColData2);
			$this->mWorksheets['Rekap']->write($row, 1, 'Gaji Yang Diterimakan sesudah dipotong potongan', $this->fH5);
			$this->mWorksheets['Rekap']->write($row, 2, '', $this->fH4);
			$this->mWorksheets['Rekap']->write($row, 3, '', $this->fH4);
			//$this->mWorksheets['Rekap']->write($row, 4, number_format($totalrekap-(($totalpotongan-$totalhutang)+$totallain),2,',','.'), $this->fH4);
			$this->mWorksheets['Rekap']->write($row, 4, $totalrekap-(($totalpotongan-$totalhutang)+$totallain), $this->fH4);
			$this->mWorksheets['Rekap']->merge_cells($row, 1, $row,3);
			
			$this->mWorksheets['Rekap']->merge_cells(3, 0, $row,0);
			
			$this->mWorksheets['Rekap']->set_column(0,0,3);
			$this->mWorksheets['Rekap']->set_column(1,1,50);
			$this->mWorksheets['Rekap']->set_column(2,2,25);
			$this->mWorksheets['Rekap']->set_column(3,3,25);
			$this->mWorksheets['Rekap']->set_column(4,4,25);
			
//===================== Rekap Gaji ===================================================================================
            
//================================Laporan Untuk Ke Bank=================================================================================================================================
			$header=array();
			$header[0]=array('No.','NIP','Nama','No. Rekening','Nominal Gaji Ditransfer');
			$val=array('no','nip','nama','rekening','Gaji Bersih');
			
			$row=0;
			$jumKolom=sizeof($val);
			$this->mWorksheets['LaporanBank']->write($row, 0, 'Gaji Bulan '.$dataPegawai[0]['periode_text'], $this->fH1);
			$this->mWorksheets['LaporanBank']->merge_cells($row, 0, $row,$jumKolom-1);
			$row++;
			$this->mWorksheets['LaporanBank']->write($row, 0, strtoupper($institusi), $this->fH1);
			$this->mWorksheets['LaporanBank']->merge_cells($row, 0, $row,$jumKolom-1);
			
			//Set Lebar Kolom Awal =0
			for ($i=0; $i<$jumKolom; $i++){
				$size_col[$val[$i]]=0;
			}
			$row++; $k=0;
			$Htemp=$header;
			for ($i=0; $i<sizeof($header); $i++){
				$row++;
				for ($ii=0; $ii<sizeof($val); $ii++){
					$this->mWorksheets['LaporanBank']->write($row, $ii, $header[$i][$ii], $this->fH3);
	    			if ($size_col[$val[$ii]]<strlen($header[$i][$ii])) {$size_col[$val[$ii]]=strlen($header[$i][$ii]);}
	    			
	    			$bottom=$i+1;
	    			if (($Htemp[$i][$ii]!='')&&($bottom<sizeof($Htemp))&&($Htemp[$bottom][$ii]=='')){
						$bottom=$i+1;
						$k++;
						while (($bottom<sizeof($Htemp))&&($Htemp[$bottom][$ii]=='')){
							$merger[$k]['row_awal']=$i+$row;
							$merger[$k]['row_akhir']=$bottom+$row;
							$merger[$k]['col_awal']=$ii;
							$merger[$k]['col_akhir']=$ii;
							$Htemp[$bottom][$ii]='WHY';
							$bottom++;
						}
					}
	          
					$left=$ii+1;
	    			if (($Htemp[$i][$ii]!='')&&($left<sizeof($val))&&($Htemp[$i][$left]=='')){
						$left=$ii+1;
						$k++;
						while (($left<sizeof($val))&&($Htemp[$i][$left]=='')){
							$merger[$k]['row_awal']=$i+$row;
							$merger[$k]['row_akhir']=$i+$row;
							$merger[$k]['col_awal']=$ii;
							$merger[$k]['col_akhir']=$left;
							$Htemp[$i][$left]='WHY';
							$left++;
						}
					}
	          
				}
			}
			
			for ($i=0; $i<sizeof($dataPegawai); $i++) {
    			$no = $i+1;
    					
    			$row++; $cols=0;
				//Menulis Datanya
				for ($ii=0; $ii<sizeof($val); $ii++){
					if ($ii>=4){
						$total[$ii] +=$dataPegawai[$i][$val[$ii]];
						if ($dataPegawai[$i][$val[$ii]]<0){
							$dataPegawai[$i][$val[$ii]] *= -1;
						}
						//$this->mWorksheets['LaporanBank']->write($row, $ii, number_format($dataPegawai[$i][$val[$ii]],2,',','.'), $this->fColData);
						$this->mWorksheets['LaporanBank']->write($row, $ii, $dataPegawai[$i][$val[$ii]], $this->fColData);
					}else{
						$this->mWorksheets['LaporanBank']->write($row, $ii, $dataPegawai[$i][$val[$ii]], $this->fColData2);
					}
					//Mencari Panjang Data Maksimal
    				if ($size_col[$val[$ii]]<strlen($dataPegawai[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataPegawai[$i][$val[$ii]]);}
				}
    		}
			
			$row++;
			$this->mWorksheets['LaporanBank']->write($row, 0, 'TOTAL KESELURUHAN', $this->fH3);
			for ($i=1; $i<4; $i++){
				$this->mWorksheets['LaporanBank']->write($row, $i, '', $this->fH3);
			}
			$this->mWorksheets['LaporanBank']->merge_cells($row, 0, $row,3);
			for ($ii=4; $ii<sizeof($val); $ii++){
				if ($total[$ii]<0){
					$total[$ii] *= -1;
				}
				//$this->mWorksheets['LaporanBank']->write($row, $ii, number_format($total[$ii],2,',','.'), $this->fH32);
				$this->mWorksheets['LaporanBank']->write($row, $ii,$total[$ii], $this->fH32);
			}
			
    				
    		//Setting Lebar Kolom
    		$lebar_max=50;
    		for ($i=0; $i<$jumKolom; $i++){
    			if ($size_col[$val[$i]]>$lebar_max) $size_col[$val[$i]]=$lebar_max;
				$this->mWorksheets['LaporanBank']->set_column($i,$i,$size_col[$val[$i]]+3);
			}			
//=====================Akhir Laporan Untuk Bank===================================================================================

//================================Laporan Pajak=================================================================================================================================
			$header=array();
			$total=array();
			$header[0]=array('No.','NPWP','Nama','Gaji','Tunjangan','Penghasilan Bruto','Biaya Jabatan','Potongan Jamsostek','Penghasilan Netto','PTKP','PKP','Pajak Terutang');
			$val=array('no','npwp','nama','gaji','tunjangan','bruto','biaya_jabatan','Potongan JAMSOSTEK','netto','PTKP','pkp','pajak');
			
			$row=0;
			$jumKolom=sizeof($val);
			$this->mWorksheets['Pajak']->write($row, 0, 'DAFTAR WAJIB PAJAK PPH 21 (KARYAWAN)', $this->fH1);
			$this->mWorksheets['Pajak']->merge_cells($row, 0, $row,$jumKolom-1);
			$row++;
			$this->mWorksheets['Pajak']->write($row, 0, strtoupper($institusi), $this->fH1);
			$this->mWorksheets['Pajak']->merge_cells($row, 0, $row,$jumKolom-1);
			$row++;
			$this->mWorksheets['Pajak']->write($row, 0, 'UNTUK PAJAK '.strtoupper($dataPegawai[0]['periode_text']), $this->fH1);
			$this->mWorksheets['Pajak']->merge_cells($row, 0, $row,$jumKolom-1);
			
			//Set Lebar Kolom Awal =0
			for ($i=0; $i<$jumKolom; $i++){
				$size_col[$val[$i]]=0;
			}
			$row++; $k=0;
			$Htemp=$header;
			for ($i=0; $i<sizeof($header); $i++){
				$row++;
				for ($ii=0; $ii<sizeof($val); $ii++){
					$this->mWorksheets['Pajak']->write($row, $ii, $header[$i][$ii], $this->fH3);
	    			if ($size_col[$val[$ii]]<strlen($header[$i][$ii])) {$size_col[$val[$ii]]=strlen($header[$i][$ii]);}
	    			
	    			$bottom=$i+1;
	    			if (($Htemp[$i][$ii]!='')&&($bottom<sizeof($Htemp))&&($Htemp[$bottom][$ii]=='')){
						$bottom=$i+1;
						$k++;
						while (($bottom<sizeof($Htemp))&&($Htemp[$bottom][$ii]=='')){
							$merger[$k]['row_awal']=$i+$row;
							$merger[$k]['row_akhir']=$bottom+$row;
							$merger[$k]['col_awal']=$ii;
							$merger[$k]['col_akhir']=$ii;
							$Htemp[$bottom][$ii]='WHY';
							$bottom++;
						}
					}
	          
					$left=$ii+1;
	    			if (($Htemp[$i][$ii]!='')&&($left<sizeof($val))&&($Htemp[$i][$left]=='')){
						$left=$ii+1;
						$k++;
						while (($left<sizeof($val))&&($Htemp[$i][$left]=='')){
							$merger[$k]['row_awal']=$i+$row;
							$merger[$k]['row_akhir']=$i+$row;
							$merger[$k]['col_awal']=$ii;
							$merger[$k]['col_akhir']=$left;
							$Htemp[$i][$left]='WHY';
							$left++;
						}
					}
	          
				}
			}
			
			for ($i=0; $i<sizeof($dataPegawai); $i++) {
    			$no = $i+1;
    					
    			$row++; $cols=0;
				//Menulis Datanya
				for ($ii=0; $ii<sizeof($val); $ii++){
					if ($ii>=3){
						$total[$ii] +=$dataPegawai[$i][$val[$ii]];
						$this->mWorksheets['Pajak']->write($row, $ii, $dataPegawai[$i][$val[$ii]], $this->fColData);
					}else{
						$this->mWorksheets['Pajak']->write($row, $ii, $dataPegawai[$i][$val[$ii]], $this->fColData2);
					}
					//Mencari Panjang Data Maksimal
    				if ($size_col[$val[$ii]]<strlen($dataPegawai[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataPegawai[$i][$val[$ii]]);}
				}
    		}
			
			$row++;
			$this->mWorksheets['Pajak']->write($row, 0, 'TOTAL KESELURUHAN', $this->fH3);
			for ($i=1; $i<3; $i++){
				$this->mWorksheets['Pajak']->write($row, $i, '', $this->fH3);
			}
			$this->mWorksheets['Pajak']->merge_cells($row, 0, $row,2);
			for ($ii=3; $ii<sizeof($val); $ii++){
				$this->mWorksheets['Pajak']->write($row, $ii, $total[$ii], $this->fH32);
			}
			
    				
    		//Setting Lebar Kolom
    		$lebar_max=50;
    		for ($i=0; $i<$jumKolom; $i++){
    			if ($size_col[$val[$i]]>$lebar_max) $size_col[$val[$i]]=$lebar_max;
				$this->mWorksheets['Pajak']->set_column($i,$i,$size_col[$val[$i]]+3);
			}			
//=====================Akhir Laporan Pajak===================================================================================
			
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
	
	function GetCol($nilai){
		$var='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$awal=round($nilai/26);
		$akhir=$nilai % 26;
      
		return $var[$awal-1].$var[$akhir-1];
      
	}
   
}
   

?>