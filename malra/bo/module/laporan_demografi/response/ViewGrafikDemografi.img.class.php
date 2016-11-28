<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/laporan_demografi/business/Demografi.class.php';

class ViewGrafikDemografi extends ImgResponse{  
  function get_color($i)
    {
        $colors = array(
                        0=>'aqua',
                        1=>'silver',
                        2=>'yellow',
                        3=>'blue',
                        4=>'brown',
                        5=>'magenta',
                        6=>'fuchsia',
                        7=>'gray',
                        8=>'grey',
                        9=>'green',
                        10=>'lime',
                        11=>'navy',
                        12=>'orange',
                        13=>'purple',
                        14=>'red',
                        15=>'cyan',
                        16=>'white',
                        17=>'black'
                       );
    
        return $colors[$i];
  }
  
  function ProcessRequest(){
    $this->Obj = new Demografi;
    
    $data['kategori']['id']=array('manpower');
    $data['kategori']['judul']=array(' TOTAL MANPOWER ');
    
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
        $val='pendidikan'; $judul='EDUCATION BACKGROUND';
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
    
     
    $unitkerja=$data['unit_kerja'];
    
    for ($i=0; $i<sizeof($unitkerja); $i++){
         $total2[$i]=0;
         $datax[]=$unitkerja[$i]['label'];
    }
    
    for ($j=1; $j<sizeof($data['kategori']['id']); $j++){
        $val=$data['kategori']['id'][$j];
        
        //Inisialisasi
        $label=$data[$val]['label'];
        $list=$data[$val]['nilai'];
        $checked=$data[$val]['checked'];
        $judul=$data['kategori']['judul'][$j];
        $label_checked=strtoupper($val);
        
        $targ=array("");
        $alts= array("val=%d","val=%d");
        for ($i=0; $i<sizeof($label); $i++){
          for ($ii=0; $ii<sizeof($unitkerja); $ii++){
                $total2[$ii] += $list[$i][$ii];
                $datay[$i][]=$list[$i][$ii];
          }
          $bplot[$i] = new BarPlot($datay[$i]);
          $bplot[$i]->SetCSIMTargets($targ,$alts);
          
          $bplot[$i]->SetFillColor($this->get_color($i));
          $bplot[$i]->SetShadow();
          $bplot[$i]->value->SetFormat("%d",70);
          $bplot[$i]->value->SetFont(FF_ARIAL,FS_NORMAL,9);
          $bplot[$i]->value->SetColor("blue");
          $bplot[$i]->value->Show();
          $bplot[$i]->SetLegend($label[$i]['label'],$this->get_color($i));
        }
        
        // Create the graph. 
        // One minute timeout for the cached image
        // INLINE_NO means don't stream it back to the browser.
        $graph = new Graph(1200,700,'auto');	
        $graph->SetScale("textlin");
        $graph->SetShadow();
        $graph->img->SetMargin(40,30,40,250);
        $graph->xaxis->SetTickLabels($datax);
        $graph->xaxis->SetLabelAngle(90);
        $graph->xaxis->title->Set('');
        $graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
        
        $graph->title->Set('Employee Demographic ('.$judul.')');
        $graph->title->SetFont(FF_FONT1,FS_BOLD);
                
        $gbarplot = new GroupBarPlot($bplot);
        $graph->Add($gbarplot);
        $graph->Stroke();
    }
  }  
}

?>