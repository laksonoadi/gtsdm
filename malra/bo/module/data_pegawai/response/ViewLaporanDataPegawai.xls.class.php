<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_pegawai/business/data_pegawai.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_kenaikangaji/business/laporan.class.php';
   
class ViewLaporanDataPegawai extends XlsResponse{

    var $mWorksheets = array('DataPegawai');
   
    function GetFileName(){
        // name it whatever you want
        return 'Laporan_DataPegawai_'.str_replace(' ', '_', Laporan::IndonesianDate(date('Y-m-d'),'YYYY-MM-DD')).'.xls';
    }
   
    /*function GetLabelFromCombo($ArrData,$Nilai){
        for ($i=0; $i<sizeof($ArrData); $i++){
            if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
        }
        return '--Semua--';
    }*/
   
    function ProcessRequest(){
        $this->Obj=new DataPegawai;
	    
  		/*if(isset($_POST['unit_kerja'])) {
  				$this->unit_kerja = $_POST['unit_kerja'];
  		} elseif(isset($_GET['unit_kerja'])) {
  				$this->unit_kerja = Dispatcher::Instance()->Decrypt($_GET['unit_kerja']);
  		} else {
  				$this->unit_kerja = 'all';
  		}*/
		$nip_nama       = $_GET['nip_nama'];
		$status_kerja   = $_GET['status_kerja'];	
		$nama_status    = $this->Obj->GetStatusKerja($status_kerja);
		//print_r($nama_status['nama']);die;
		if (empty($nama_status['name'])){ 
			$nama_status_kerja = 'Semua';
		}
		else{
			$nama_status_kerja = $nama_status['name']; 
		}
  		//$this->ComboUnitKerja=$this->Obj->GetComboUnitKerja();
  		//$this->label_unit_kerja=$this->GetLabelFromCombo($this->ComboUnitKerja,$this->unit_kerja);
  		  				  
		//$totalData = $Obj->GetCountPegawaiByUserId($nip_nama, $status_kerja);
		$dataPegawai = $this->Obj->GetDataPegawaiByUserIdCetak($nip_nama, $status_kerja);
		//print_r($dataPegawai);die;
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
    	
    	$jumKolom = 38;
  		$row++;
		$this->mWorksheets['DataPegawai']->write($row, 0, 'DATA PEGAWAI', $this->fH1);
    	$this->mWorksheets['DataPegawai']->merge_cells($row, 0, $row,$jumKolom-1);
    			  
		$row++;
		$this->mWorksheets['DataPegawai']->write($row, 0, 'Status Kerja  : '.$nama_status_kerja , $this->fH2);
		$this->mWorksheets['DataPegawai']->merge_cells($row, 0, $row,$jumKolom-1);
		  
		//Set Header
		$header[0]=array('No','Data Pegawai','','','','','','','','','','','Data Lain','','','','','','Informasi Medis ','','','','','Informasi pegawai ketika rekruitmen','','','','','','','','','','','','','','','');
		$header[1]=array('','NIP','NIDN','Nomor Induk Internal','Nomor Kode Absen','Nama','Tempat & tgl lahir','Tipe ID','Nomor ID','Jender','Agama','Status Pernikahan','Alamat','Kota','Kode POS','Nomor Telepon','Nomor HP','Email','Golongan Darah','Tinggi Badan','Berat Badan','Cacat','Hobi','Tanggal Masuk','TMT PNS','TMT CPNS','Kategori Pegawai','Tipe Pegawai','Status Pekerjaan','Status Pekerjaan','Bank','Nomor Rekening Bank','Rekening Bank Penerima','Nomor Jaminan Sosial','Nomor Asuransi Kesehatan','Status NPWP','NPWP ','Batas Usia Pensiun ');
		//Set Nama Variabel Yang akan ditulis
		$val=array('no','nip','NIDN','pegKodeInternal','absen','nama','lahir','tipe_id','no_id','jender','agama','nikah','alamat','wil','pos','telp','hp','email','gol_darah','tinggi_badan','berat_badan','cacat','hobi','tgl_masuk','tmt_pns','tmt_cpns','kategori','tipe_pegawai','statpeker','statpeg','bank','rekening','nama_rekening','jam_sos','jam_kes','statNPWP','no_NPWP','usia_pensiun');
		  
		//Set Lebar Kolom Awal =0
		for ($i=0; $i<$jumKolom; $i++){
            $size_col[$val[$i]]=0;
        }
		  
        $row++;
		$k = 0;
        $Htemp = $header;
        for ($i = 0; $i<sizeof($header); $i++){
            $row++;
            for ($ii=0; $ii<sizeof($val); $ii++){
                $this->mWorksheets['DataPegawai']->write($row, $ii, $header[$i][$ii], $this->fH3);
    			if ($size_col[$val[$ii]]<strlen($header[$i][$ii])) {$size_col[$val[$ii]]=strlen($header[$i][$ii]);}
    			
    			$bottom = $i+1;
    			if (($Htemp[$i][$ii]!='')&&($bottom<sizeof($Htemp))&&($Htemp[$bottom][$ii]=='')){
    			    $bottom=$i+1;
    			    $k++;
    			    while (($bottom<sizeof($Htemp))&&($Htemp[$bottom][$ii]=='')){
    			        $merger[$k]['row_awal']  = $i+$row;
        			    $merger[$k]['row_akhir'] = $bottom+$row;
        			    $merger[$k]['col_awal']  =$ii;
        			    $merger[$k]['col_akhir'] = $ii;
        			    $Htemp[$bottom][$ii] = 'WHY';
        			    $bottom++;
                    }
                }
          
                $left=$ii+1;
    			if (($Htemp[$i][$ii]!='')&&($left<sizeof($val))&&($Htemp[$i][$left]=='')){
    			    $left = $ii+1;
    			    $k++;
    			    while (($left<sizeof($val))&&($Htemp[$i][$left]=='')){
    			        $merger[$k]['row_awal']  = $i+$row;
        			    $merger[$k]['row_akhir'] = $i+$row;
        			    $merger[$k]['col_awal']  = $ii;
        			    $merger[$k]['col_akhir'] = $left;
        			    $Htemp[$i][$left] = 'WHY';
        			    $left++;
                    }
                }
            }
        }

        for ($i = 1; $i<=sizeof($merger); $i++){
		    $this->mWorksheets['DataPegawai']->merge_cells($merger[$i]['row_awal'], $merger[$i]['col_awal'], $merger[$i]['row_akhir'], $merger[$i]['col_akhir']);
        }
        //Menulis Angka sebelum data
        $row++;
        for ($i = 0; $i<$jumKolom; $i++){
            $this->mWorksheets['DataPegawai']->write($row, $i, $i+1, $this->fH3);
        }
      
        if (sizeof($data)<=0) {
            $row++;
    		$this->mWorksheets['DataPegawai']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
    		for ($i=1; $i<$jumKolom; $i++){
                $this->mWorksheets['DataPegawai']->write($row, $i, '', $this->fH3);
            }
    		$this->mWorksheets['DataPegawai']->merge_cells($row, 0, $row,$jumKolom-1);
        }
        else{
			$dataPegawai = $data;
            
			for ($i=0; $i<sizeof($dataPegawai); $i++) {
				$row++;
				$no   = $i + 1;
				$cols = 0;
    			$dataPegawai[$i]['no']  = $no;
    			$dataPegawai[$i]['nip'] = " ".$dataPegawai[$i]['nip'];

                //Menulis Datanya
                for ($ii=0; $ii<sizeof($val); $ii++){
                    $this->mWorksheets['DataPegawai']->write($row, $ii, $dataPegawai[$i][$val[$ii]], $this->fColData2);
                    //Mencari Panjang Data Maksimal
    				if ($size_col[$val[$ii]]<strlen($dataPegawai[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataPegawai[$i][$val[$ii]]);}
                }
    		}
    				
			//Setting Lebar Kolom
			$lebar_max=50;
			for ($i=0; $i<$jumKolom; $i++){
                if ($size_col[$val[$i]]>$lebar_max) $size_col[$val[$i]]=$lebar_max;
                $this->mWorksheets['DataPegawai']->set_column($i,$i,$size_col[$val[$i]]+3);
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