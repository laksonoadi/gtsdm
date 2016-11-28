<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/business/AppPayrollPegawai.class.php';  
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_gaji/business/AppDetilGaji.class.php';

class ViewPayrollPegawai extends HtmlResponse {

  var $Pesan;
  
  function TemplateModule() {
    $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/gaji_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
    $this->SetTemplateFile('view_payroll_pegawai.html');
  }
  
  function ProcessRequest() {
	if(isset($_GET['periode_bulan'])){  
      $periode_mon = $_GET['periode_bulan'];
    } else{
      $periode_mon = date('m');
    }
    if(isset($_GET['periode_tahun'])){  
      $periode_year = $_GET['periode_tahun'];
    } else{
      $periode_year = date('Y');
    }
	
	$this->periode_mon = $periode_mon;
	$this->periode_year = $periode_year;
	
    //Menginputkan komponen otomatis
	$ObjKomponen = new AppDetilGaji();
	$ObjKomponen->GetKomponenGajiOtomatis($_GET['dataId'],$periode_year.'-'.$periode_mon);
	
    $Obj = new AppPayrollPegawai(); 
    $arrResult =  $Obj->GetBidataPegawaiById($_GET['dataId']);

    $return['biodata'] = $arrResult['0'];
	$return['biodata']['sudah'] = $Obj->CekGaji($return['biodata']['nip'],$periode_year.'-'.$periode_mon.'-01');
	
    $j=0;
    for($i=0;$i<count($arrResult);$i++){
      if(!empty($arrResult[$i]['nama_formula']))
      $komponen[$j]['nama_formula'] = $arrResult[$i]['nama_formula'];
      $komponen[$j]['id_formula'] = $arrResult[$i]['id_formula'];
      $komponen[$j]['nominal'] = $arrResult[$i]['nominal'];
      $komponen[$j]['is_manual'] = $arrResult[$i]['isManual'];
      $j++;
    }
	
	$temp=$Obj->GetJumlahHari($periode_year.'-'.$periode_mon,$_GET['dataId']);
	
    $this->jumlahhari = $temp[0];
	$this->absen = $temp[0]-$temp[1];
	$this->kategori1 = $temp[2];
	$this->kategori2 = $temp[3];
	$this->kategori0 = $temp[4];
	$this->kategori3 = $temp[5];
	
    $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    if ($lang=='eng'){
      $bulan = $Obj->GetBulanEng();
    }else{
      $bulan = $Obj->GetBulan();
    }
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_bulan', 
    array('periode_bulan', $bulan, $periode_mon, 'none', 'onChange="this.form.update_form()"'),Messenger::CurrentRequest);
    
    $year = $Obj->GetTahun();
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_tahun', 
    array('periode_tahun', $year, $periode_year, 'none', 'onChange="this.form.update_form()"'),Messenger::CurrentRequest);
    
    $idPeg = Dispatcher::Instance()->Decrypt((string)$_GET['dataId']);
    
    $dataPendapatan = $Obj->GetDataPendapatan($idPeg,$periode_mon,$periode_year);   
    $return['dataPendapatan'] = $dataPendapatan;
    
    $dataPendapatanTotal = $Obj->GetDataPendapatanTotal($idPeg,$periode_mon,$periode_year);   
    $return['dataPendapatanTotal'] = $dataPendapatanTotal[0];
    
    $dataTunjanganThr = $Obj->GetDataTunjanganThr($idPeg,$periode_mon,$periode_year);
    $return['dataTunjanganThr'] = $dataTunjanganThr['0'];
    
    $dataPotonganGaji = $Obj->GetDataPotonganGaji($idPeg,$periode_mon,$periode_year);
    $return['dataPotonganGaji'] = $dataPotonganGaji;
    
    $dataPotonganGajiTotal = $Obj->GetDataPotonganGajiTotal($idPeg,$periode_mon,$periode_year);
    $return['dataPotonganGajiTotal'] = $dataPotonganGajiTotal[0];
    
    $dataTunjanganPph = $Obj->GetDataTunjanganPph($idPeg,$periode_mon,$periode_year);
    $return['dataTunjanganPph'] = $dataTunjanganPph['0'];
    
    //pembedaan gapok (swasta/negeri)
    $setGaji=GTFWConfiguration::GetValue('application', 'set_gaji');
    if ($setGaji=='swasta'){
      $dataGapok = $Obj->GetGapokSwasta($idPeg);

    }else{
      $dataGapok = $Obj->GetGapokNegeri($idPeg);
    }
    $return['dataGapok'] = $dataGapok[0];
    //sampai sini

    $msg = Messenger::Instance()->Receive(__FILE__);
    $this->Pesan = $msg[0][1];
    $this->css = $msg[0][2];
    
    $return['idPegawai'] = $idPeg;
    $return['komponen'] = $komponen;
    
    return $return;
  }
  
  function ParseTemplate($data = NULL) {
    $this->mrTemplate->AddVars('content',$data['biodata'],'BIO_');
    $this->mrTemplate->AddVar('content','URL_ACTION',Dispatcher::Instance()->GetUrl('gaji_pegawai', 'payrollPegawai', 'do', 'html'));
    $this->mrTemplate->AddVar('content','URL_LIST',Dispatcher::Instance()->GetUrl('gaji_pegawai', 'gajiPegawai', 'view', 'html').'&periode_bulan='.$this->periode_mon.'&periode_tahun='.$this->periode_year.'&cari=1&nip_nama=');
    $this->mrTemplate->AddVar('content','URL_UPDATE',Dispatcher::Instance()->GetUrl('gaji_pegawai', 'payrollPegawai', 'view', 'html').'&dataId='.$data['idPegawai']);
    $this->mrTemplate->AddVar('content','ID_PEGAWAI',$data['idPegawai']);
	$this->mrTemplate->AddVar('content','PERIODE_MON',$this->periode_mon);
	$this->mrTemplate->AddVar('content','PERIODE_YEAR',$this->periode_year);
	
	if ($data['biodata']['sudah']==1){
		$this->mrTemplate->AddVar('content','TYPE_BUTTON','button');
		$this->mrTemplate->AddVar('content','LABEL_BUTTON','Gaji Bulan Ini Sudah Dibayar');
	}else{
		$this->mrTemplate->AddVar('content','TYPE_BUTTON','submit');
		$this->mrTemplate->AddVar('content','LABEL_BUTTON','Simpan Daftar Gaji');
	}
    
    //hitung gaji pokok berdasarkan config
    $no=1;
    $pok = $data['dataGapok'];
    if(empty($pok['gapok'])){
    $this->mrTemplate->AddVar('content','GAPOK2','');
    }else{
    $this->mrTemplate->AddVar('content','GAPOK2',(int)$pok['gapok']);
    }
    $gapok1=(int)$pok['gapok'];

    $pok['gapok'] = number_format($pok['gapok'], 2, ',', '.');
    $this->mrTemplate->AddVar('content','GAPOK',$pok['gapok']);
    $this->mrTemplate->AddVar('content','NO2',$no);
    $this->mrTemplate->AddVar('content','CLASS2','table-common-even');
    //sampe sini

    $this->mrTemplate->AddVar('content','PENDAPATAN_LAIN_TOTAL',(int)$data['dataPendapatanTotal']['nominal']);
    $this->mrTemplate->AddVar('content','POTONGAN_GAJI_TOTAL',(int)$data['dataPotonganGajiTotal']['nominal']);
    
    $this->mrTemplate->AddVar('content','GAPOK',$pok['gapok']);
    
    if (empty($data['komponen'])) {
      $this->mrTemplate->AddVar('data_grid', 'IS_DATA_EMPTY', 'YES');
    } else {
      $this->mrTemplate->AddVar('data_grid', 'IS_DATA_EMPTY', 'NO');
      $komponen = $data['komponen'];
      $no = 1;
      $total=0;
      for($i=0;$i<count($komponen);$i++){
        if ($no % 2 != 0) $komponen[$i]['class_name'] = 'table-common-even';
        else $komponen[$i]['class_name'] = '';
        
        $komponen[$i]['number'] = $i+1;
        
        //if($komponen[$i]['nominal']==0.00){
        $hitungGaji=$this->HitungGaji($komponen[$i]['id_formula'],$data['biodata']['nip'],$gapok1);
        //print_r($komponen[$i]['id_formula'].' ');
        //print_r($hitungGaji);
        $komponen[$i]['ori'] = $hitungGaji['ori'];
        $komponen[$i]['nominal'] = $hitungGaji['nominal'];
		$komponen[$i]['is_manual'] = $hitungGaji['is_manual'];
        /*if($komponen[$i]['is_manual']==""){
        $komponen[$i]['is_manual'] = "Tidak";
        }*/
		
		if (!is_integer(strpos($komponen[$i]['nama_formula'],"Tunjangan Lain"))){
			$total += $komponen[$i]['ori'];
			$hide[] = $komponen[$i]['id_formula'];
		}
      
        /*}else{
        $komponen[$i]['ori'] = $komponen[$i]['nominal'];
        $komponen[$i]['nominal'] = number_format($komponen[$i]['nominal'], 2, ',', '.');
        }*/
        $this->mrTemplate->AddVars('payroll_item',$komponen[$i],'PAYROLL_');
        $this->mrTemplate->parseTemplate('payroll_item','a');
        $no++;
      }
	  $this->mrTemplate->AddVar('content','TOTAL',$total);
      $hide = implode('|',$hide);
      $this->mrTemplate->AddVar('content','HIDE_KOMPONEN',$hide);              
    }
    
    //hitung pendapatan lain otomatis
    $lain = $data['dataPendapatan'];
    
    if (empty($data['dataPendapatan'])) {
      $this->mrTemplate->AddVar('data_pendapatan', 'IS_DATA_EMPTY', 'YES');
    } else {
      $this->mrTemplate->AddVar('data_pendapatan', 'IS_DATA_EMPTY', 'NO');
      for($i=0;$i<count($lain);$i++){       
        if ($no % 2 != 0) $lain[$i]['class_name'] = 'table-common-even';
        else $lain[$i]['class_name'] = '';
        
        $lain[$i]['no3'] = $no;
        if(empty($lain[$i]['nominal'])){
          $this->mrTemplate->AddVar('pendapatan_item','DAPAT_LAIN2','');
        }else{
          $this->mrTemplate->AddVar('pendapatan_item','DAPAT_LAIN2',(int)$lain[$i]['nominal']);
        }
        
        $lain[$i]['nominal'] = number_format($lain[$i]['nominal'], 2, ',', '.');
        $this->mrTemplate->AddVars('pendapatan_item',$lain[$i],'DAPAT_');
        $this->mrTemplate->AddVar('pendapatan_item','DAPAT_LAIN',$lain[$i]['nominal']);
        $this->mrTemplate->parseTemplate('pendapatan_item','a');
        $no++;
      }
    }
 
    //sampe sini
    
    //hitung tunjangan thr otomatis
    $thr = $data['dataTunjanganThr'];

    if (empty($data['dataTunjanganThr']['nominal'])) {
      $this->mrTemplate->AddVar('data_thr', 'IS_DATA_EMPTY', 'YES');
    } else {
      $this->mrTemplate->AddVar('data_thr', 'IS_DATA_EMPTY', 'NO');
      if(empty($thr['nominal'])){
        $this->mrTemplate->AddVar('thr_item','TUNJ_THR2','');
      }else{
        $this->mrTemplate->AddVar('thr_item','TUNJ_THR2',(int)$thr['nominal']);
      }
      $thr['nominal'] = number_format($thr['nominal'], 2, ',', '.');
      $this->mrTemplate->AddVar('thr_item','TUNJ_THR',$thr['nominal']);
      $this->mrTemplate->AddVar('thr_item','TUNJ_NO4',$no);
      if ($no % 2 != 0){
        $this->mrTemplate->AddVar('thr_item','TUNJ_CLASS_NAME','table-common-even');
      }else{
        $this->mrTemplate->AddVar('thr_item','TUNJ_CLASS_NAME','');
      }
    } 
    //sampe sini
    
    //hitung potongan gaji otomatis
    $pot = $data['dataPotonganGaji'];

    if (empty($data['dataPotonganGaji'])) {
      $this->mrTemplate->AddVar('data_potongan', 'IS_DATA_EMPTY', 'YES');
    } else {
      $this->mrTemplate->AddVar('data_potongan', 'IS_DATA_EMPTY', 'NO');
      for($i=0;$i<count($pot);$i++){
        if ($no % 2 != 0) $pot[$i]['class_name'] = 'table-common-even';
        else $pot[$i]['class_name'] = '';
        
        $pot[$i]['no5'] = $no;
        if(empty($pot[$i]['nominal'])){
          $this->mrTemplate->AddVar('potongan_item','POT_GAJI2','');
        }else{
          $this->mrTemplate->AddVar('potongan_item','POT_GAJI2',(int)$pot[$i]['nominal']);
        }
        $pot[$i]['nominal'] = number_format($pot[$i]['nominal'], 2, ',', '.');
        $this->mrTemplate->AddVars('potongan_item',$pot[$i],'POT_');
        $this->mrTemplate->AddVar('potongan_item','POT_GAJI',$pot[$i]['nominal']);
        $this->mrTemplate->parseTemplate('potongan_item','a');
        
        $no++;
      }
     } 
    //sampe sini
    
    //hitung tunjangan thr otomatis
    $pph = $data['dataTunjanganPph'];
    if (empty($data['dataTunjanganPph']['nominal'])) {
      $this->mrTemplate->AddVar('data_pph', 'IS_DATA_EMPTY', 'YES');
    } else {
      $this->mrTemplate->AddVar('data_pph', 'IS_DATA_EMPTY', 'NO');
      if(empty($pph['nominal'])){
        $this->mrTemplate->AddVar('pph_item','TUNJ_PPH2','');
      }else{
        $this->mrTemplate->AddVar('pph_item','TUNJ_PPH2',(int)$pph['nominal']);
      }
      $pph['nominal'] = number_format($pph['nominal'], 2, ',', '.');
      $this->mrTemplate->AddVar('pph_item','TUNJ_PPH',$pph['nominal']);
      $this->mrTemplate->AddVar('pph_item','TUNJ_NO6',$no);
      if ($no % 2 != 0){
        $this->mrTemplate->AddVar('pph_item','TUNJ_CLASS_NAME','table-common-even');
      }else{
        $this->mrTemplate->AddVar('pph_item','TUNJ_CLASS_NAME','');
      }
    } 
    //sampe sini
  }
  
  function HitungGaji($id,$nip,$gapok){
    $obj = new AppPayrollPegawai();    
    $komponenGajiPeg = $obj->GetKomponenGajiPegawai($nip);
	
	$jumlahhari = $this->jumlahhari;
	$absen = $this->absen;
	$variabel= $obj->GetVariabelPegawai($nip);
	$jenis = $variabel['jenis'];
	$jabatan = $variabel['jabatan_label'];
	$semester = $variabel['semester'];
	$studi = $variabel['studi'];
	$jenjang = $variabel['jenjang'];
	$periode = $this->periode_year.'-'.$this->periode_mon.'-01';
	$dosen = $variabel['dosen'];
	
    $formula = $obj->GetFormulaGaji($id,$nip);
	$cekformula = $obj->CekFormulaGaji($id,$nip,$periode);
	if ($cekformula['manual']=='Ya') $formula=$cekformula['nominal'];
	
    for($i=0;$i<count($komponenGajiPeg);$i++){
      $kode[$i] = '['.$komponenGajiPeg[$i]['kompgajiKode'].']';
      if($komponenGajiPeg[$i]['id']==1){
         $value[$i] = $gapok;
      }else{
         $value[$i] = $komponenGajiPeg[$i]['nominal'];
      }
    }

	$kode[]='[JUMLAHHARI]'; $value[]=$jumlahhari;
	$kode[]='[ABSEN]'; $value[]=$absen;
	$kode[]='[KATEGORI0]'; $value[]=$this->kategori0;
	$kode[]='[KATEGORI1]'; $value[]=$this->kategori1;
	$kode[]='[KATEGORI2]'; $value[]=$this->kategori2;
	$kode[]=$jabatan=='DOSEN'?'[ISDOSEN]':'[ISDOSEN]'; $value[]=$jabatan=='DOSEN'?1:0;
	if ($dosen=='DOSEN'){
		$kode[]='[DOSEN]'; $value[]=1;
		$kode[]='[KARYAWAN]'; $value[]=0;
	}else{
		$kode[]='[DOSEN]'; $value[]=0;
		$kode[]='[KARYAWAN]'; $value[]=1;
	}
	
    $newSql = str_replace($kode,$value,$formula);
    
    ob_start(); 
	if (is_integer(strpos($newSql,'$value'))){
		eval("$newSql");
	}else{
		eval("\$value=$newSql;");
	}
    $ret = ob_get_contents();
    ob_end_clean();
	
    if (preg_match("/Parse error/",$ret)) $value=0;
	
	if (is_array($value)) $value=0;
	
    $return['id_formula'] = $id;
    $return['nominal'] = number_format($value, 2, ',', '.');
	$return['is_manual']=empty($cekformula['manual'])?'Tidak':$cekformula['manual'];
    $return['ori'] = $value;
    
    return $return;
  }
}
?>
