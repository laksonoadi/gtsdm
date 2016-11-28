<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pak_kumulatif/business/mutasi_pak.class.php';
   
class ViewMutasiPak extends XlsResponse
{
   var $mWorksheets = array('pak');
   
   function GetFileName() {
      // name it whatever you want
      return 'Pengajuan_Angka_Kredit.xls';
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
		$dataDetail = $this->Obj->GetDataUnsurPenilaian($_GET['dataId']);
		$unsurPak = $this->Obj->GetDataUnsurPenilaianGroup1($_GET['id'],$_GET['dataId']);
		$unsurPakBaru = $this->Obj->GetDataUnsurPenilaianGroup2($_GET['id'],$_GET['dataId']);
		//print_r($dataPegawaipak);exit;
		$data=$unsurPak;
		for ($i=0; $i<sizeof($unsurPakBaru); $i++){
				$jenis=$$unsurPakBaru[$i]['jenis'];
				$lama[$jenis]+=$unsurPakBaru[$i]['total'];
			}
			
			
			$unsurUtama=array();
			$unsurPenunjang=array();
			$unsur=''; $subunsur='';
			$unsurP='';
			$ii=0; $jj=0;
			for ($i=0; $i<sizeof($unsurPak); $i++){
				if ($unsurPak[$i]['jenis']=='Utama'){
					if ($unsur!=$unsurPak[$i]['unsur']){
						if ($i>0) $ii++;
						$unsur=$unsurPak[$i]['unsur'];
						$unsurUtama[$ii]['nama']='<b>'.$unsur.'</b>';
						$ii++;
					}
					if ($subunsur!=$unsurPak[$i]['subunsur']){
						$subunsur=$unsurPak[$i]['subunsur'];
						$unsurUtama[$ii]['nama']='<b><i>'.$subunsur.'</i></b>';
						$ii++;
					}
					$unsurUtama[$ii]['nama']=$unsurPak[$i]['kegiatan'];
					$unsurUtama[$ii]['baru']=$unsurPak[$i]['total'];
					$unsurUtama[$ii]['lama']=0;
					
					$jumUnsur += $unsurPak[$i]['total'];
					$jumSubunsur += $unsurPak[$i]['total'];
					$ii++;
				}else{
				    if ($unsurP!=$unsurPak[$i]['unsur']){
						$unsurP=$unsurPak[$i]['unsur'];
						$unsurPenunjang[$jj]['nama']='<b>'.$unsurP.'</b>';
						$jj++;
					}
					$unsurPenunjang[$jj]['nama']=$unsurPak[$i]['kegiatan'];
					$unsurPenunjang[$jj]['baru']=$unsurPak[$i]['total'];
					$unsurPenunjang[$jj]['lama']=0;
					$jj++;
				}
			}
			
  	   
			if (empty($unsurUtama)) {
				//$this->mrTemplate->AddVar('unsur_utama', 'DATA_EMPTY', 'YES');
			} else {
				//$this->mrTemplate->AddVar('unsur_utama', 'DATA_EMPTY', 'NO');
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
					//$this->mrTemplate->AddVars('unsur_utama_item', $unsurUtama[$i], '');
					//$this->mrTemplate->parseTemplate('unsur_utama_item', 'a');	 
				}
				//$this->mrTemplate->AddVars('content', $utama, 'UTAMA_');
			}
  		 
			if (empty($unsurPenunjang)) {
				//$this->mrTemplate->AddVar('unsur_penunjang', 'DATA_EMPTY', 'YES');
			} else {
				//$this->mrTemplate->AddVar('unsur_penunjang', 'DATA_EMPTY', 'NO');
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
					//$this->mrTemplate->AddVars('unsur_penunjang_item', $unsurPenunjang[$i], '');
					//$this->mrTemplate->parseTemplate('unsur_penunjang_item', 'a');	 
				}
				//$this->mrTemplate->AddVars('content', $penunjang, 'PENUNJANG_');
			}
  		 
			$tot['lama']=0+$utama['lama']+$penunjang['lama'];
			$tot['baru']=0+$utama['baru']+$penunjang['baru'];
			$tot['jumlah']=0+$utama['jumlah']+$penunjang['jumlah'];
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
		
    	$jumKolom=5;
  		
  		$row++;
		  $this->mWorksheets['pak']->write($row, 0, 'PENGAJUAN ANGKA KREDIT', $this->fH1);
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
		  
		  //$row++;
		  //$this->mWorksheets['pak']->write($row, 0, 'Nomor PAK ', $this->fH2);
		  //$this->mWorksheets['pak']->write($row, 1, ': '.$dataPegawaipak[0]['nopak'], $this->fH2);
		  //$this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		  //$row++;
		  //$this->mWorksheets['pak']->write($row, 0, 'Tanggal Penetapan ', $this->fH2);
		  //$this->mWorksheets['pak']->write($row, 1, ': '.$this->date2string($dataPegawaipak[0]['tanggal_ditetapkan']), $this->fH2);
		  //$this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
		  //$row++;
		  //$this->mWorksheets['pak']->write($row, 0, 'Pejabat ', $this->fH2);
		  //$this->mWorksheets['pak']->write($row, 1, ': '.$dataPegawaipak[0]['pejabat'], $this->fH2);
		  //$this->mWorksheets['pak']->merge_cells($row, 1, $row,$jumKolom-1);
		  
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
		  $this->mWorksheets['pak']->write($row, 0, 'Detail Kegiatan', $this->fH4);
		  $this->mWorksheets['pak']->merge_cells($row, 0, $row,$jumKolom-1);
		  
		  //Set Header
		  $header[0]=array('Butir Kegiatan','AK','Deskripsi','Peran Lokasi Waktu','Bukti Fisik');
		  //$header[1]=array('unsur utama','Lama','Baru','Jumlah');
		  //$footer[0]=array('Jumlah Unsur Utama','','','');
		  //Set Nama Variabel Yang akan ditulis
		  $val=array('','','','','');
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
     $lebar_max=50;
    			for ($i=0; $i<$jumKolom; $i++){
    			  if ($size_col[$val[$i]]>$lebar_max) $size_col[$val[$i]]=$lebar_max;
            $this->mWorksheets['pak']->set_column($i,$i,$size_col[$val[$i]]+3);
			}	
	 /* if (sizeof($dataDetail)<=0) {
          $row++;
    			$this->mWorksheets['pak']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
    			for ($i=1; $i<$jumKolom; $i++){
            $this->mWorksheets['pak']->write($row, $i, '', $this->fH3);
          }
    			$this->mWorksheets['pak']->merge_cells($row, 0, $row,$jumKolom-1);
    	} else {
    			$dataDetail = $data;
    			for ($i=0; $i<sizeof($dataDetail); $i++) {
    					$no = $i+1;
    					
    					$row++; $cols=0;
    					$dataDetail[$i]['no'] = $no;
    					//$dataDetail[$i]['golongan_tmt']=$this->Obj->IndonesianDate($dataPegawai[$i]['golongan_tmt'],'YYYY-MM-DD');
        		  //$dataPegawai[$i]['golongan_yad_tmt']=$this->Obj->IndonesianDate($dataPegawai[$i]['golongan_yad_tmt'],'YYYY-MM-DD');
    					for ($ii=0; $ii<sizeof($val); $ii++){
                $this->mWorksheets['pak']->write($row, $ii, $dataDetail[$i][$val[$ii]], $this->fColData2);
                //Mencari Panjang Data Maksimal
    				    if ($size_col[$val[$ii]]<strlen($dataDetail[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataDetail[$i][$val[$ii]]);}
              }
    			}
    			//Setting Lebar Kolom
    			$lebar_max=50;
    			for ($i=0; $i<$jumKolom; $i++){
    			  if ($size_col[$val[$i]]>$lebar_max) $size_col[$val[$i]]=$lebar_max;
            $this->mWorksheets['pak']->set_column($i,$i,$size_col[$val[$i]]+3);
          }
        }*/
	 
      //$row++;
      //for ($i=0; $i<$jumKolom; $i++){
        //$this->mWorksheets['pak']->write($row, $i, $i+1, $this->fH3);
      //} 
	  //$dataDetail=$data['dataMutasiDetail'];
		$total_angka_kredit=0;
		if (empty($dataDetail)) {
			$this->mWorksheets['pak']->write($row, 0, 'KEGIATAN_LIST_EMPTY ', $this->fH5);
				for ($i=1; $i<$jumKolom; $i++){
						$this->mWorksheets['pak']->write($row, $i, '', $this->fH3);
				}
				$this->mWorksheets['pak']->merge_cells($row, 0, $row,$jumKolom-1);
  		} else {
  			//$this->mrTemplate->AddVar('tpl_kegiatan_list', 'KEGIATAN_LIST_EMPTY', 'NO');
			$unsurDetail=array();
			$unsur=''; $subunsur='';
			$ii=0; $jj=0;
			for ($i=0; $i<count($dataDetail); $i++) {
				//if ($no % 2 != 0) {
					//$dataDetail[$i]['class_name'] = 'table-common-even';
				//}else{
					//$dataDetail[$i]['class_name'] = '';
				//}
				if ($unsurPak[$i]['jenis']){
				if ($unsur!=$dataDetail[$i]['unsur']){
					if ($i>0) $ii++;
					$unsur=$dataDetail[$i]['unsur'];
					$row++;
					$this->mWorksheets['pak']->write($row, 0, $unsur, $this->fColData4);
					$this->mWorksheets['pak']->write($row, 1, '', $this->fColData2);
					$this->mWorksheets['pak']->write($row, 2, '', $this->fColData2);
					$this->mWorksheets['pak']->write($row, 3, '', $this->fColData2);
					$this->mWorksheets['pak']->write($row, 4, '', $this->fColData2);
					$ii++;
				}
				if ($subunsur!=$dataDetail[$i]['subunsur']){
					$subunsur=$dataDetail[$i]['subunsur'];
					$row++; 
					$this->mWorksheets['pak']->write($row, 0, $subunsur, $this->fColData4);
					$this->mWorksheets['pak']->write($row, 1, '', $this->fColData2);
					$this->mWorksheets['pak']->write($row, 2, '', $this->fColData2);
					$this->mWorksheets['pak']->write($row, 3, '', $this->fColData2);
					$this->mWorksheets['pak']->write($row, 4, '', $this->fColData2);
					$ii++;
				}
					$unsurDetail[$ii]['nama']=$dataDetail[$i]['kegiatan'];
					$unsurDetail[$ii]['angka_kredit']=$dataDetail[$i]['angka_kredit'];
					$unsurDetail[$ii]['dekripsi']=$dataDetail[$i]['deskripsi'];
					$unsurDetail[$ii]['peran']=$dataDetail[$i]['peran'];
					$unsurDetail[$ii]['lokasi']=$dataDetail[$i]['lokasi'];
					$unsurDetail[$ii]['waktu']=$dataDetail[$i]['waktu'];
					$unsurDetail[$ii]['bukti']=$dataDetail[$i]['bukti'];
					//$unsur[$ii]['lama']=0;
					
					
					//$jumSubunsur += $unsurPak[$i]['total'];
					$row++;
					$this->mWorksheets['pak']->write($row, 0, $unsurDetail[$ii]['nama'], $this->fColData2);
					$this->mWorksheets['pak']->write($row, 1, $unsurDetail[$ii]['angka_kredit'], $this->fColData3);
					$this->mWorksheets['pak']->write($row, 2, $unsurDetail[$ii]['dekripsi'], $this->fColData2);
					$this->mWorksheets['pak']->write($row, 3, $unsurDetail[$ii]['peran'].','.$unsurDetail[$ii]['lokasi'].','.$unsurDetail[$ii]['waktu'], $this->fColData2);
					$this->mWorksheets['pak']->write($row, 4, $unsurDetail[$ii]['bukti'], $this->fColData2);
					//$jumUnsur += $unsurDetail[$ii]['angka_kredit'];
					$total_angka_kredit += $unsurDetail[$ii]['angka_kredit'];
					$ii++;
					//print_r($total_angka_kredit);exit;
				//$this->mrTemplate->AddVars('tpl_kegiatan_item', $dataDetail[$i], 'DATA_');
				//$this->mrTemplate->parseTemplate('tpl_kegiatan_item', 'a');
				}
				//print_r($unsurDetail[$ii]['angka_kredit']);exit;
      		}
			
  		}
		
		$footer[0]=array('Total',$total_angka_kredit,'','','');
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