<?php
set_time_limit(0);

require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_sertifikasi/business/sertifikasi.class.php';
   
class ViewDetailSertifikasi extends XlsResponse{
	var $mWorksheets = array('Daftar');
   
	function GetFileName() {
		// name it whatever you want
		return 'Daftar_Sertifikasi_Dosen_'.date('Ymd').'.xls';
	}
   
	function GetLabelFromCombo($ArrData,$Nilai){
		for ($i=0; $i<sizeof($ArrData); $i++){
			if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
		}
		return '';
	}
   
	function ProcessRequest(){
		$Obj = new Sertifikasi();
		$_POST = $_POST->AsArray();
		$_GET = $_GET->AsArray();
		if ($_POST['srtfkId']!=''){
			$srtfkId=$_POST['srtfkId'];
			$srtfkdetHasilAkhir=$_POST['srtfkdetHasilAkhir'];
		}else if ($_GET['srtfkId']!=''){
			$srtfkId=$_GET['srtfkId'];
			$srtfkdetHasilAkhir=$_GET['srtfkdetHasilAkhir'];
		}else{
			$this->srtfkPeriodeAwal=date("Y-")."01-01";
			$this->srtfkPeriodeAkhir=date("Y-")."12-31";
			$this->srtfkTahun=date("Y");
		} 
		
		if ($srtfkId!=''){
			$srtfk = $Obj->GetUsulanSertifikasiById($srtfkId);
			$this->data['srtfkId']=$srtfk[0]['srtfkId'];
			$this->data['srtfkPeriodeAwal']=$srtfk[0]['srtfkPeriodeAwal'];
			$this->data['srtfkPeriodeAwal_label']=$Obj->IndonesianDate($srtfk[0]['srtfkPeriodeAwal'],'YYYY-MM-DD');
			$this->data['srtfkPeriodeAkhir']=$srtfk[0]['srtfkPeriodeAkhir'];
			$this->data['srtfkPeriodeAkhir_label']=$Obj->IndonesianDate($srtfk[0]['srtfkPeriodeAkhir'],'YYYY-MM-DD');
			$this->data['srtfkTahun']=$srtfk[0]['srtfkTahun'];
			$this->data['srtfkdetHasilAkhir']=$srtfkdetHasilAkhir;
			$this->datalist['srtfkPeserta'] = $Obj->GetListPesertaSertifikasiByIdDetail($srtfkId,$srtfkdetHasilAkhir);
		}
		
  		$row=-1;
  		
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
		
		//Set Header
		$header[0]=array('No.','Nomor Sertifikat','Nomor Peserta','NIP/NIK','Nama','L/P','Jabatan Akademik','Pangkat','Alamat','Kontak','Tempat dan Tangal Lahir','Institusi','','Bidang Ilmu','','TMMD','Pendidikan','','','Penilaian','','','','','','ATDL','Penilai','');
		$header[1]=array('','','','','','','','','','','','Kode','Nama','Kode','Nama','','S1','S2','S3','NTA','Persep','Person','Gab PAK','Konst','Hasil Akhir','','NIRA Asesor I','NIRA Asesor II');
    	
    	$jumKolom=sizeof($header[0]);
		
		if ($_GET['srtfkId']!='ALL'){
			$row++;
			$this->mWorksheets['Daftar']->write($row, 0, strtoupper('DAFTAR SERTIFIKASI DOSEN TAHUN '.$this->data['srtfkTahun']), $this->fH1);
			$this->mWorksheets['Daftar']->merge_cells($row, 0, $row,$jumKolom-1);
			$row++;
			$this->mWorksheets['Daftar']->write($row, 0, strtoupper($this->data['srtfkPeriodeAwal_label'].' s/d '.$this->data['srtfkPeriodeAkhir_label']), $this->fH1);
			$this->mWorksheets['Daftar']->merge_cells($row, 0, $row,$jumKolom-1);
			$row++;
			$this->mWorksheets['Daftar']->write($row, 0, 'DENGAN KRITERIA KELULUSAN : '.$this->data['srtfkdetHasilAkhir'], $this->fH1);
			$this->mWorksheets['Daftar']->merge_cells($row, 0, $row,$jumKolom-1);
		}else{
			$row++;
			$this->mWorksheets['Daftar']->write($row, 0, strtoupper('DAFTAR SERTIFIKASI DOSEN KESELURUHAN'), $this->fH1);
			$this->mWorksheets['Daftar']->merge_cells($row, 0, $row,$jumKolom-1);
		}
						
		//Set Nama Variabel Yang akan ditulis
		$val=array(
						'no',
						'srtfkdetNo',
						'srtfkdetNoPeserta',
						'srtfkdetNip',
						'srtfkdetNama',
						'srtfkdetJenisKelamin',
						'srtfkdetJabfungrNama',
						'srtfkdetPktgolrId',
						'srtfkdetAlamat',
						'srtfkdetKontak',
						'srtfkdetTTL',
						'srtfkdetInstitusiKode',
						'srtfkdetInstitusiNama',
						'srtfkdetBidangKode',
						'srtfkdetBidangNama',
						'srtfkdetTMMD',
						'srtfkdetS1',
						'srtfkdetS2',
						'srtfkdetS3',
						'srtfkdetNTA',
						'srtfkdetPersep',
						'srtfkdetPerson',
						'srtfkdetGabPAK',
						'srtfkdetKonst',
						'srtfkdetHasilAkhir',
						'srtfkdetATDL',
						'srtfkdetAsesorI','srtfkdetAsesorII'
						);
		
		  
		//Set Lebar Kolom Awal =0
		for ($i=0; $i<$jumKolom; $i++){
			$size_col[$val[$i]]=0;
		}
      
		  
		$row++; $k=0;
		$Htemp=$header;
		for ($i=0; $i<sizeof($header); $i++){
			$row++;
			for ($ii=0; $ii<sizeof($val); $ii++){
				$this->mWorksheets['Daftar']->write($row, $ii, $header[$i][$ii], $this->fH3);
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
		    $this->mWorksheets['Daftar']->merge_cells($merger[$i]['row_awal'], $merger[$i]['col_awal'], $merger[$i]['row_akhir'], $merger[$i]['col_akhir']);
		}
		//Menulis Angka sebelum data
		$row++;
		for ($i=0; $i<$jumKolom; $i++){
			$this->mWorksheets['Daftar']->write($row, $i, $i+1, $this->fH3);
		}
      
		$dataPegawai = $this->datalist['srtfkPeserta'];
		if (sizeof($dataPegawai)<=0) {
			$row++;
    		$this->mWorksheets['Daftar']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
    		for ($i=1; $i<$jumKolom; $i++){
				$this->mWorksheets['Daftar']->write($row, $i, '', $this->fH3);
			}
    		$this->mWorksheets['Daftar']->merge_cells($row, 0, $row,$jumKolom-1);
    	} else {
    		for ($i=0; $i<sizeof($dataPegawai); $i++) {
    			$no = $i+1;
				$row++; $cols=0;
    			$dataPegawai[$i]['no'] = $no;
				$dataPegawai[$i]['srtfkdetNo'] = empty($dataPegawai[$i]['srtfkdetNo'])?'-':$dataPegawai[$i]['srtfkdetNo'].' ';
				$dataPegawai[$i]['srtfkdetNoPeserta'] .= ' ';
				$dataPegawai[$i]['srtfkdetNip'] .= ' ';
				$dataPegawai[$i]['srtfkdetAsesorI'] .= ' ';
				$dataPegawai[$i]['srtfkdetAsesorII'] .= ' ';
				//Menulis Datanya
				for ($ii=0; $ii<sizeof($val); $ii++){
					$this->mWorksheets['Daftar']->write($row, $ii, $dataPegawai[$i][$val[$ii]], $this->fColData2);
					//Mencari Panjang Data Maksimal
    			    if ($size_col[$val[$ii]]<strlen($dataPegawai[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataPegawai[$i][$val[$ii]]);}
				}
    		}
    				
    		//Setting Lebar Kolom
    		$lebar_max=50;
    		for ($i=0; $i<$jumKolom; $i++){
				if ($size_col[$val[$i]]>$lebar_max) $size_col[$val[$i]]=$lebar_max;
				$this->mWorksheets['Daftar']->set_column($i,$i,$size_col[$val[$i]]+3);
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