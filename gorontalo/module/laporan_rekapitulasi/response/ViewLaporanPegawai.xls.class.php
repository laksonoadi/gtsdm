<?php
set_time_limit(0);

require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_rekapitulasi/business/laporan.class.php';
   
class ViewLaporanPegawai extends XlsResponse{
	var $mWorksheets = array('Rekapitulasi','Ringkasan');
   
	function GetFileName() {
		// name it whatever you want
		return 'Laporan_Rekapitulasi_'.date('Ymd').'.xls';
	}
   
	function GetLabelFromCombo($ArrData,$Nilai){
		for ($i=0; $i<sizeof($ArrData); $i++){
			if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
		}
		return '';
	}
   
	function ProcessRequest(){
		$this->Obj=new Laporan;
  		if(isset($_GET['cari'])) {
 			$this->Obj->filter['jabatan_fungsional'] = strval($_GET['jabatan_fungsional']);
			$this->Obj->filter['jenisfungsional'] = strval($_GET['jabatan_fungsional']);
			$this->Obj->filter['unit'] = strval($_GET['unit']);
			$this->Obj->filter['status'] = strval($_GET['status']);
			$this->Obj->filter['jenis'] = strval($_GET['jenis']);
			$this->Obj->filter['golongan'] = strval($_GET['golongan']);
			$this->Obj->filter['fungsional'] = strval($_GET['fungsional']);
			$this->Obj->filter['pendidikan'] = strval($_GET['pendidikan']);
			
			$this->berdasarkan = strval($_GET['berdasarkan']);
				
			$this->check_l=$_GET['L'];
			$this->check_p=$_GET['P'];
			$this->check_x=$_GET['X'];
			$this->check_t=$_GET['T'];
			$this->check_0=$_GET['0'];
			
			$this->jumlah=($this->check_l=='on'?1:0)+($this->check_p=='on'?1:0)+($this->check_x=='on'?1:0)+($this->check_t=='on'?1:0);
  		} else {
  			$this->Obj->filter['jabatan_fungsional'] = 'all';
			$this->Obj->filter['jenisfungsional'] = 'all';
			$this->Obj->filter['unit'] = 'all';
			$this->Obj->filter['status'] = 'all';
			$this->Obj->filter['jenis'] = 'all';
			$this->Obj->filter['golongan'] = 'all';
			$this->Obj->filter['fungsional'] = 'all';
			$this->Obj->filter['pendidikan'] = 'all';
			
			$this->berdasarkan = 'unit';
			
			$this->check_l='on';
			$this->check_p='on';
			$this->check_x='on';
			$this->check_t='on';
			$this->check_0='on';
			$this->jumlah=4;
  		}
		
		$this->Obj->getVariabelGlobal();
		$this->judul=$this->Obj->judul;
		$this->tanggal=$this->Obj->IndonesianDate(date('Y-m-d'),"YYYY-MM-DD");
      
		$this->ComboJabatanFungsional=$this->Obj->GetComboJabatanFungsional();
  		$this->label_jabatan_fungsional=$this->GetLabelFromCombo($this->ComboJabatanFungsional,$this->Obj->filter['jabatan_fungsional']);
		$this->label['jenisfungsional']=$this->label_jabatan_fungsional;
		
		$this->ComboUnit=$this->Obj->GetComboUnitKerja(true);
		$this->label['unit']=$this->GetLabelFromCombo($this->ComboUnit,$this->Obj->filter['unit']);
		
		$this->ComboStatus=$this->Obj->GetComboVariabel2('status');
		$this->label['status']=$this->GetLabelFromCombo($this->ComboStatus,$this->Obj->filter['status']);
		
		$this->ComboJenis=$this->Obj->GetComboVariabel2('jenis');
		$this->label['jenis']=$this->GetLabelFromCombo($this->ComboJenis,$this->Obj->filter['jenis']);
		
		$this->ComboGolongan=$this->Obj->GetComboVariabel2('golongan');
		$this->label['golongan']=$this->GetLabelFromCombo($this->ComboGolongan,$this->Obj->filter['golongan']);
		
		$this->ComboFungsional=$this->Obj->GetComboVariabel2('fungsional');
		$this->label['fungsional']=$this->GetLabelFromCombo($this->ComboPangkat,$this->Obj->filter['fungsional']);
		
		$this->ComboPendidikan=$this->Obj->GetComboVariabel2('pendidikan');
		$this->label['pendidikan']=$this->GetLabelFromCombo($this->ComboPangkat,$this->Obj->filter['pendidikan']);
	
		//create paging 
		$this->ComboUnitKerja=$this->Obj->GetComboUnitKerja();
		$this->ComboVariabel=$this->Obj->GetComboVariabel($this->berdasarkan,$this->Obj->filter['jabatan_fungsional']);
		$totalData = sizeof($this->ComboUnitKerja);
		
		$dataPegawai = $this->Obj->GetDataPegawai($startRec, $itemViewed,$this->Obj->filter['jabatan_fungsional'],$this->berdasarkan);
  		for ($i=0; $i<sizeof($dataPegawai); $i++){
			$this->dataJumlah[$dataPegawai[$i]['unit_kerja']][$dataPegawai[$i]['nama']][$dataPegawai[$i]['jenis_kelamin']]=$dataPegawai[$i]['jumlah'];
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
    	
    	$totalKolom=sizeof($this->ComboVariabel);
		
    	$jumKolom=($totalKolom+1)*$this->jumlah+2;
  		$row++;
		$this->mWorksheets['Rekapitulasi']->write($row, 0, strtoupper('LAPORAN REKAPITULASI PEGAWAI '.$this->label_jabatan_fungsional.' BERDASARKAN '.$this->judul[$this->berdasarkan]), $this->fH1);
    	$this->mWorksheets['Rekapitulasi']->merge_cells($row, 0, $row,$jumKolom-1);
		$row++;
		$this->mWorksheets['Rekapitulasi']->write($row, 0, strtoupper('PER TANGGAL '.$this->tanggal.' DENGAN KRITERIA '.$this->kriteria), $this->fH1);
    	$this->mWorksheets['Rekapitulasi']->merge_cells($row, 0, $row,$jumKolom-1);
		  
		  //Set Header
		$header[0]=array('No.','Unit Kerja');
		for ($i=0; $i<$totalKolom; $i++){
		    if ($i==0){
				array_push($header[0],$this->judul[$this->berdasarkan]);
				if ($this->check_p=='on') array_push($header[0],'');
				if ($this->check_x=='on') array_push($header[0],'');
				if ($this->check_t=='on') array_push($header[0],'');
			}else{
				if ($this->check_l=='on') array_push($header[0],'');
				if ($this->check_p=='on') array_push($header[0],'');
				if ($this->check_x=='on') array_push($header[0],'');
				if ($this->check_t=='on') array_push($header[0],'');
			}
		}
		if ($this->check_l=='on') array_push($header[0],'');
		if ($this->check_p=='on') array_push($header[0],'');
		if ($this->check_x=='on') array_push($header[0],'');
		if ($this->check_t=='on') array_push($header[0],'');
		  
		$header[1]=array('','');
		for ($i=0; $i<$totalKolom; $i++){
		    array_push($header[1],$this->ComboVariabel[$i]['name']);
			if ($this->check_p=='on') array_push($header[1],'');
			if ($this->check_x=='on') array_push($header[1],'');
			if ($this->check_t=='on') array_push($header[1],'');
		}
		array_push($header[1],'Jumlah');
		if ($this->check_p=='on') array_push($header[1],'');
		if ($this->check_x=='on') array_push($header[1],'');
		if ($this->check_t=='on') array_push($header[1],'');
		  
		$header[2]=array('1','2');
		for ($i=0; $i<=$totalKolom; $i++){
		    array_push($header[2],$i+3);
			if ($this->check_p=='on') array_push($header[2],'');
			if ($this->check_x=='on') array_push($header[2],'');
			if ($this->check_t=='on') array_push($header[2],'');
		}
      
		$header[3]=array(' ','Jenis Kelamin');
		for ($i=0; $i<=$totalKolom; $i++){
		    if ($this->check_l=='on') array_push($header[3],'L');
			if ($this->check_p=='on') array_push($header[3],'P');
			if ($this->check_x=='on') array_push($header[3],'X');
			if ($this->check_t=='on') array_push($header[3],'T');
		}
		  
		//Set Nama Variabel Yang akan ditulis
		$val=array('no','unit_kerja');
		for ($i=0; $i<$totalKolom; $i++){
		    array_push($val,$this->ComboVariabel[$i]['id'].'_l');
		    array_push($val,$this->ComboVariabel[$i]['id'].'_p');
			array_push($val,$this->ComboVariabel[$i]['id'].'_x');
			array_push($val,$this->ComboVariabel[$i]['id'].'_t');
		}
		array_push($val,'jml_l');
		array_push($val,'jml_p');
		array_push($val,'jml_x');
		array_push($val,'jml_t');
		  
		//Set Lebar Kolom Awal =0
		for ($i=0; $i<$jumKolom; $i++){
			$size_col[$val[$i]]=0;
		}
		
		$row++; $k=0;
		$Htemp=$header;
		for ($i=0; $i<sizeof($header); $i++){
			$row++;
        
			for ($ii=0; $ii<sizeof($val); $ii++){ 
				$this->mWorksheets['Rekapitulasi']->write($row, $ii, $header[$i][$ii], $this->fH3);
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
		    $this->mWorksheets['Rekapitulasi']->merge_cells($merger[$i]['row_awal'], $merger[$i]['col_awal'], $merger[$i]['row_akhir'], $merger[$i]['col_akhir']);
		}
      
		if (sizeof($this->ComboUnitKerja)<=0) {
			$row++;
    		$this->mWorksheets['Rekapitulasi']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
    		for ($i=1; $i<$jumKolom; $i++){
				$this->mWorksheets['Rekapitulasi']->write($row, $i, '', $this->fH3);
			}
    			$this->mWorksheets['Rekapitulasi']->merge_cells($row, 0, $row,$jumKolom-1);
    	} else {
    		$total=0;
			
    		$temp=sizeof($this->ComboUnitKerja);
    		$this->ComboUnitKerja[$temp]['id']='TOTAL';
    		$this->ComboUnitKerja[$temp]['name']='TOTAL';
    		for ($i=0; $i<sizeof($this->ComboUnitKerja); $i++) {
    			$no = $i+1;
    			if ($i<$temp) $dataUnit[$i]['no'] = $no;
        		$dataUnit[$i]['unit_kerja']=$this->ComboUnitKerja[$i]['name'];
        			
        		$total_l=0; $total_p=0; $total_x=0; $total_t=0;
        		for ($ii=0; $ii<$totalKolom; $ii++){
					$tempDataL=$this->dataJumlah[$this->ComboUnitKerja[$i]['id']][$this->ComboVariabel[$ii]['id']]['L'];
        			$tempDataP=$this->dataJumlah[$this->ComboUnitKerja[$i]['id']][$this->ComboVariabel[$ii]['id']]['P'];
					$tempDataX=$this->dataJumlah[$this->ComboUnitKerja[$i]['id']][$this->ComboVariabel[$ii]['id']]['X'];
					$tempDataT=$tempDataX+$tempDataP+$tempDataL;
        			   
        			if ($tempDataL=='') $tempDataL=0;
					if ($tempDataP=='') $tempDataP=0;
					if ($tempDataX=='') $tempDataX=0;
					if ($tempDataT=='') $tempDataT=0;
                 
					$total_l +=$tempDataL;
					$total_p +=$tempDataP;
					$total_x +=$tempDataX;
					$total_t +=$tempDataT;
                 
					$this->dataJumlah[$this->ComboUnitKerja[$temp]['id']][$this->ComboVariabel[$ii]['id']]['L'] += $tempDataL;
					$this->dataJumlah[$this->ComboUnitKerja[$temp]['id']][$this->ComboVariabel[$ii]['id']]['P'] += $tempDataP;
					$this->dataJumlah[$this->ComboUnitKerja[$temp]['id']][$this->ComboVariabel[$ii]['id']]['X'] += $tempDataX;
					$this->dataJumlah[$this->ComboUnitKerja[$temp]['id']][$this->ComboVariabel[$ii]['id']]['T'] += $tempDataT;
                 
					if ($tempDataL<=0){ $tempDataL=($this->check_0=='on'?'0':''); }
					if ($tempDataP<=0){ $tempDataP=($this->check_0=='on'?'0':''); } 
					if ($tempDataX<=0){ $tempDataX=($this->check_0=='on'?'0':''); } 
					if ($tempDataT<=0){ $tempDataT=($this->check_0=='on'?'0':''); } 
                 
					$dataUnit[$i][$this->ComboVariabel[$ii]['id'].'_l']=$tempDataL;
					$dataUnit[$i][$this->ComboVariabel[$ii]['id'].'_p']=$tempDataP;
					$dataUnit[$i][$this->ComboVariabel[$ii]['id'].'_x']=$tempDataX;
					$dataUnit[$i][$this->ComboVariabel[$ii]['id'].'_t']=$tempDataT;
				}
              
				$dataUnit[$i]['jml_l']=$total_l;
				$dataUnit[$i]['jml_p']=$total_p;   
				$dataUnit[$i]['jml_x']=$total_x;
				$dataUnit[$i]['jml_t']=$total_t;   
                
				$row++;
				for ($ii=0; $ii<sizeof($val); $ii++){
					$this->mWorksheets['Rekapitulasi']->write($row, $ii, $dataUnit[$i][$val[$ii]], $this->fColData2);
					//Mencari Panjang Data Maksimal
    				    if ($size_col[$val[$ii]]<strlen($dataUnit[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataUnit[$i][$val[$ii]]);}
				}	 
    		}
    				
    		//Setting Lebar Kolom
    		$lebar_max=50;
    		for ($i=0; $i<$jumKolom; $i++){
    			if ($size_col[$val[$i]]>$lebar_max) $size_col[$val[$i]]=$lebar_max;
				$this->mWorksheets['Rekapitulasi']->set_column($i,$i,$size_col[$val[$i]]+3);
			}
			
			
			//Ringkasan
			$row=0;
			$this->mWorksheets['Ringkasan']->write($row, 0, strtoupper('RINGKASAN DATA'), $this->fH1);
			$this->mWorksheets['Ringkasan']->merge_cells($row, 0, $row,6);
			
			$row++;
			$this->mWorksheets['Ringkasan']->write($row, 0, 'No', $this->fH3);
			$this->mWorksheets['Ringkasan']->write($row, 1, $this->judul[$this->berdasarkan], $this->fH3);
			$this->mWorksheets['Ringkasan']->write($row, 2, 'L', $this->fH3);
			$this->mWorksheets['Ringkasan']->write($row, 3, 'P', $this->fH3);
			$this->mWorksheets['Ringkasan']->write($row, 4, 'X', $this->fH3);
			$this->mWorksheets['Ringkasan']->write($row, 5, 'Total', $this->fH3);
			$this->mWorksheets['Ringkasan']->write($row, 6, 'Persentasi (%)',$this->fH3);
				
			$ring_total['JML_L']=0;
			$ring_total['JML_P']=0;
			$ring_total['JML_X']=0;
			$ring_total['JML_T']=0;
			
			
			for ($i=0; $i<$totalKolom; $i++){
				$ringkasan['JML_L']=$this->dataJumlah[$this->ComboUnitKerja[$temp]['id']][$this->ComboVariabel[$i]['id']]['L']/2;
				$ringkasan['JML_P']=$this->dataJumlah[$this->ComboUnitKerja[$temp]['id']][$this->ComboVariabel[$i]['id']]['P']/2;
				$ringkasan['JML_X']=$this->dataJumlah[$this->ComboUnitKerja[$temp]['id']][$this->ComboVariabel[$i]['id']]['X']/2;
				$ringkasan['JML_T']=$ringkasan['JML_L']+$ringkasan['JML_P']+$ringkasan['JML_X'];
				if ($total_t>0){
					$ringkasan['PERSEN']=number_format(($ringkasan['JML_T']/$total_t)*100, 2, '.', '');
				}else{
					$ringkasan['PERSEN']=number_format(0, 2, '.', '');
				}
				
				$ringkasan['NO']=$i+1;
				$ringkasan['GOLONGAN']=$this->ComboVariabel[$i]['name'];
				
				$ringkasan['L']=$this->check_l=='on'?'':'none';
				$ringkasan['P']=$this->check_p=='on'?'':'none';
				$ringkasan['X']=$this->check_x=='on'?'':'none';
				$ringkasan['T']=$this->check_t=='on'?'':'none';
				
				$ring_total['JML_L'] += $ringkasan['JML_L'];
				$ring_total['JML_P'] += $ringkasan['JML_P'];
				$ring_total['JML_X'] += $ringkasan['JML_X'];
				$ring_total['JML_T'] += $ringkasan['JML_T'];
				$ring_total['PERSEN'] += $ringkasan['PERSEN'];
				
				$row++;
				$this->mWorksheets['Ringkasan']->write($row, 0, $ringkasan['NO'], $this->fColData2);
				$this->mWorksheets['Ringkasan']->write($row, 1, $ringkasan['GOLONGAN'], $this->fColData2);
				$this->mWorksheets['Ringkasan']->write($row, 2, $ringkasan['JML_L'], $this->fColData2);
				$this->mWorksheets['Ringkasan']->write($row, 3, $ringkasan['JML_P'], $this->fColData2);
				$this->mWorksheets['Ringkasan']->write($row, 4, $ringkasan['JML_X'], $this->fColData2);
				$this->mWorksheets['Ringkasan']->write($row, 5, $ringkasan['JML_T'], $this->fColData2);
				$this->mWorksheets['Ringkasan']->write($row, 6, $ringkasan['PERSEN'], $this->fColData2);
			}
			
			$ring_total['GOLONGAN']='TOTAL';
			$ring_total['NO']='';
			$ring_total['PERSEN']=round($ring_total['PERSEN']);
			$ringkasan=$ring_total;
			
			$row++;
			$this->mWorksheets['Ringkasan']->write($row, 0, $ringkasan['NO'], $this->fColData2);
			$this->mWorksheets['Ringkasan']->write($row, 1, $ringkasan['GOLONGAN'], $this->fColData2);
			$this->mWorksheets['Ringkasan']->write($row, 2, $ringkasan['JML_L'], $this->fColData2);
			$this->mWorksheets['Ringkasan']->write($row, 3, $ringkasan['JML_P'], $this->fColData2);
			$this->mWorksheets['Ringkasan']->write($row, 4, $ringkasan['JML_X'], $this->fColData2);
			$this->mWorksheets['Ringkasan']->write($row, 5, $ringkasan['JML_T'], $this->fColData2);
			$this->mWorksheets['Ringkasan']->write($row, 6, $ringkasan['PERSEN'], $this->fColData2);
            
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