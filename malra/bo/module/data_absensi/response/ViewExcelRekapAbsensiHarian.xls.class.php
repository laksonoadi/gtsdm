<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/laporan_duk_individu/business/laporan.class.php';
//require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_duk/business/laporan.class.php';
   
class ViewExcelRekapAbsensiHarian extends XlsResponse{
    
    var $mWorksheets = array("Absen Panjang");
   
    function GetFileName() {
        // name it whatever you want
        return 'Absen_panjang_'.str_replace(' ', '_', date('m-Y')).'.xls';
    }
   
    function GetLabelFromCombo($ArrData,$Nilai){
        for ($i=0; $i<sizeof($ArrData); $i++){
            if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
        }
        return '--Semua--';
    }
   	
    function ProcessRequest(){
        $this->Obj = new Laporan;
      
        if(isset($_POST['unit_kerja'])) {
            $this->unit_kerja = $_POST['unit_kerja'];
        }
        elseif(isset($_GET['unit_kerja'])) {
            $this->unit_kerja = Dispatcher::Instance()->Decrypt($_GET['unit_kerja']);
        }
        else{
            $this->unit_kerja = 'all';
        }

        if(isset($_POST['tampilkan'])){
            $tampilkan = $_POST['tampilkan'];
        }
        elseif(isset($_GET['tampilkan'])){
            $tampilkan = $_GET['tampilkan'];
        }
        else{
            $tampilkan='';
        }

        $this->ComboUnitKerja 	= $this->Obj->GetComboUnitKerja();
        $this->label_unit_kerja = $this->GetLabelFromCombo($this->ComboUnitKerja, $this->unit_kerja);

        $totalData = $this->Obj->GetCountDataAbsen();

        $dataPegawai = $this->Obj->GetDataAbsen($tampilkan, 0, $totalData);
    
        $data = $dataPegawai;
        $row = -1;
      
        $this->fH1 = $this->mrWorkbook->add_format();
        $this->fH1->set_bold();
        $this->fH1->set_size(12);
        $this->fH1->set_align('vcenter');
        $this->fH1->set_align('center');
      
        $this->fH2 = $this->mrWorkbook->add_format();
        $this->fH2->set_bold();
        $this->fH2->set_size(12);
        $this->fH2->set_align('vcenter');
        $this->fH2->set_align('center');
         
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
        //$this->fColData2->set_num_format();

        $jumKolom=8;

        $row++;
        $this->mWorksheets["Absen Panjang"]->write($row, 0, 'DAFTAR HADIR : PNS PEMKO BANJARMASIN ', $this->fH1);
        $this->mWorksheets["Absen Panjang"]->merge_cells($row, 0, $row,$jumKolom-1);

        $row++;
        $this->mWorksheets["Absen Panjang"]->write($row, 0, 'UNIT KERJA : '.$this->label_unit_kerja , $this->fH2);
        $this->mWorksheets["Absen Panjang"]->merge_cells($row, 0, $row,$jumKolom-1);

        $row++;
        $this->mWorksheets["Absen Panjang"]->write($row, 0, 'HARI / TANGGAL  : '.date('j / F Y'), $this->fH2);
        $this->mWorksheets["Absen Panjang"]->merge_cells($row, 0, $row,$jumKolom-1);

        //Set Header
        $header[0]=array('NO', 'NIP', 'NAMA','GOL','JABATAN','HADIR',''
                       ,'KETERANGAN');
        $header[1]=array('','','','','','JAM','TANDA TANGAN','');
                       
        //Set Nama Variabel Yang akan ditulis
        $val=array('no','nip','nama','golongan','jabatan','','','');

        //Set Lebar Kolom Awal =0
        for ($i=0; $i<$jumKolom; $i++){
            $size_col[$val[$i]]=0;
        }
      
        //Menulis Header/Judul Table
        $row++;
        $k = 0;
        $Htemp = $header;
        for ($i = 0; $i<sizeof($header); $i++){
            $row++;
            for ($ii=0; $ii<sizeof($val); $ii++){
                $this->mWorksheets["Absen Panjang"]->write($row, $ii, $header[$i][$ii], $this->fH3);
                if ($size_col[$val[$ii]]<strlen($header[$i][$ii])) { $size_col[$val[$ii]] = strlen($header[$i][$ii]); }
                
                $bottom = $i + 1;
                if (($Htemp[$i][$ii]!='')&&($bottom<sizeof($Htemp))&&($Htemp[$bottom][$ii]=='')){
                    $bottom = $i + 1;
                    $k++;
                    while (($bottom<sizeof($Htemp))&&($Htemp[$bottom][$ii]=='')){
                        $merger[$k]['row_awal']  = $i + $row;
                        $merger[$k]['row_akhir'] = $bottom + $row;
                        $merger[$k]['col_awal']  = $ii;
                        $merger[$k]['col_akhir'] = $ii;
                        $Htemp[$bottom][$ii]     = 'WHY';
                        $bottom++;
                    }
                }
                
                $left = $ii+1;
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

        for ($i = 1; $i<=sizeof($merger); $i++){
            $this->mWorksheets["Absen Panjang"]->merge_cells($merger[$i]['row_awal'], $merger[$i]['col_awal'], $merger[$i]['row_akhir'], $merger[$i]['col_akhir']);
        }
      
        $row++;
        for ($i = 0; $i<$jumKolom; $i++){
            $this->mWorksheets["Absen Panjang"]->write($row, $i, $i+1, $this->fH3);
        }
      
        if(sizeof($data) <= 0){
            $row++;
            $this->mWorksheets["Absen Panjang"]->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
            for ($i=1; $i<$jumKolom; $i++){
                $this->mWorksheets["Absen Panjang"]->write($row, $i, '', $this->fH3);
            }
            $this->mWorksheets["Absen Panjang"]->merge_cells($row, 0, $row,$jumKolom-1);
        }
        else{
            $dataPegawai = $data;
            for ($i=0; $i<sizeof($dataPegawai); $i++){
                $no = $i+1;
              
                // $thn = $dataPegawai[$i]['latihan_tahun'];
                // $nama = $dataPegawai[$i]['latihan_nama'];
                // $jam = $dataPegawai[$i]['latihan_jam'];

                // $thn = explode(',', $thn);  
                // $nama = explode(',', $nama);
                // $jam = explode(',', $jam);


                // $dataPegawai[$i]['latihan_thn'] = implode("\n",$thn);
                // $dataPegawai[$i]['latihan_nm'] = implode("\n",$nama);
                // $dataPegawai[$i]['latihan_jm'] = implode("\n",$jam);

                // if(!empty($thn)) {
                //   foreach ($thn as $key => $th) {
                //     $tahun = $th.'<br/>';
                //     $dataPegawai[$i]['latihan_thn'] = $tahun;    
                //   }
                // }

                // if(!empty($nama)) {
                //   foreach ($nama as $key => $nm) {
                //     $dataPegawai[$i]['latihan_nm'] = $nm; 
                //   }
                // }

                // if(!empty($jam)) {
                //   foreach ($jam as $key => $jm) {
                //     $dataPegawai[$i]['latihan_jm'] = $jm; 
                //   }
                // }

                $row++;
                $cols = 0;
                $dataPegawai[$i]['no']  = $no;
    			$dataPegawai[$i]['nip'] = " ".$dataPegawai[$i]['nip'];

                for ($ii=0; $ii < sizeof($val); $ii++){
                    $this->mWorksheets["Absen Panjang"]->write($row, $ii, $dataPegawai[$i][$val[$ii]], $this->fColData2);
                    //Mencari Panjang Data Maksimal
                    if ($size_col[$val[$ii]]<strlen($dataPegawai[$i][$val[$ii]])) {$size_col[$val[$ii]]=strlen($dataPegawai[$i][$val[$ii]]);}
                }
            }
            //Setting Lebar Kolom
            $lebar_max=50;
            for ($i=0; $i<$jumKolom; $i++){
                if ($size_col[$val[$i]]>$lebar_max) $size_col[$val[$i]]=$lebar_max;
                $this->mWorksheets["Absen Panjang"]->set_column($i,$i,$size_col[$val[$i]]+3);
            }
        }
    }
   
    function GetCol($nilai){
        $var    = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $awal   = round($nilai/26);
        $akhir  = $nilai % 26;
      
        return $var[$awal-1].$var[$akhir-1];
    }
}

?>
