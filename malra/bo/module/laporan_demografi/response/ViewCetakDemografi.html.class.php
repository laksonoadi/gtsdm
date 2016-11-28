<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/laporan_demografi/business/Demografi.class.php';

class ViewCetakDemografi extends HtmlResponse{

  function TemplateModule(){
    $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
    'module/laporan_demografi/'.GTFWConfiguration::GetValue('application', 'template_address').'');
    $this->SetTemplateFile('view_cetak_demografi.html');
  }
  
  function TemplateBase() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') . 'main/template/');
		$this->SetTemplateFile('document-print.html');
		$this->SetTemplateFile('layout-common-print.html');
	 }
  
  function ProcessRequest(){
    $this->Obj = new Demografi;
    $data['link']['url_search'] = Dispatcher::Instance()->GetUrl('laporan_demografi','demografi','view','html');
    $data['link']['url_cetak'] = Dispatcher::Instance()->GetUrl('laporan_demografi','cetakDemografi','view','html');
    $data['link']['url_excel'] = Dispatcher::Instance()->GetUrl('laporan_demografi','excelDemografi','view','html');
    
    
    $data['kategori']['id']=array('manpower');
    $data['kategori']['judul']=array(' ');
    
    $data['unit_kerja']=$this->Obj->GetListUnitKerja();
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
        $val='grade'; $judul='PANGKAT/GOLONGAN';
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
        if ($_POST[$val]=='on'){
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
    
    //echo "<pre>";print_r($data);echo "</pre>"; 
    return $data;
  }
  
  function ParseTemplate($data = NULL){
    $unitkerja=$data['unit_kerja'];
    
    //tentukan value judul, button dll sesuai pilihan bahasa 
    if ($data['lang']=='eng'){
      $this->mrTemplate->AddVar('content', 'TITLE', 'EMPLOYEE DEMOGRAPHIC PROFILE');
      $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Report Data');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
    }else{
      $this->mrTemplate->AddVar('content', 'TITLE', 'PROFIL DEMOGRAFI KARYAWAN');
      $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Data Laporan');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');  
    } 
    
    //Filter Form
    for ($j=0; $j<sizeof($data['kategori']['id']); $j++){
        $val=$data['kategori']['id'][$j];
        $label_checked=strtoupper($val);
        $checked=$data[$val]['checked'];
        $this->mrTemplate->AddVar('content', $label_checked, $checked);
    }
    $this->mrTemplate->AddVar('content', 'URL_SEARCH', $data['link']['url_search']);
    $this->mrTemplate->AddVar('content', 'URL_PRINT', $data['link']['url_cetak']);
    $this->mrTemplate->AddVar('content', 'URL_EXCEL', $data['link']['url_excel']);
    
    //Header
    $this->mrTemplate->clearTemplate('data_judul');
    for ($i=0; $i<sizeof($unitkerja); $i++){
         $total2[$i]=0;
         $this->mrTemplate->AddVar('data_judul', 'LABEL_JUDUL',$unitkerja[$i]['label']) ;
         $this->mrTemplate->parseTemplate('data_judul','a');    
    }
    
    $no=0;
    
    for ($j=0; $j<sizeof($data['kategori']['id']); $j++){
        $val=$data['kategori']['id'][$j];
        if ($j==0) {$var='m'; } else {$var='';}
        //$this->mrTemplate->clearTemplate('data_kategori_'.$var);
        
        //Inisialisasi
        $label=$data[$val]['label'];
        $list=$data[$val]['nilai'];
        $checked=$data[$val]['checked'];
        $judul=$data['kategori']['judul'][$j];
        $label_checked=strtoupper($val);
        
        for ($i=0; $i<sizeof($unitkerja); $i++){
            $total2[$i]=0;    
        }
            
        //Isi
        for ($i=0; $i<sizeof($label); $i++){
            if ($j>0) { $no++; }
            
            $total1[$i]=0;
            for ($ii=0; $ii<sizeof($unitkerja); $ii++){
              $total1[$i] += $list[$i][$ii];  
            }
            
            $label[$i]['number']=$no;
            $label[$i]['total']=$total1[$i];
            $this->mrTemplate->AddVars('data_item_'.$var,$label[$i],'') ;
            
            
            $this->mrTemplate->clearTemplate('data_nilai_'.$var);
            for ($ii=0; $ii<sizeof($unitkerja); $ii++){
              $total2[$ii] += $list[$i][$ii];
              if ($list[$i][$ii]==0){ $list[$i][$ii]='-'; }
              $colspan=1;
              $this->mrTemplate->AddVar('data_nilai_'.$var,'NILAI',$list[$i][$ii]) ;
              $this->mrTemplate->AddVar('data_nilai_'.$var,'COLSPAN',$colspan) ;
              $this->mrTemplate->parseTemplate('data_nilai_'.$var,'a'); 
            }
            
            $this->mrTemplate->parseTemplate('data_item_'.$var,'a');
        }
        
        
        if ($j>0){
          $this->mrTemplate->clearTemplate('data_sub_total_'.$var);
          $total=0;
          for ($ii=0; $ii<sizeof($total2); $ii++){
              $total += $total2[$ii];
              $this->mrTemplate->AddVar('data_sub_total_'.$var, 'SUB_TOTAL',$total2[$ii]) ;
              $this->mrTemplate->parseTemplate('data_sub_total_'.$var,'a');    
          }
        }
        
        
        $this->mrTemplate->SetAttribute('data_kategori_'.$var, 'visibility', 'visible');
        $this->mrTemplate->AddVar('data_kategori_'.$var, 'TOTAL',$total) ;
        $this->mrTemplate->AddVar('data_kategori_'.$var, 'JUM',sizeof($unitkerja)+3) ;
        $this->mrTemplate->AddVar('data_kategori_'.$var, 'JUDUL_KATEGORI',$judul) ;
        $this->mrTemplate->parseTemplate('data_kategori_'.$var,'a');
        $this->mrTemplate->clearTemplate('data_item_'.$var);
    }
    
    
  }
}

?>