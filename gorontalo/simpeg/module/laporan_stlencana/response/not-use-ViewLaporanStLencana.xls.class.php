<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_stlencana/business/laporanSL.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/cetak_sk/business/CetakSK.class.php';
   
class ViewLaporanStLencana extends XlsResponse{
  var $mWorksheets = array('Daftar');
   
  function GetFileName() {
    // name it whatever you want
    return 'Laporan_Satya_Lencana_'.date('Ymd').'.xls';
  }
   
  function GetLabelFromCombo($ArrData,$Nilai){
    for ($i=0; $i<sizeof($ArrData); $i++){
      if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
    }
    return '--Semua--';
  }
   
  function ProcessRequest(){
    set_time_limit(0);
    $this->Obj=new LaporanSL;
    
    $this->Obj->getVariabelGlobal();
    $this->judul=$this->Obj->judul;
    $this->tanggal=$this->Obj->IndonesianDate(date('Y-m-d'),"YYYY-MM-DD");
    
      if(isset($_GET['cari'])) {
      for ($i=0; $i<sizeof($this->Obj->varFilter); $i++){
        $this->Obj->filter[$this->Obj->varFilter[$i]] = Dispatcher::Instance()->Decrypt(strval($_GET[$this->Obj->varFilter[$i]]));
      }
      
      $this->Obj->berdasarkan = strval($_GET['berdasarkan']);
      $this->Obj->urutan = strval($_GET['urutan']);
      
      }
    
    $this->Obj->getVariabelGlobal();
    
    $dataPegawai = $this->Obj->GetDaftarPegawai();
      $data=$dataPegawai;
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
    $field=array_keys($this->Obj->field);
    $header[0]=array('No.');
    $header[1]=array('');
    $val=array('no');
    
    for ($i=0; $i<sizeof($field)-1; $i++){
      $temp= Dispatcher::Instance()->Decrypt(strval($_GET[$field[$i]]));
      if ($temp=='on'){
        array_push($header[0],$this->Obj->caption[$field[$i]][0]);
        array_push($val,$this->Obj->field[$field[$i]][0]);
        if (sizeof($this->Obj->caption[$field[$i]])>1) {
          for ($jj=1; $jj<sizeof($this->Obj->caption[$field[$i]]); $jj++){
            array_push($header[1],$this->Obj->caption[$field[$i]][$jj]);
          }
        }else{
          array_push($header[1],'');
        }
        
        for ($jj=1; $jj<sizeof($this->Obj->field[$field[$i]]); $jj++){
          array_push($header[0],'');
          array_push($val,$this->Obj->field[$field[$i]][$jj]);
        }
      }
    }
      
      $jumKolom=sizeof($val);
      
      $row++;
    $this->mWorksheets['Daftar']->write($row, 0, 'DAFTAR PEGAWAI Per '.$this->Obj->IndonesianDate(date('Y-m-d'),'YYYY-MM-DD'), $this->fH1);
      $this->mWorksheets['Daftar']->merge_cells($row, 0, $row,$jumKolom-1);
      
      var_dump($header, $val); exit;
      
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
      
    $row++;//$row++;
    for ($i=0; $i<$jumKolom; $i++){
      $this->mWorksheets['Daftar']->write($row, $i, $i+1, $this->fH3);
    }
      
    if (sizeof($data)<=0) {
      $row++;
        $this->mWorksheets['Daftar']->write($row, 0, 'Data Tidak Ditemukan', $this->fH3);
        for ($i=1; $i<$jumKolom; $i++){
        $this->mWorksheets['Daftar']->write($row, $i, '', $this->fH3);
      }
        $this->mWorksheets['Daftar']->merge_cells($row, 0, $row,$jumKolom-1);
    } else {
        $dataPegawai = $data;
        for ($i=0; $i<sizeof($dataPegawai); $i++) {
          $no = $i+1;
              
          $row++; $cols=0;
          $dataPegawai[$i]['no'] = $no;
        $dataPegawai[$i]['pegKodeResmi'] .= ' ';
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