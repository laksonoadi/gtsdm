<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_pegawai_ultah/business/laporan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';
   
class ViewLaporanDaftar extends XlsResponse
{
   var $mWorksheets = array('UlangTahun');
   
   function GetFileName() {
      // name it whatever you want
      return 'Laporan_Ulang_Tahun_'.date('Ymd').'.xls';
   }
   
   function GetLabelFromCombo($ArrData,$Nilai){
      for ($i=0; $i<sizeof($ArrData); $i++){
        if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
      }
      return '--Semua--';
   }
   
   function ProcessRequest()
   {
        $this->Obj = new Laporan;
        $this->ObjSatker = new SatuanKerja;
        $this->tahun_awal=date('Y')-10;
        $this->tahun_akhir=date('Y')+10;
      
        if(isset($_POST['cari'])) {
            $this->Obj->berdasarkan = strval($_POST['berdasarkan']);
            $this->Obj->urutan = strval($_POST['urutan']);
        } elseif(isset($_GET['cari'])) {
            $this->Obj->berdasarkan = strval($_GET['berdasarkan']);
            $this->Obj->urutan = strval($_GET['urutan']);
        } else {
            $this->Obj->berdasarkan = 'bulan_ini';
            $this->Obj->urutan = 'ASC';
        }
        switch($this->Obj->berdasarkan) {
            case 'tanggal': $this->label_berdasarkan = 'Tanggal'; break;
            case 'hari_ini': $this->label_berdasarkan = 'Hari Ini'; break;
            case 'minggu_ini': $this->label_berdasarkan = 'Minggu Ini'; break;
            case 'bulan_ini': $this->label_berdasarkan = 'Bulan Ini'; break;
            case 'kemarin': $this->label_berdasarkan = 'Kemarin'; break;
            case 'minggu_kemarin': $this->label_berdasarkan = 'Minggu Kemarin'; break;
            case 'bulan_kemarin': $this->label_berdasarkan = 'Bulan Kemarin'; break;
        }
        if($this->Obj->urutan == 'ASC')
            $this->label_urutan = 'Dari Rendah Ke Tinggi';
        else
            $this->label_urutan = 'Dari Tinggi Ke Rendah';
        
        if($this->Obj->berdasarkan == 'tanggal'){
            $this->Obj->awal = $_POST['awal_year'].'-'.$_POST['awal_mon'].'-'.$_POST['awal_day'];
            $this->Obj->akhir = $_POST['akhir_year'].'-'.$_POST['akhir_mon'].'-'.$_POST['akhir_day'];
        } else {
            $this->Obj->awal = '';
            $this->Obj->akhir = '';
        }
        // Strip the year part
        $label_awal = $this->Obj->IndonesianDate($this->Obj->awal, 'YYYY-MM-DD');
        $this->label_awal = substr($label_awal, 0, strrpos($label_awal, ' '));
        
        $label_akhir = $this->Obj->IndonesianDate($this->Obj->akhir, 'YYYY-MM-DD');
        $this->label_akhir = substr($label_akhir, 0, strrpos($label_akhir, ' '));
        
        $this->Obj->getVariabelGlobal();
		$field = array_keys($this->Obj->field);
		
  		$dataPegawai = $this->Obj->GetDaftarPegawai();
        
  		$data = $dataPegawai;
  		$row = -1;
  		
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
		
    	$this->fColData3 = $this->mrWorkbook->add_format();
    	$this->fColData3->set_border(1);   
    	$this->fColData3->set_size(10);
    	$this->fColData3->set_align('left');
    	$this->fColData3->set_align('top');
    	$this->fColData3->set_text_wrap();
		$this->fColData3->set_num_format('00');
    	
    	$jumKolom = 9;
  		
  		  $row++;
		  $this->mWorksheets['UlangTahun']->write($row, 0, 'DAFTAR PEGAWAI ULANG TAHUN', $this->fH1);
    	  $this->mWorksheets['UlangTahun']->merge_cells($row, 0, $row,$jumKolom-1);
          
  		  $row++;
		  $this->mWorksheets['UlangTahun']->write($row, 0, $this->Obj->IndonesianDate(date('Y-m-d'), 'YYYY-MM-DD'), $this->fH1);
    	  $this->mWorksheets['UlangTahun']->merge_cells($row, 0, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['UlangTahun']->write($row, 0, 'Jenis Urutan : '.$this->label_urutan , $this->fH2);
		  $this->mWorksheets['UlangTahun']->merge_cells($row, 0, $row,$jumKolom-1);
          
    	  $row++;
		  $this->mWorksheets['UlangTahun']->write($row, 0, 'Ulang Tahun Pada : '.$this->label_berdasarkan , $this->fH2);
		  $this->mWorksheets['UlangTahun']->merge_cells($row, 0, $row,$jumKolom-1);
		  
          if($this->Obj->berdasarkan == 'tanggal') {
              $row++;
              $this->mWorksheets['UlangTahun']->write($row, 0, 'Pada Tanggal : '.$this->label_awal.' - '.$this->label_akhir , $this->fH2);
              $this->mWorksheets['UlangTahun']->merge_cells($row, 0, $row,$jumKolom-1);
          }
		  
		  //Set Header
		  $header[0] = array('No', 'Nama Pegawai', 'NIP Pegawai', 'Alamat', '', '', 'TTL', '', 'Usia');
          $header[1] = array('', '', '', 'RT/RW', 'Kelurahan', 'Kecamatan', 'Tempat Lahir', 'Tanggal Lahir', '');
		  //Set Nama Variabel Yang akan ditulis
		  $val = array('no', 'pegNama', 'pegKodeResmi', 'pegAlamat', 'pegKelurahan', 'pegKecamatan', 'pegTmpLahir', 'pegTglLahir', 'usia');
		  
		  //Set Lebar Kolom Awal =0
		  for ($i=0; $i<$jumKolom; $i++){
          $size_col[$val[$i]]=0;
      }
      
      //Menulis Header/Judul Table
      $row++; $k=0;
      $Htemp=$header;
      for ($i=0; $i<sizeof($header); $i++){
        $row++;
        for ($ii=0; $ii<sizeof($val); $ii++){
          $this->mWorksheets['UlangTahun']->write($row, $ii, $header[$i][$ii], $this->fH3);
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
		    $this->mWorksheets['UlangTahun']->merge_cells($merger[$i]['row_awal'], $merger[$i]['col_awal'], $merger[$i]['row_akhir'], $merger[$i]['col_akhir']);
      }
      
      $row++;
      for ($i=0; $i<$jumKolom; $i++){
        $this->mWorksheets['UlangTahun']->write($row, $i, $i+1, $this->fH3);
      }
      
      if (sizeof($data)<=0) {
          $row++;
    			$this->mWorksheets['UlangTahun']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
    			for ($i=1; $i<$jumKolom; $i++){
            $this->mWorksheets['UlangTahun']->write($row, $i, '', $this->fH3);
          }
    			$this->mWorksheets['UlangTahun']->merge_cells($row, 0, $row,$jumKolom-1);
    	} else {
    			$dataPegawai = $data;
    			for ($i=0; $i<sizeof($dataPegawai); $i++) {
					$no = $i+1;
					
					$row++; $cols=0;
					$dataPegawai[$i]['no'] = $no;
					for ($ii=0; $ii<sizeof($val); $ii++){
						if($val[$ii] == 'pegKodeResmi') {
							$this->mWorksheets['UlangTahun']->write($row, $ii, $dataPegawai[$i][$val[$ii]], $this->fColData3);
						} else {
							$this->mWorksheets['UlangTahun']->write($row, $ii, $dataPegawai[$i][$val[$ii]], $this->fColData2);
						}
						//Mencari Panjang Data Maksimal
						if ($size_col[$val[$ii]]<strlen($dataPegawai[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataPegawai[$i][$val[$ii]]);}
              }
    			}
    			//Setting Lebar Kolom
    			$lebar_max=50;
    			for ($i=0; $i<$jumKolom; $i++){
    			  if ($size_col[$val[$i]]>$lebar_max) $size_col[$val[$i]]=$lebar_max;
            $this->mWorksheets['UlangTahun']->set_column($i,$i,$size_col[$val[$i]]+3);
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