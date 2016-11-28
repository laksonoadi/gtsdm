<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pak_kumulatif/business/mutasi_pak.class.php';
   
class ViewMutasiPak extends XlsResponse
{
   var $mWorksheets = array('pak');
   
   function GetFileName() {
      // name it whatever you want
      return 'Penetapan_Angka_Kredit.xls';
   }
   
   /*function GetLabelFromCombo($ArrData,$Nilai){
      for ($i=0; $i<sizeof($ArrData); $i++){
        if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
      }
      return '--Semua--';
   }*/
   
   function ProcessRequest()
   {
		$this->Obj=new MutasiPak();
		$dataPegawai = $this->Obj->GetDataDetail($_GET['id']); 
		$dataPegawaipak = $this->Obj->GetDataMutasiById($_GET['id'],$_GET['dataId']);
		$mutasiPak = $this->Obj->GetListMutasiPak($_GET['id']);
		
		$dataUnsur['utama'] = $this->Obj->GetDataUnsurPenilaian($_GET['dataId'],'Utama');
        $dataUnsur['penunjang'] = $this->Obj->GetDataUnsurPenilaian($_GET['id'],$_GET['dataId'],'Penunjang');
		$unsurPak = $this->Obj->GetDataUnsurPenilaianGroup1($_GET['id'],$_GET['dataId']);
		$unsurPakBaru = $this->Obj->GetDataUnsurPenilaianGroup2($_GET['id'],$_GET['dataId']);
		$unsurPakLama = $this->Obj->GetDataUnsurPenilaianLamaGroup2($_GET['id'],$_GET['dataId']);
		//$jumlahTotalbaru =($unsurPakBaru[0]['total']);
		
		//print_r($unsurPakBaru[]['total']);exit;
		$data=$dataUnsur['utama'];
		
			//$this->mrTemplate->AddVars('content', $tot, 'TOTAL_');
      
  		$row=-1;
  		
  		$this->fH1 = $this->mrWorkbook->add_format();
		 	$this->fH1->set_bold();
      $this->fH1->set_size(12);
      $this->fH1->set_align('vcenter');
      $this->fH1->set_align('center');
      
      $this->fH2 = $this->mrWorkbook->add_format();
		 	//$this->fH2->set_bold();
      $this->fH2->set_size(10);
      $this->fH2->set_align('vcenter');
      
	  $this->fH4 = $this->mrWorkbook->add_format();
		 	$this->fH4->set_bold();
      $this->fH4->set_size(11);
      $this->fH4->set_align('vcenter');	  
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
	  
	  $this->fH5 = $this->mrWorkbook->add_format();
      $this->fH5->set_border(1);
		 	$this->fH5->set_bold();
      $this->fH5->set_size(10);
      $this->fH5->set_align('center');
      $this->fH5->set_align('vcenter');
	  $this->fH5->set_fg_color('white');
      $this->fH5->set_bg_color('white');
      $this->fH5->set_pattern(2);
      $this->fH5->set_bottom(2);
      $this->fH5->set_top(2);
      $this->fH5->set_right(2);
      $this->fH5->set_left(2);
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
		
		$this->fColData3 = $this->mrWorkbook->add_format();
    	$this->fColData3->set_border(1);   
    	$this->fColData3->set_size(10);
    	$this->fColData3->set_align('center');
    	$this->fColData3->set_align('top');
    	$this->fColData3->set_text_wrap();
		
		$this->fColData4 = $this->mrWorkbook->add_format();
		$this->fColData4->set_bold();
    	$this->fColData4->set_border(1);   
    	$this->fColData4->set_size(10);
    	$this->fColData4->set_align('left');
    	$this->fColData4->set_align('top');
    	$this->fColData4->set_text_wrap();
    	
    	$jumKolom=4;
  		
  		$row++;
		  $this->mWorksheets['pak']->write($row, 0, 'PENETAPAN ANGKA KREDIT', $this->fH1);
    	$this->mWorksheets['pak']->merge_cells($row, 0, $row,$jumKolom-1);
    	
		$row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Informasi Pribadi', $this->fH4);
		  $this->mWorksheets['pak']->merge_cells($row, 0, $row,$jumKolom-1);
		  
    	$row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Nama  ', $this->fH2);
		  //$this->mWorksheets['pak']->merge_cells($row, 0, $row,$jumKolom-1);
		  $this->mWorksheets['pak']->write($row,1, ': '.$dataPegawai[0]['name'], $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'NIP ', $this->fH2);
		  //$this->mWorksheets['pak']->merge_cells($row, 0, $row,$jumKolom-1);
		  $this->mWorksheets['pak']->write($row, 1, ': '.$dataPegawai[0]['kode'], $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Alamat ', $this->fH2);
		  $this->mWorksheets['pak']->write($row, 1, ': '.$dataPegawai[0]['alamat'], $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Detail Mutasi Penetapan Angka Kredit ', $this->fH4);
		  $this->mWorksheets['pak']->merge_cells($row, 0, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Nomor PAK ', $this->fH2);
		  $this->mWorksheets['pak']->write($row, 1, ': '.$dataPegawaipak[0]['nopak'], $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Tanggal Penetapan ', $this->fH2);
		  $this->mWorksheets['pak']->write($row, 1, ': '.$this->date2string($dataPegawaipak[0]['tanggal_ditetapkan']), $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Pejabat ', $this->fH2);
		  $this->mWorksheets['pak']->write($row, 1, ': '.$dataPegawaipak[0]['pejabat'], $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		   $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Tanggal Pengajuan  ', $this->fH2);
		  $this->mWorksheets['pak']->write($row, 1, ': '.$this->date2string($dataPegawaipak[0]['tgl_penetapan']), $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Tanggal Awal Penilaian ', $this->fH2);
		  $this->mWorksheets['pak']->write($row, 1, ': '.$this->date2string($mutasiPak[0]['mulai']), $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Tanggal Akhir Penilaian ', $this->fH2);
		  $this->mWorksheets['pak']->write($row, 1, ': '.$this->date2string($mutasiPak[0]['selesai']), $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'NIP  ', $this->fH2);
		  $this->mWorksheets['pak']->write($row, 1, ': '.$dataPegawai[0]['kode'], $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Nomor Seri Kartu Pegawai ', $this->fH2);
		  $this->mWorksheets['pak']->write($row, 1, ': '.$dataPegawai[0]['no_seri'], $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Tanggal Lahir ', $this->fH2);
		  $this->mWorksheets['pak']->write($row, 1, ': '.$this->date2string($dataPegawai[0]['tgl_lahir']), $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Jenis Kelamin ', $this->fH2);
		  $this->mWorksheets['pak']->write($row, 1, ': '.$dataPegawai[0]['jenis_kelamin'], $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Pendidikan Tertinggi ', $this->fH2);
		  $this->mWorksheets['pak']->write($row, 1, ': '.$dataPegawai[0]['pendidikan_tertinggi'], $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom+1);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Pangkat dan Golongan/TMT  ', $this->fH2);
		  $this->mWorksheets['pak']->write($row, 1, ': '.$dataPegawai[0]['pangkat_golongan'].'/'.$dataPegawai[0]['pangkat_golongan_tmt'], $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Jabatan Fungsional/TMT ', $this->fH2);
		  $this->mWorksheets['pak']->write($row, 1, ': '.$dataPegawai[0]['jabatan_fungsional'].'/'.$dataPegawai[0]['jabatan_fungsional_tmt'], $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Unit Kerja ', $this->fH2);
		  $this->mWorksheets['pak']->write($row, 1, ': '.$dataPegawai[0]['unit_kerja'], $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row , 0, 'Dapat diangkat dlm Jabatan sebagai ', $this->fH2);
		  $this->mWorksheets['pak']->write($row, 1, ': '.$dataPegawai[0]['diangkat_label'], $this->fH2);
		  $this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom);
		  
		  $row++;
		  $this->mWorksheets['pak']->write($row, 0, 'Penetapan Angka Kredit', $this->fH4);
		  $this->mWorksheets['pak']->merge_cells($row, 0, $row,$jumKolom-1);
		  
		  //Set Header
		  $header[0]=array('Penetapan Angka Kredit','Jumlah');
		  $header[1]=array('unsur utama','Lama','Baru','Jumlah');
		  $header2[0]=array('unsur penunjang','Lama','Baru','Jumlah');
		  
		  //Set Nama Variabel Yang akan ditulis
		  $val=array('deskripsi','0','angka_kredit','jumlah');
		  //$val=array('','','','');
			//Set Lebar Kolom Awal =0
		  //for ($i=0; $i<$jumKolom; $i++){
          //$size_col[$val[$i]]=0;
      //}
		  //Menulis Header/Judul Table
      $row++; $k=0;
      $Htemp=$header;
      for ($i=0; $i<sizeof($header); $i++){
        $row++;
        for ($ii=0; $ii<sizeof($val); $ii++){
          $this->mWorksheets['pak']->write($row, $ii, $header[$i][$ii], $this->fH3);
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
		    $this->mWorksheets['pak']->merge_cells($merger[$i]['row_awal'], $merger[$i]['col_awal'], $merger[$i]['row_akhir'], $merger[$i]['col_akhir']);
      }
      
      //$row++;
      //for ($i=0; $i<$jumKolom; $i++){
        //$this->mWorksheets['pak']->write($row, $i, $i+1, $this->fH3);
      //} 
	for ($i=0; $i<sizeof($unsurPakLama); $i++){
				$jenis=$unsurPakLama[$i]['jenis'];
				$lama[$jenis]+=$unsurPakLama[$i]['total'];
			}
			
			
			$unsurUtama=array();
			$unsurPenunjang=array();
			$unsur=''; $subunsur='';
			$unsurP='';
			$ii=0; $jj=0;
			for ($i=0; $i<sizeof($unsurPak); $i++){
			//$row++;
				if ($unsurPak[$i]['jenis']=='Utama'){
					if ($unsur!=$unsurPak[$i]['unsur']){
						if ($i>0) $ii++;
						$row++; $cols=0;
						$unsur=$unsurPak[$i]['unsur'];
						$this->mWorksheets['pak']->write($row, 0, $unsur, $this->fColData4);
						$this->mWorksheets['pak']->write($row, 1, '', $this->fColData2);
						$this->mWorksheets['pak']->write($row, 2, '', $this->fColData2);
						$this->mWorksheets['pak']->write($row, 3, '', $this->fColData2);
						$ii++;
					}
					if ($subunsur!=$unsurPak[$i]['subunsur']){
						$subunsur=$unsurPak[$i]['subunsur'];
						$row++; $cols=0;
						$this->mWorksheets['pak']->write($row, 0, $subunsur, $this->fColData4);
						$this->mWorksheets['pak']->write($row, 1, '', $this->fColData2);
						$this->mWorksheets['pak']->write($row, 2, '', $this->fColData2);
						$this->mWorksheets['pak']->write($row, 3, '', $this->fColData2);
						$ii++;
					}
					$unsurUtama[$ii]['nama']=$unsurPak[$i]['kegiatan'];
					$unsurUtama[$ii]['baru']=$unsurPak[$i]['total'];
					$unsurUtama[$ii]['lama']=0;
					
					$jumUnsur += $unsurPak[$i]['total'];
					$jumSubunsur += $unsurPak[$i]['total'];
					$row++;
					$this->mWorksheets['pak']->write($row, 0, $unsurUtama[$ii]['nama'], $this->fColData2);
					$this->mWorksheets['pak']->write($row, 1, $unsurUtama[$ii]['lama'], $this->fColData3);
					$this->mWorksheets['pak']->write($row, 2, $unsurUtama[$ii]['baru'], $this->fColData3);
					$this->mWorksheets['pak']->write($row, 3, '', $this->fColData3);
					$ii++;
				}else{
				    if ($unsurP!=$unsurPak[$i]['unsur']){
						$unsurP=$unsurPak[$i]['unsur'];
						$this->mWorksheets['pak']->write($row, 0, $unsurP, $this->fColData2);
						$jj++;
					}
					$unsurPenunjang[$jj]['nama']=$unsurPak[$i]['kegiatan'];
					$unsurPenunjang[$jj]['baru']=$unsurPak[$i]['total'];
					$unsurPenunjang[$jj]['lama']=0;
					$this->mWorksheets['pak']->write($row, 0, $unsurPenunjang[$jj]['nama'], $this->fColData2);
					$this->mWorksheets['pak']->write($row, 1, $unsurPenunjang[$jj]['lama'], $this->fColData2);
					$this->mWorksheets['pak']->write($row, 2, $unsurPenunjang[$jj]['baru'], $this->fColData2);
					$this->mWorksheets['pak']->write($row, 3, '', $this->fColData3);
					$jj++;
				}		
		}
			if (empty($unsurUtama)) {
				$this->mWorksheets['pak']->write($row, 0, 'Unsur Utama belum ditetapkan ', $this->fH5);
				for ($i=1; $i<$jumKolom; $i++){
						$this->mWorksheets['pak']->write($row, $i, '', $this->fH3);
				}
				$this->mWorksheets['pak']->merge_cells($row, 0, $row,$jumKolom-1);
			} else {
				
				$utama['jumlah']=count($unsurUtama);
				$utama['lama']=$lama['Utama'];
				$utama['baru']=0;
				$utama['jumlah']=$lama['Utama'];
				$start=1;
				for ($i=0; $i<count($unsurUtama); $i++) {
					$no = $i+$start;
					$unsurUtama[$i]['nomor'] = $no;
					$utama['baru'] +=$unsurUtama[$i]['baru'];
					$utama['jumlah'] +=$unsurUtama[$i]['baru'];
					
					//$this->mWorksheets['pak']->write($row, $ii, $utama['jumlah'], $this->fColData3);
				 
				}
				//print_r($utama['baru']);exit;
			}
	$jumlahTotal = $utama['lama'] + $utama['baru'];
	$footer[0]=array('Jumlah Unsur Utama',$utama['lama'],$utama['baru'],$jumlahTotal);
	$k=0;
      $Htemp1=$footer;
      for ($i=0; $i<sizeof($footer); $i++){
        $row++;
        for ($ii=0; $ii<sizeof($val); $ii++){
          $this->mWorksheets['pak']->write($row, $ii, $footer[$i][$ii], $this->fH3);
    			if ($size_col[$val[$ii]]<strlen($footer[$i][$ii])) {$size_col[$val[$ii]]=strlen($footer[$i][$ii]);}
    			
    			$bottom=$i+1;
    			if (($Htemp1[$i][$ii]!='')&&($bottom<sizeof($Htemp1))&&($Htemp1[$bottom][$ii]=='')){
    			   $bottom=$i+1;
    			   $k++;
    			   while (($bottom<sizeof($Htemp1))&&($Htemp1[$bottom][$ii]=='')){
    			       $merger[$k]['row_awal']=$i+$row;
        			   $merger[$k]['row_akhir']=$bottom+$row;
        			   $merger[$k]['col_awal']=$ii;
        			   $merger[$k]['col_akhir']=$ii;
        			   $Htemp[$bottom][$ii]='WHY';
        			   $bottom++;
             }
          }
          
          $left=$ii+1;
    			if (($Htemp1[$i][$ii]!='')&&($left<sizeof($val))&&($Htemp1[$i][$left]=='')){
    			   $left=$ii+1;
    			   $k++;
    			   while (($left<sizeof($val))&&($Htemp1[$i][$left]=='')){
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
	  $k=0;
	  $Htemp2=$header2;
      for ($i=0; $i<sizeof($header2); $i++){
        $row++;
        for ($ii=0; $ii<sizeof($val); $ii++){
          $this->mWorksheets['pak']->write($row, $ii, $header2[$i][$ii], $this->fH3);
    			if ($size_col[$val[$ii]]<strlen($header2[$i][$ii])) {$size_col[$val[$ii]]=strlen($header2[$i][$ii]);}
    			
    			$bottom=$i+1;
    			if (($Htemp2[$i][$ii]!='')&&($bottom<sizeof($Htemp2))&&($Htemp2[$bottom][$ii]=='')){
    			   $bottom=$i+1;
    			   $k++;
    			   while (($bottom<sizeof($Htemp2))&&($Htemp2[$bottom][$ii]=='')){
    			       $merger[$k]['row_awal']=$i+$row;
        			   $merger[$k]['row_akhir']=$bottom+$row;
        			   $merger[$k]['col_awal']=$ii;
        			   $merger[$k]['col_akhir']=$ii;
        			   $Htemp[$bottom][$ii]='WHY';
        			   $bottom++;
             }
          }
          
          $left=$ii+1;
    			if (($Htemp2[$i][$ii]!='')&&($left<sizeof($val))&&($Htemp2[$i][$left]=='')){
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
		 $row++;
		 	if (empty($unsurPenunjang)) {
				$this->mWorksheets['pak']->write($row, 0, 'Unsur Penunjang belum ditetapkan ', $this->fH5);
				for ($i=1; $i<$jumKolom; $i++){
						$this->mWorksheets['pak']->write($row, $i, '', $this->fH3);
				}
				$this->mWorksheets['pak']->merge_cells($row, 0, $row,$jumKolom-1);
			} else {
				for ($i=0; $i<sizeof($unsurPak); $i++){
				if ($unsurPak[$i]['jenis'] =='Penunjang') {
						//if ($unsurP!=$unsurPak[$i]['unsur']){
						$unsurP=$unsurPak[$i]['unsur'];
						//$row++;
						$this->mWorksheets['pak']->write($row, 0, $unsurP, $this->fColData4);
						$this->mWorksheets['pak']->write($row, 1, '', $this->fColData2);
						$this->mWorksheets['pak']->write($row, 2, '', $this->fColData2);
						$this->mWorksheets['pak']->write($row, 3, '', $this->fColData2);
						//$jj++;
						//}
					$unsurPenunjang[$jj]['nama']=$unsurPak[$i]['kegiatan'];
					$unsurPenunjang[$jj]['baru'] =$unsurPak[$i]['total'];
					$unsurPenunjang[$jj]['lama']=0;
					$row++;
					$this->mWorksheets['pak']->write($row, 0, $unsurPenunjang[$jj]['nama'], $this->fColData2);
					$this->mWorksheets['pak']->write($row, 1, $unsurPenunjang[$jj]['lama'], $this->fColData3);
					$this->mWorksheets['pak']->write($row, 2, $unsurPenunjang[$jj]['baru'], $this->fColData3);
					$this->mWorksheets['pak']->write($row, 3, '', $this->fColData3);
					$jumlahpenunjang += $unsurPenunjang[$jj]['baru'];
					$penunjang['baru'] +=  $unsurPenunjang[$jj]['baru'];
					$penunjang['jumlah'] +=$unsurPenunjang[$jj]['baru'];
					$penunjang['lama']=$lama['Penunjang'];					
					$jj++;
				//print_r($penunjang['jumlah']);exit;
				}
				}
				//$penunjang['jumlah']=count($unsurPenunjang);
				
				//$penunjang['baru']=0;
				//$penunjang['jumlah']=$lama['Penunjang'];
				/*$start=1;
				for ($i=0; $i<count($unsurPenunjang); $i++) {
				//$i = 0;
				//if ($i<count($unsurPenunjang)){
					$no = $i+$start;
					$unsurPenunjang[$i]['nomor'] = $no;
					$penunjang['baru'] +=$unsurPenunjang[$i]['baru'];
					$penunjang['jumlah'] +=$unsurPenunjang[$i]['baru'];
					//$this->mWorksheets['pak']->write($row, $ii, $penunjang['jumlah'], $this->fColData3);
				//$i++;	
				}*/
				//for ($i=0; $i<sizeof($unsurPak); $i++){
				//$jumlahpenunjang= $unsurPenunjang[$jj]['baru'];
			$tot['lama']=0+$utama['lama']+$penunjang['lama'];
			$tot['baru']=0+$utama['baru']+$penunjang['baru'];
			$tot['jumlah']=0+$tot['lama']+$tot['baru']; 
			//print_r($tot['lama']); exit;
				
			}
			
			//for ($i=0; $i<sizeof($unsurPak); $i++){
				
				
			//}
			
			$jumlahTotalpenunjang = $penunjang['lama'] + $jumlahpenunjang;
	$footer1[0]=array('Jumlah Unsur Penunjang',$penunjang['lama'],$jumlahpenunjang,$jumlahTotalpenunjang);
	$k=0;
      $Htemp4=$footer1;
      for ($i=0; $i<sizeof($footer1); $i++){
        $row++;
        for ($ii=0; $ii<sizeof($val); $ii++){
          $this->mWorksheets['pak']->write($row, $ii, $footer1[$i][$ii], $this->fH3);
    			if ($size_col[$val[$ii]]<strlen($footer1[$i][$ii])) {$size_col[$val[$ii]]=strlen($footer1[$i][$ii]);}
    			
    			$bottom=$i+1;
    			if (($Htemp4[$i][$ii]!='')&&($bottom<sizeof($Htemp4))&&($Htemp4[$bottom][$ii]=='')){
    			   $bottom=$i+1;
    			   $k++;
    			   while (($bottom<sizeof($Htemp4))&&($Htemp4[$bottom][$ii]=='')){
    			       $merger[$k]['row_awal']=$i+$row;
        			   $merger[$k]['row_akhir']=$bottom+$row;
        			   $merger[$k]['col_awal']=$ii;
        			   $merger[$k]['col_akhir']=$ii;
        			   $Htemp[$bottom][$ii]='WHY';
        			   $bottom++;
             }
          }
          
          $left=$ii+1;
    			if (($Htemp4[$i][$ii]!='')&&($left<sizeof($val))&&($Htemp4[$i][$left]=='')){
    			   $left=$ii+1;
    			   $k++;
    			   while (($left<sizeof($val))&&($Htemp4[$i][$left]=='')){
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
	  
	  //$jumlahTotalpenunjang = $penunjang['lama'] + $penunjang['baru'];
	$footer2[0]=array('Jumlah Unsur Utama dan Unsur Penunjang',$tot['lama'],$tot['baru'],$tot['jumlah']);
	$k=0;
      $Htemp5=$footer2;
      for ($i=0; $i<sizeof($footer2); $i++){
        $row++;
        for ($ii=0; $ii<sizeof($val); $ii++){
          $this->mWorksheets['pak']->write($row, $ii, $footer2[$i][$ii], $this->fH3);
    			if ($size_col[$val[$ii]]<strlen($footer2[$i][$ii])) {$size_col[$val[$ii]]=strlen($footer2[$i][$ii]);}
    			
    			$bottom=$i+1;
    			if (($Htemp5[$i][$ii]!='')&&($bottom<sizeof($Htemp5))&&($Htemp5[$bottom][$ii]=='')){
    			   $bottom=$i+1;
    			   $k++;
    			   while (($bottom<sizeof($Htemp5))&&($Htemp5[$bottom][$ii]=='')){
    			       $merger[$k]['row_awal']=$i+$row;
        			   $merger[$k]['row_akhir']=$bottom+$row;
        			   $merger[$k]['col_awal']=$ii;
        			   $merger[$k]['col_akhir']=$ii;
        			   $Htemp[$bottom][$ii]='WHY';
        			   $bottom++;
             }
          }
          
          $left=$ii+1;
    			if (($Htemp5[$i][$ii]!='')&&($left<sizeof($val))&&($Htemp5[$i][$left]=='')){
    			   $left=$ii+1;
    			   $k++;
    			   while (($left<sizeof($val))&&($Htemp5[$i][$left]=='')){
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
		  //Setting Lebar Kolom
    			$lebar_max=50;
    			for ($i=0; $i<$jumKolom; $i++){
    			  if ($size_col[$val[$i]]>$lebar_max) $size_col[$val[$i]]=$lebar_max;
            $this->mWorksheets['pak']->set_column($i,$i,$size_col[$val[$i]]+3);
        }
   //}
  
	  
	  /*$row++;
	  if (empty($unsurPenunjang)) {
				$this->mWorksheets['pak']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
				
			} else {
				
				$penunjang['jumlah']=count($unsurPenunjang);
				$penunjang['lama']=$lama['Penunjang'];
				$penunjang['baru']=0;
				$penunjang['jumlah']=$lama['Penunjang'];
				$start=1;
				for ($i=0; $i<count($unsurPenunjang); $i++) {
					$no = $i+$start;
					$unsurPenunjang[$i]['nomor'] = $no;
					$penunjang['baru'] +=$unsurPenunjang[$i]['baru'];
					$penunjang['jumlah'] +=$unsurPenunjang[$i]['baru'];
					$this->mWorksheets['pak']->write($row, $ii, $penunjang['jumlah'], $this->fColData3);
					
				}
				
			}*/

	}
   function GetCol($nilai){
      $var='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $awal=round($nilai/26);
      $akhir=$nilai % 26;
      
      return $var[$awal-1].$var[$akhir-1];
      
   }
   
   function date2string($date) {
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
  	   $arrtgl = explode('-',$date);
  	   return $arrtgl[2].' '.$bln[(int) $arrtgl[1]].' '.$arrtgl[0];
	   
	}
   
}
   

?>