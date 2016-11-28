<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_pegawai_jenis/business/laporan.class.php';
   
class ViewLaporanPegawai extends XlsResponse
{
   var $mWorksheets = array('Pegawai');
   
   function GetFileName() {
      // name it whatever you want
      return 'Laporan_Pegawai_'.date('Ymd').'.xls';
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
  		
  		if(isset($_POST['jabatan_fungsional'])) {
  				$this->jabatan_fungsional = $_POST['jabatan_fungsional'];
  		} elseif(isset($_GET['jabatan_fungsional'])) {
  				$this->jabatan_fungsional = Dispatcher::Instance()->Decrypt($_GET['jabatan_fungsional']);
  		} else {
  				$this->jabatan_fungsional = 'all';
  		}
      
      $this->ComboJabatanFungsional=$this->Obj->GetComboJabatanFungsional();
  		$this->label_jabatan_fungsional=$this->GetLabelFromCombo($this->ComboJabatanFungsional,$this->jabatan_fungsional);
	
      //create paging 
      $this->ComboUnitKerja=$this->Obj->GetComboUnitKerja();
      $this->ComboJenisPegawai=$this->Obj->GetComboJenisPegawai();
      
      $totalData = sizeof($this->ComboUnitKerja);
		  $dataPegawai = $this->Obj->GetDataPegawai($startRec, $itemViewed,$this->jabatan_fungsional);
  		for ($i=0; $i<sizeof($dataPegawai); $i++){
  		  $this->dataJumlah[$dataPegawai[$i]['unit_kerja']][$dataPegawai[$i]['jenis']][$dataPegawai[$i]['jenis_kelamin']]=$dataPegawai[$i]['jumlah'];
      }
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
    	
    	$totalKolomJenisPegawai=sizeof($this->ComboJenisPegawai);
    	$jumKolom=$totalKolomJenisPegawai+4;
  		$row++;
		  $this->mWorksheets['Pegawai']->write($row, 0, 'LAPORAN JUMLAH PEGAWAI BERDASARKAN JENIS PEGAWAI', $this->fH1);
    	$this->mWorksheets['Pegawai']->merge_cells($row, 0, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['Pegawai']->write($row, 0, 'Jenis Jabatan Fungsional  : '.$this->label_jabatan_fungsional , $this->fH2);
		  $this->mWorksheets['Pegawai']->merge_cells($row, 0, $row,$jumKolom-1);
		  
		  //Set Header
		  $header[0]=array('No.','Unit Kerja');
		  for ($i=0; $i<$totalKolomJenisPegawai; $i++){
		    if ($i==0){
		      array_push($header[0],'Jenis Pegawai');
		      array_push($header[0],'');
        }else{
          array_push($header[0],'');
		      array_push($header[0],'');
        }
      }
      array_push($header[0],'');
		  array_push($header[0],'');
		  
		  $header[1]=array('','');
		  for ($i=0; $i<$totalKolomJenisPegawai; $i++){
		    array_push($header[1],$this->ComboJenisPegawai[$i]['name']);
		    array_push($header[1],'');
      }
      array_push($header[1],'Jumlah');
		  array_push($header[1],'');
		  
		  $header[2]=array('1','2');
		  for ($i=0; $i<=$totalKolomJenisPegawai; $i++){
		    array_push($header[2],$i+3);
		    array_push($header[2],'');
      }
      
      $header[3]=array(' ','Jenis Kelamin');
		  for ($i=0; $i<=$totalKolomJenisPegawai; $i++){
		    array_push($header[3],'L');
		    array_push($header[3],'P');
      }
		  
		  //Set Nama Variabel Yang akan ditulis
		  $val=array('no','unit_kerja');
		  for ($i=0; $i<$totalKolomJenisPegawai; $i++){
		    array_push($val,$this->ComboJenisPegawai[$i]['id'].'_l');
		    array_push($val,$this->ComboJenisPegawai[$i]['id'].'_p');
      }
      array_push($val,'jml_l');
		  array_push($val,'jml_p');
		  
		  //Set Lebar Kolom Awal =0
		  for ($i=0; $i<$jumKolom; $i++){
          $size_col[$val[$i]]=0;
      }
		  
		  $row++; $k=0;
      $Htemp=$header;
      for ($i=0; $i<sizeof($header); $i++){
        $row++;
        
        for ($ii=0; $ii<sizeof($val); $ii++){ 
          $this->mWorksheets['Pegawai']->write($row, $ii, $header[$i][$ii], $this->fH3);
    			if ($size_col[$val[$ii]]<strlen($header[$i][$ii])) {$size_col[$val[$ii]]=strlen($header[$i][$ii]);}
    			
    			$bottom=$i+1;
    			if (($header[$i][$ii]!='')&&($Htemp[$i][$ii]!='')&&($bottom<sizeof($Htemp))&&($Htemp[$bottom][$ii]=='')){
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
    			if (($header[$i][$ii]!='')&&($Htemp[$i][$ii]!='')&&($left<sizeof($val))&&($Htemp[$i][$left]=='')){
    			   $left=$ii+1;
    			   
    			   /*print_r($header[$i][$ii]);
             print_r($Htemp[$i][$left]);*/
    			   $k++;
    			   while (($left<sizeof($val))&&($Htemp[$i][$left]=='')){
    			       $merger[$k]['row_awal']=$row;
        			   $merger[$k]['row_akhir']=$row;
        			   $merger[$k]['col_awal']=$ii;
        			   $merger[$k]['col_akhir']=$left;
        			   $Htemp[$i][$left]='WHY';
        			   $left++;
             }
          }
          
        }
      } 
      
		  for ($i=1; $i<=sizeof($merger); $i++){
		    $this->mWorksheets['Pegawai']->merge_cells($merger[$i]['row_awal'], $merger[$i]['col_awal'], $merger[$i]['row_akhir'], $merger[$i]['col_akhir']);
      }
      //print_r($merger); exit();
      /*//Menulis Angka sebelum data
      $row++;
      for ($i=0; $i<$jumKolom; $i++){
        $this->mWorksheets['Pegawai']->write($row, $i, $i+1, $this->fH3);
      }*/
      
      if (sizeof($this->ComboUnitKerja)<=0) {
          $row++;
    			$this->mWorksheets['Pegawai']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
    			for ($i=1; $i<$jumKolom; $i++){
            $this->mWorksheets['Pegawai']->write($row, $i, '', $this->fH3);
          }
    			$this->mWorksheets['Pegawai']->merge_cells($row, 0, $row,$jumKolom-1);
    	} else {
    			$total=0;
    			$temp=sizeof($this->ComboUnitKerja);
    			$this->ComboUnitKerja[$temp]['id']='TOTAL';
    			$this->ComboUnitKerja[$temp]['name']='TOTAL';
    			for ($i=0; $i<sizeof($this->ComboUnitKerja); $i++) {
    					$no = $i+1;
    					if ($i<$temp) $dataUnit[$i]['no'] = $no;
        			$dataUnit[$i]['unit_kerja']=$this->ComboUnitKerja[$i]['name'];
        			
        			$total_l=0; $total_p=0;
        			for ($ii=0; $ii<$totalKolomJenisPegawai; $ii++){
        			   $tempDataL=$this->dataJumlah[$this->ComboUnitKerja[$i]['id']][$this->ComboJenisPegawai[$ii]['id']]['L'];
        			   $tempDataP=$this->dataJumlah[$this->ComboUnitKerja[$i]['id']][$this->ComboJenisPegawai[$ii]['id']]['P'];
        			   
        			   if ($tempDataL=='') $tempDataL=0;
                 if ($tempDataP=='') $tempDataP=0;
                 
                 $total_l +=$tempDataL;
                 $total_p +=$tempDataP;
                 
                 $this->dataJumlah[$this->ComboUnitKerja[$temp]['id']][$this->ComboJenisPegawai[$ii]['id']]['L'] += $tempDataL;
                 $this->dataJumlah[$this->ComboUnitKerja[$temp]['id']][$this->ComboJenisPegawai[$ii]['id']]['P'] += $tempDataP;
                 
                 if ($tempDataL<=0){ $tempDataL=''; }
                 if ($tempDataP<=0){ $tempDataP=''; } 
                 
                 $dataUnit[$i][$this->ComboJenisPegawai[$ii]['id'].'_l']=$tempDataL;
                 $dataUnit[$i][$this->ComboJenisPegawai[$ii]['id'].'_p']=$tempDataP;
              }
              
              $dataUnit[$i]['jml_l']=$total_l;
              $dataUnit[$i]['jml_p']=$total_p;   
              
              $row++;
              for ($ii=0; $ii<sizeof($val); $ii++){
                $this->mWorksheets['Pegawai']->write($row, $ii, $dataUnit[$i][$val[$ii]], $this->fColData2);
                //Mencari Panjang Data Maksimal
    				    if ($size_col[$val[$ii]]<strlen($dataUnit[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataUnit[$i][$val[$ii]]);}
              }	 
    			}
    				
    			//Setting Lebar Kolom
    			$lebar_max=50;
    			for ($i=0; $i<$jumKolom; $i++){
    			  if ($size_col[$val[$i]]>$lebar_max) $size_col[$val[$i]]=$lebar_max;
            $this->mWorksheets['Pegawai']->set_column($i,$i,$size_col[$val[$i]]+3);
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