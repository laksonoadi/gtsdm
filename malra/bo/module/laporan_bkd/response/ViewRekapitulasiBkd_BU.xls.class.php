<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_bkd/business/laporan.class.php';
   
class ViewRekapitulasiBkd extends XlsResponse
{
   var $mWorksheets = array('Bkd');
   
   function GetFileName() {
      // name it whatever you want
      return 'Laporan_Rekapitulasi_BKD_'.date('Ymd').'.xls';
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
		// $totalData		= $this->Obj->GetCountDataBkd($this->unit_kerja, $this->pangkat_golongan, $this->fungsional, $this->pendidikan);
		// $dataPegawai	= $this->Obj->GetDataBkd(0, $totalData, $this->unit_kerja, $this->pangkat_golongan, $this->fungsional, $this->pendidikan);

		$dataFakultas	= $this->Obj->GetDataFakultas($this->unit_kerja);

		for ($i=0; $i<sizeof($dataFakultas); $i++) {
			$dataPegawai[$dataFakultas[$i][idFak]]	= $this->Obj->GetDataRekapitulasiBkd($this->unit_kerja, $this->pangkat_golongan, $this->fungsional, $this->pendidikan, $dataFakultas[$i][nameFak]);
			$paramQuery		= $dataPegawai[$dataFakultas[$i][idFak]];
			$pegId			= $paramQuery[0][id];
			
			// get sks ganjil '''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
			$getSksPendGanjil[$dataFakultas[$i][idFak]]	= $this->Obj->getSksPendGanjil($pegId);
			$getSksPenlGanjil[$dataFakultas[$i][idFak]]	= $this->Obj->getSksPenlGanjil($pegId);
			$getSksPengGanjil[$dataFakultas[$i][idFak]]	= $this->Obj->getSksPengGanjil($pegId);
			$getSksPenuGanjil[$dataFakultas[$i][idFak]]	= $this->Obj->getSksPenuGanjil($pegId);

			// get sks genap ''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
			$getSksPendGenap[$dataFakultas[$i][idFak]]	= $this->Obj->getSksPendGenap($pegId);
			$getSksPenlGenap[$dataFakultas[$i][idFak]]	= $this->Obj->getSksPenlGenap($pegId);
			$getSksPengGenap[$dataFakultas[$i][idFak]]	= $this->Obj->getSksPengGenap($pegId);
			$getSksPenuGenap[$dataFakultas[$i][idFak]]	= $this->Obj->getSksPenuGenap($pegId);

			$getSksProf[$dataFakultas[$i][idFak]]	= $this->Obj->getSksProf($pegId);
		}

  		$dataFak	 	= $dataFakultas;
  		$row	 		= -1;
  		
  		$this->fH1 = $this->mrWorkbook->add_format();
		$this->fH1->set_bold();
		$this->fH1->set_size(12);
		$this->fH1->set_align('vcenter');
		$this->fH1->set_align('center');

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
					'nameFak','','',
					'','','','',
					'','','','',
					'',
					'',
					'');
      
		$val2=array(
					'no','nameFak','',
					'','','','',
					'','','','',
					'',
					'',
					'');
      
      //Set Lebar Kolom Awal =0
		for ($i=0; $i<$jumKolom; $i++){
			$size_col[$val[$i]]=0;
		}
      
      // Header/Judul Table
      $row++; $k=0;
      $Htemp=$header;
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
			$this->mWorksheets['Bkd']->merge_cells($merger[$i]['row_awal'], $merger[$i]['col_awal'], $merger[$i]['row_akhir'], $merger[$i]['col_akhir']);
		}
      
      $row++;//$row++;
      for ($i=0; $i<$jumKolom; $i++){
        $this->mWorksheets['Bkd']->write($row, $i, $i+1, $this->fH3);
      }
      
      if (sizeof($dataFak)<=0) {
          $row++;
    			$this->mWorksheets['Bkd']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
    			for ($i=1; $i<$jumKolom; $i++){
					$this->mWorksheets['Bkd']->write($row, $i, '', $this->fH3);
				}
    			$this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);
    	} else {
    			$dataFakultas = $dataFak;
    			for ($i=0; $i<sizeof($dataFakultas); $i++) {
					$row++; 
					$cols=0;
					for ($ii=0; $ii<sizeof($val); $ii++){
						$this->mWorksheets['Bkd']->write($row, $ii, $dataFakultas[$i][$val[$ii]], $this->fh3);

						// Mencari Panjang Data Maksimal
						if ($size_col[$val[$ii]]<strlen($dataFakultas[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataFakultas[$i][$val[$ii]]);}

						$dataPeg	= $dataPegawai[$dataFakultas[$i][idFak]];
						// for ($x=0; $x<sizeof($dataPeg); $x++) {
							// for ($y=0; $y<sizeof($val2); $y++){
								// $this->mWorksheets['Bkd']->write($row, $y, $dataFakultas[$x][$val2[$y]], $this->fColData2);
								// Mencari Panjang Data Maksimal
								// if ($size_col[$val2[$y]]<strlen($dataFakultas[$x][$val2[$y]])) {$size_col[$val2[$y]]=strlen($dataFakultas[$x][$val2[$y]]);}
							// }
						// }
					}

					$this->mWorksheets['Bkd']->merge_cells($row, 0, $row,$jumKolom-1);
    			}

    			// for ($i=0; $i<sizeof($dataFakultas); $i++) {
					// $no = $i+1;
					
					// $row++; $cols=0;
					// $dataFakultas[$i]['no'] = $no;
					// for ($ii=0; $ii<sizeof($val); $ii++){
						// $this->mWorksheets['Bkd']->write($row, $ii, $dataFakultas[$i][$val[$ii]], $this->fColData2);
						// Mencari Panjang Data Maksimal
						// if ($size_col[$val[$ii]]<strlen($dataFakultas[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataFakultas[$i][$val[$ii]]);}
					// }
    			// }

    			//Setting Lebar Kolom
    			$lebar_max=50;
    			for ($i=0; $i<$jumKolom; $i++){
					if ($size_col[$val[$i]]>$lebar_max) $size_col[$val[$i]]=$lebar_max;
					$this->mWorksheets['Bkd']->set_column($i,$i,$size_col[$val[$i]]+3);
				}
        }
        
   }
   
   function GetCol($nilai){
      $var='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $awal=round($nilai/26);
      $akhir=$nilai % 26;
      
      return $var[$awal-1].$var[$akhir-1];
      
   }
   
}
   

?>