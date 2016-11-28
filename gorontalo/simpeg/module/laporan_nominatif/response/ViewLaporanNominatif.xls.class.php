<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_nominatif/business/laporan.class.php';
   
class ViewLaporanNominatif extends XlsResponse
{
   var $mWorksheets = array('Nominatif');
   
   function GetFileName() {
      // name it whatever you want
      return 'Laporan_Nominatif_'.date('Ymd').'.xls';
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
  		$this->ComboUnitKerja=$this->Obj->GetComboUnitKerja();
	    
  		if(isset($_POST['unit_kerja'])) {
  				$this->unit_kerja = $_POST['unit_kerja'];
  		} elseif(isset($_GET['unit_kerja'])) {
  				$this->unit_kerja = Dispatcher::Instance()->Decrypt($_GET['unit_kerja']);
  		} else {
  				// $this->unit_kerja = 'all';
  				$this->unit_kerja = $this->ComboUnitKerja[0]['id'];
  		}
			
      if(isset($_POST['jenis']))
        $this->jenis= $_POST['jenis'];
	  elseif(isset($_GET['jenis']))
	    $this->jenis = Dispatcher::Instance()->Decrypt($_GET['jenis']);
      else 
        $this->jenis='CPNS';
  		
  		
  		$this->label_unit_kerja=$this->GetLabelFromCombo($this->ComboUnitKerja,$this->unit_kerja);
  		  				  
		  // $totalData = $this->Obj->GetCountDataNominatif($this->unit_kerja,$this->jenis);
		  $dataPegawai = $this->Obj->GetDataNominatif(0, 0, $this->unit_kerja,$this->jenis);
		  $totalData = $this->Obj->GetCountDataNominatif();
  		$data=$dataPegawai;
  		$row=-1;
  		
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
    	
    	$jumKolom=17;
  		$row++;
		  $this->mWorksheets['Nominatif']->write($row, 0, 'LAPORAN NOMINATIF', $this->fH1);
    	$this->mWorksheets['Nominatif']->merge_cells($row, 0, $row,$jumKolom-1);
    			  
		  $row++;
		  $this->mWorksheets['Nominatif']->write($row, 0, 'Unit Kerja  : '.$this->label_unit_kerja , $this->fH2);
		  $this->mWorksheets['Nominatif']->merge_cells($row, 0, $row,$jumKolom-1);
    			  
		  $row++;
		  $this->mWorksheets['Nominatif']->write($row, 0, 'Jenis Pegawai  : '.$this->jenis , $this->fH2);
		  $this->mWorksheets['Nominatif']->merge_cells($row, 0, $row,$jumKolom-1);
		  
		  //Set Header
		  $header[0]=array(
			  'No.','Nama','Tempat Lahir','Tgl/Bln/Thn Lahir','NIP',
			  'TMT CPNS','TMT PNS', 'Gol/Ruang Terakhir', 'Berkala', 'Status Perkawinan',
			  'Agama', 'Status Hukum', 'Status Pegawai', 'Instansi Lama', 'TMT Jabatan',
			  'Eselon', 'Diklat Perjenjangan'
		  );
		  //Set Nama Variabel Yang akan ditulis
		  $val=array(
			  'no','nama','pegTmpLahir','tanggal_lahir','nip',
			  'pegCpnsTmt','pegPnsTmt','golongan','berkala', 'statnkhNama',
			  'agmNama','statrPegawai','jenis_pegawai','instansi_lama', 'jabatan_tmt',
			  'jbtnEselon','latihan'
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
          $this->mWorksheets['Nominatif']->write($row, $ii, $header[$i][$ii], $this->fH3);
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
		    $this->mWorksheets['Nominatif']->merge_cells($merger[$i]['row_awal'], $merger[$i]['col_awal'], $merger[$i]['row_akhir'], $merger[$i]['col_akhir']);
      }
      //Menulis Angka sebelum data
      $row++;
      for ($i=0; $i<$jumKolom; $i++){
        $this->mWorksheets['Nominatif']->write($row, $i, $i+1, $this->fH3);
      }
      
      if (sizeof($data)<=0) {
          $row++;
    			$this->mWorksheets['Nominatif']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
    			for ($i=1; $i<$jumKolom; $i++){
            $this->mWorksheets['Nominatif']->write($row, $i, '', $this->fH3);
          }
    			$this->mWorksheets['Nominatif']->merge_cells($row, 0, $row,$jumKolom-1);
    	} else {
    			$dataPegawai = $data;
          
    			for ($i=0; $i<sizeof($dataPegawai); $i++) {
						$thn = $dataPegawai[$i]['latihan_tahun'];
						$nama = $dataPegawai[$i]['latihan_nama'];
						// $jam = $dataPegawai[$i]['latihan_jam'];

						$thn = explode(',', $thn);  
						$nama = explode(',', $nama);
						if(!empty($thn)) {
							$x = array();
							foreach ($thn as $key => $th) {
								if(!empty($th))$x[] = $th . ' : ' . $nama[$key];
							}
							$dataPegawai[$i]['latihan'] = implode("\n",$x);
						}else{
							$dataPegawai[$i]['latihan'] = '';
						}

    					$no = $i+1;
    					
    					$row++; $cols=0;
    					$dataPegawai[$i]['no'] = $no;
    					$dataPegawai[$i]['tanggal_pengangkatan_cpns']=$this->Obj->IndonesianDate($dataPegawai[$i]['tanggal_pengangkatan_cpns'],'YYYY-MM-DD');
              
              //Menulis Datanya
              for ($ii=0; $ii<sizeof($val); $ii++){
                $this->mWorksheets['Nominatif']->write($row, $ii, $dataPegawai[$i][$val[$ii]], $this->fColData2);
                //Mencari Panjang Data Maksimal
    				    if ($size_col[$val[$ii]]<strlen($dataPegawai[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataPegawai[$i][$val[$ii]]);}
              }
    			}
    				
    			//Setting Lebar Kolom
    			$lebar_max=50;
    			for ($i=0; $i<$jumKolom; $i++){
    			  if ($size_col[$val[$i]]>$lebar_max) $size_col[$val[$i]]=$lebar_max;
            $this->mWorksheets['Nominatif']->set_column($i,$i,$size_col[$val[$i]]+3);
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