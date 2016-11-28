<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/laporan_demografi/business/Demografi.class.php';

class ViewExcelDemografi extends XlsResponse{
  var $mWorksheets = array('Data');

  function GetFileName() {
      // name it whatever you want
      return 'LaporanDemographic.xls';
   }
  
  function ProcessRequest(){
    $this->Obj = new Demografi;
    
    $data['kategori']['id']=array('manpower');
    $data['kategori']['judul']=array(' ');
    
    $data['unit_kerja']=$this->Obj->GetComboUnitKerjaLike();
    if (isset($_GET['cari'])){
        //Status
        $val='status'; $judul='STATUS PEGAWAI';
        if ($_GET[$val]=='on'){
          array_push($data['kategori']['id'],$val);
          array_push($data['kategori']['judul'],$judul);
        }
        //Posisi
        $val='posisi'; $judul='JABATAN STRUKTURAL';
        if ($_GET[$val]=='on'){
          array_push($data['kategori']['id'],$val);
          array_push($data['kategori']['judul'],$judul);
        }
        //Grade
        $val='grade'; $judul='PANGKAT DAN GOLONGAN';
        if ($_GET[$val]=='on'){
          array_push($data['kategori']['id'],$val);
          array_push($data['kategori']['judul'],$judul.' (Non Academic Staff)');
          
          array_push($data['kategori']['id'],$val.'_academic');
          array_push($data['kategori']['judul'],$judul.' (Academic Staff)');
        }
        //Lama Kerja
        $val='lama_kerja'; $judul='MASA KERJA';
        if ($_GET[$val]=='on'){
          array_push($data['kategori']['id'],$val);
          array_push($data['kategori']['judul'],$judul);
        }
        //Jenis Kelamin
        $val='jenis_kelamin'; $judul='JENIS KELAMIN';
        if ($_GET[$val]=='on'){
          array_push($data['kategori']['id'],$val);
          array_push($data['kategori']['judul'],$judul);
        }
        //Umur
        $val='umur'; $judul='UMUR';
        if ($_GET[$val]=='on'){
          array_push($data['kategori']['id'],$val);
          array_push($data['kategori']['judul'],$judul);
        }
        //Pendidikan
        $val='pendidikan'; $judul='LATAR BELAKANG PENDIDIKAN';
        if ($_GET[$val]=='on'){
          array_push($data['kategori']['id'],$val);
          array_push($data['kategori']['judul'],$judul);
        }
        //Status Nikah
        $val='status_nikah'; $judul='STATUS PERNIKAHAN';
        if ($_GET[$val]=='on'){
          array_push($data['kategori']['id'],$val);
          array_push($data['kategori']['judul'],$judul);
        }
        //Agama
        $val='agama'; $judul='AGAMA';
        if ($_GET[$val]=='on'){
          array_push($data['kategori']['id'],$val);
          array_push($data['kategori']['judul'],$judul);
        }
    }
    
    //Get Data
    for ($j=0; $j<sizeof($data['kategori']['id']); $j++){
        $val=$data['kategori']['id'][$j];
        $data[$val]['checked']='checked';
        unset($list);
        if ($val=='manpower'){
          $list[0]['id']=0; $list[0]['label']='Total Manpower';
        }else if ($val=='lama_kerja'){
          $list[0]['id']=-100; $list[0]['id2']=5; $list[0]['label']='0 s/d 5 Tahun';
          $list[1]['id']=6; $list[1]['id2']=10; $list[1]['label']='6 s/d 10 Tahun';
          $list[2]['id']=11; $list[2]['id2']=15; $list[2]['label']='11 s/d 15 Tahun';
          $list[3]['id']=16; $list[3]['id2']=20; $list[3]['label']='16 s/d 20 Tahun';
          $list[4]['id']=21; $list[4]['id2']=25; $list[4]['label']='21 s/d 25 Tahun';
		  $list[4]['id']=26; $list[4]['id2']=200; $list[4]['label']='Lebih dari 25 tahun';
        }else if ($val=='jenis_kelamin'){
          $list[0]['id']='L'; $list[0]['label']='Laki-laki';
          $list[1]['id']='P'; $list[1]['label']='Perempuan';
        }else if ($val=='umur'){
          $list[0]['id']=17; $list[0]['id2']=24; $list[0]['label']='17 s/d 24';
          $list[1]['id']=25; $list[1]['id2']=35; $list[1]['label']='25 s/d 35';
          $list[2]['id']=36; $list[2]['id2']=46; $list[2]['label']='36 s/d 46';
          $list[3]['id']=47; $list[3]['id2']=54; $list[3]['label']='47 s/d 54';
          $list[4]['id']=55; $list[4]['id2']=200; $list[4]['label']='55 Above';
          
        }else{
          $list=$this->Obj->GetList($val);
        }
        $data[$val]['label']=$list;
          
        for ($i=0; $i<sizeof($list); $i++){
            for ($ii=0; $ii<sizeof($data['unit_kerja']); $ii++){
              $unit=$data['unit_kerja'][$ii]['id'];
              $id=$list[$i]['id'];
              $id2=$list[$i]['id2']; 
              $data[$val]['nilai'][$i][$ii]=$this->Obj->GetJumlah($val,$unit,array($id,$id2));
            }  
        }
    }
    
     
    $unitkerja=$data['unit_kerja'];
    
    $fHeader = $this->mrWorkbook->add_format();
    $fHeader->set_bold();
    $fHeader->set_size(14);
    
    $fTitle = $this->mrWorkbook->add_format();
    $fTitle->set_border(1);
    $fTitle->set_text_wrap(1);
    $fTitle->set_bold();
    $fTitle->set_size(12);
    $fTitle->set_bg_color('green');
    $fTitle->set_align('vcenter');
    $fTitle->set_align('center');
    
    $frTitle = $this->mrWorkbook->add_format();
    $frTitle->set_bold();
    $frTitle->set_size(12);
    $frTitle->set_align('left');
    
    $fText = $this->mrWorkbook->add_format();
    $fText->set_border(1);
    $fText->set_size(12);
    $fText->set_align('vcenter');
    $fText->set_align('left');
    
    $fNilai = $this->mrWorkbook->add_format();
    $fNilai->set_border(1);
    $fNilai->set_size(12);
    $fNilai->set_align('vcenter');
    $fNilai->set_align('center');
    
    $fxText = $this->mrWorkbook->add_format();
    $fxText->set_border(1);
    $fxText->set_size(12);
    $fxText->set_color('blue');
    $fxText->set_align('vcenter');
    $fxText->set_align('left');
    
    $fxNilai = $this->mrWorkbook->add_format();
    $fxNilai->set_border(1);
    $fxNilai->set_size(12);
    $fxNilai->set_color('blue');
    $fxNilai->set_fg_color('aqua');
    $fxNilai->set_align('vcenter');
    $fxNilai->set_align('center');
    
    
    //$this->mWorksheets['Data']->merge_cell(1, 0, 1, sizeof($unitkerja)+3, strtoupper('Sampoerna School Of Education'), $this->fH2);
    //$this->mWorksheets['Data']->merge_range(2, 0, 2, sizeof($unitkerja)+3, 'EMPLOYEE DEMOGRAPHIC PROFILE', $this->fH1);
    
    $this->mWorksheets['Data']->write(0, 0, strtoupper('LAPORAN'), $fHeader);
    $this->mWorksheets['Data']->write(1, 0, 'DEMOGRAFI KARYAWAN', $fHeader);
    
    
    //Header
    $this->mWorksheets['Data']->write(4, 0, 'NO', $fTitle);
    $this->mWorksheets['Data']->write(4, 1, 'DESCRIPTION', $fTitle);
    $this->mWorksheets['Data']->write(4, 2, 'TOTAL', $fTitle);
    for ($i=0; $i<sizeof($unitkerja); $i++){
         $total2[$i]=0;
         $this->mWorksheets['Data']->write(4, $i+3,$unitkerja[$i]['label'], $fTitle);
    }
    
    $x=4; $no=0;
    for ($j=0; $j<sizeof($data['kategori']['id']); $j++){
        $val=$data['kategori']['id'][$j];
        if ($j==0) {$var='m'; } else {$var='';}
        
        //Inisialisasi
        $label=$data[$val]['label'];
        $list=$data[$val]['nilai'];
        $checked=$data[$val]['checked'];
        $judul=$data['kategori']['judul'][$j];
        $label_checked=strtoupper($val);
        
        for ($i=0; $i<sizeof($unitkerja); $i++){
            $total2[$i]=0;    
        }
        
        if ($j>0) {
          $x++;$x++;
          //$this->mWorksheets['Data']->merge_range($x, 0, $x, sizeof($unitkerja)+3,$judul , $this->fColTitle);
          $this->mWorksheets['Data']->write($x, 0 ,$judul , $frTitle);
        }
        //Isi
        for ($i=0; $i<sizeof($label); $i++){
            $x++;
            if ($j>0) { $no++; }
            
            $total1[$i]=0;
            for ($ii=0; $ii<sizeof($unitkerja); $ii++){
              $total1[$i] += $list[$i][$ii];  
            }
            
            $label[$i]['number']=$no;
            $label[$i]['total']=$total1[$i];
            
            $this->mWorksheets['Data']->write($x, 0,$label[$i]['number'], $fText);
            $this->mWorksheets['Data']->write($x, 1,$label[$i]['label'], $fText);
            $this->mWorksheets['Data']->write($x, 2,$label[$i]['total'], $fNilai);
            
            for ($ii=0; $ii<sizeof($unitkerja); $ii++){
              $total2[$ii] += $list[$i][$ii];
              if ($list[$i][$ii]==0){ $list[$i][$ii]='-'; }
              $colspan=1;
              $this->mWorksheets['Data']->write($x, $ii+3,$list[$i][$ii], $fNilai);
            }
        }
        
        if ($j>0){
          $x++;
          $this->mWorksheets['Data']->write($x,1,'Sub Total', $fxText);
          $total=0;
          for ($ii=0; $ii<sizeof($total2); $ii++){
              $total += $total2[$ii];
              $this->mWorksheets['Data']->write($x, $ii+3,$total2[$ii], $fxNilai);   
          }
          $this->mWorksheets['Data']->write($x, 2,$total, $fxNilai);
        }
        
    }
  }  
}

?>