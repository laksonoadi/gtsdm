<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_pak_baru/business/laporan.class.php';
   
class ViewLaporanPak extends XlsResponse
{
   var $mWorksheets = array('Pak');
   
   function GetFileName() {
      // name it whatever you want
      return 'Laporan_PAK_'.date('Ymd').'.xls';
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
      $this->tahun_awal=date('Y')-10;
      $this->tahun_akhir=date('Y')+10;
      
      if(isset($_POST['awal_year'])) {
  				$this->awal = $_POST['awal_year'].'-'.$_POST['awal_mon'].'-'.$_POST['awal_day'];
  		} elseif(isset($_GET['awal'])) {
  				$this->awal = Dispatcher::Instance()->Decrypt($_GET['awal']);
  		} else {
  				$this->awal = date('Y-m').'-01';
  		}
  		$this->label_awal=$this->Obj->IndonesianDate($this->awal,'YYYY-MM-DD');
  		
  		if(isset($_POST['akhir_year'])) {
  				$this->akhir = $_POST['akhir_year'].'-'.$_POST['akhir_mon'].'-'.$_POST['akhir_day'];;
  		} elseif(isset($_GET['akhir'])) {
  				$this->akhir = Dispatcher::Instance()->Decrypt($_GET['akhir']);
  		} else {
  				$this->akhir = date('Y-m').'-'.$this->Obj->getLastDate(date('Y'),date('m'));
  		}
  		$this->label_akhir=$this->Obj->IndonesianDate($this->akhir,'YYYY-MM-DD');
	    
  		if(isset($_POST['unit_kerja'])) {
  				$this->unit_kerja = $_POST['unit_kerja'];
  		} elseif(isset($_GET['unit_kerja'])) {
  				$this->unit_kerja = Dispatcher::Instance()->Decrypt($_GET['unit_kerja']);
  		} else {
  				$this->unit_kerja = 'all';
  		}
			
  		
  		$this->ComboUnitKerja=$this->Obj->GetComboUnitKerja();
  		$this->label_unit_kerja=$this->GetLabelFromCombo($this->ComboUnitKerja,$this->unit_kerja);
  		  				  
		  $totalData = $this->Obj->GetCountDataPak($this->awal,$this->akhir,$this->unit_kerja);
		  $dataPegawai = $this->Obj->GetDataPak(0, $totalData, $this->awal,$this->akhir, $this->unit_kerja);
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
    	
    	$jumKolom=12;
  		
  		$row++;
		  $this->mWorksheets['Pak']->write($row, 0, 'LAPORAN ANGKA KREDIT PEGAWAI', $this->fH1);
    	$this->mWorksheets['Pak']->merge_cells($row, 0, $row,$jumKolom-1);
    	
    	$row++;
		  $this->mWorksheets['Pak']->write($row, 0, 'Periode Awal : '.$this->label_awal , $this->fH2);
		  $this->mWorksheets['Pak']->merge_cells($row, 0, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['Pak']->write($row, 0, 'Periode Akhir : '.$this->label_akhir , $this->fH2);
		  $this->mWorksheets['Pak']->merge_cells($row, 0, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['Pak']->write($row, 0, 'Unit Kerja  : '.$this->label_unit_kerja , $this->fH2);
		  $this->mWorksheets['Pak']->merge_cells($row, 0, $row,$jumKolom-1);
		  
		  //Set Header
		  $header[0]=array('No.','NIP','Nama','L/P','Jabatan','Golongan','Unit Kerja','Angka Kredit','','','Dapat Diangkat','Tanggal Pak');
		  $header[1]=array('','','','','','','','Jumlah Lama','Jumlah Baru','Jumlah','','');
		  //Set Nama Variabel Yang akan ditulis
		  $val=array('no','nip','nama','jenis_kelamin','jabatan','golongan','unit_kerja','total_awal','pak_jumlah_baru','jumlah','dapat_diangkat','tanggal_pak');
		  
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
          $this->mWorksheets['Pak']->write($row, $ii, $header[$i][$ii], $this->fH3);
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
		    $this->mWorksheets['Pak']->merge_cells($merger[$i]['row_awal'], $merger[$i]['col_awal'], $merger[$i]['row_akhir'], $merger[$i]['col_akhir']);
      }
      
      $row++;
      for ($i=0; $i<$jumKolom; $i++){
        $this->mWorksheets['Pak']->write($row, $i, $i+1, $this->fH3);
      }
      
      if (sizeof($data)<=0) {
          $row++;
    			$this->mWorksheets['Pak']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
    			for ($i=1; $i<$jumKolom; $i++){
            $this->mWorksheets['Pak']->write($row, $i, '', $this->fH3);
          }
    			$this->mWorksheets['Pak']->merge_cells($row, 0, $row,$jumKolom-1);
    	} else {
    			$dataPegawai = $data;
    			for ($i=0; $i<sizeof($dataPegawai); $i++) {
    					$no = $i+1;
    					$id = $dataPegawai[$i]['id'];
						//print_r($id);exit;
						$dataId = $dataPegawai[$i]['dataid'];
						//print_r($dataId);exit;
						$dataPegawai[$i]['total_awal'] = $this->Obj->GetDataUnsurPenilaianLamaGroup2($id,$dataId);
						//echo $this->Obj->GetDataUnsurPenilaianLamaGroup2($id,$dataId); exit;
						//print_r($dataPegawai[$i]['total_awal']);exit;
						//$dataPegawai[$i]['total_awal'] = $totalLama[$i]['lama'];
						//$dataPegawai[$i]['total_awal'] = 0;
						$dataPegawai[$i]['jumlah'] = $dataPegawai[$i]['total_awal'] + $dataPegawai[$i]['pak_jumlah_baru'];
						//print_r($dataPegawai[$i]['total_awal']);exit;
    					$row++; $cols=0;
    					$dataPegawai[$i]['no'] = $no;
						
    				  //Menulis Datanya
              for ($ii=0; $ii<sizeof($val); $ii++){
                $this->mWorksheets['Pak']->write($row, $ii, $dataPegawai[$i][$val[$ii]], $this->fColData2);
                //Mencari Panjang Data Maksimal
    				    if ($size_col[$val[$ii]]<strlen($dataPegawai[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataPegawai[$i][$val[$ii]]);}
              }
    			}
    			//Setting Lebar Kolom
    			$lebar_max=50;
    			for ($i=0; $i<$jumKolom; $i++){
    			  if ($size_col[$val[$i]]>$lebar_max) $size_col[$val[$i]]=$lebar_max;
            $this->mWorksheets['Pak']->set_column($i,$i,$size_col[$val[$i]]+3);
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