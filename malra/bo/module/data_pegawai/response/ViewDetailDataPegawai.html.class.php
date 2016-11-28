<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';

class ViewDetailDataPegawai extends HtmlResponse {
   var $Data;
   var $Pesan;
   var $Op;
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') .
         'module/data_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_detail_data_pegawai.html');
   }
   
   function ProcessRequest() {
      $pegId = $_GET['dataId']->Integer()->Raw();
      $ObjDatPeg = new DataPegawai();
      $rs = $ObjDatPeg->GetDatPegDetail2($pegId);
      $rs2 = $ObjDatPeg->GetDatPegDetail3($pegId);
      $rs3 = $ObjDatPeg->GetDatPegDetail4($pegId);
      $rs4 = $ObjDatPeg->GetDatPegDetail5($pegId);
      $rs5 = $ObjDatPeg->GetDatPegDetail6($pegId);
      $rs6 = $ObjDatPeg->GetDatPegDetail7($pegId);
      $rs7 = $ObjDatPeg->GetDatPegBahasa($pegId);
      
	  for($i = 0, $m = count($rs7), $bahasa = array(); $i < $m; ++$i)$bahasa[] = '- ' . $rs7[$i]['bahasa'];
	  $bahasa = implode('<br>', $bahasa);
	  
      $this->Data = array('id'=>$rs['id'],'nip'=>$rs['nip'],'kodegateaccess'=>$rs['kodegateaccess'],'kodeint'=>$rs['kodeint'],'kodelain'=>$rs['kodelain'],
		'nama'=>$rs['nama'],'alamat'=>$rs['alamat'],'kodepos'=>$rs['kodepos'],'telp'=>$rs['telp'],
		'hp'=>$rs['hp'],'email'=>$rs['email'],'satwil'=>$rs['satwil'],'tmplahir'=>$rs['tmplahir'],
		'tgllahir'=>$rs['tgllahir'],'jenkel'=>$rs['jenkel'],'jnsidlain'=>$rs['jnsidlain'],'idlain'=>$rs['idlain'],
		'agama'=>$rs['agama'],'nikah'=>$rs['nikah'],'goldar'=>$rs['goldar'],'tinggibdn'=>$rs['tinggibdn'],
		'beratbdn'=>$rs['beratbdn'],'cacat'=>$rs['cacat'],'hobi'=>$rs['hobi'],'jnspegid'=>$rs['jnspegid'],
		'pnstmt'=>$rs['pnstmt'],'cpnstmt'=>$rs['cpnstmt'],'tglmasuk'=>$rs['tglmasuk'],'notaspen'=>$rs['notaspen'],'noaskes'=>$rs['noaskes'],'statusnpwp'=>$rs['statusnpwp'],
        'nonpwp'=>$rs['nonpwp'],'tglnpwp'=>$rs['tglnpwp'],'usiapens'=>$rs['usiapens'],'statr'=>$rs['statr'],'geldep'=>$rs['geldep'], 'gelbel'=>$rs['gelbel'],
        'foto'=>$rs['foto'],'kodeabsen'=>$rs['kodeabsen'],'rekening'=>$rs['rekening'],'bank'=>$rs['bank_label'],'resipien'=>$rs['resipien'],'durasi'=>$rs['durasi'],'level'=>$rs['level'],
        'kodenikah'=>$rs2['kodenikah'],'katpeg'=>$rs2['katpeg'],'tippeg'=>$rs2['tippeg'],'nama1'=>$rs2['nama1'],'nama2'=>$rs2['nama2'],
		'pktgol'=>$rs3['pktgol'],'jabstruk'=>$rs4['jabstruk'],'jabfung'=>$rs5['jabfung'],'satker'=>$rs6['satker'],'stakepeg'=>$rs6['stakepeg']
		,'bahasa'=>$bahasa
		);  
	  // $this->Data += $rs; 
	  $this->Data = array_merge($rs, $this->Data);
      
      $return = $this->Data;
      

      return $return;
   }
   
   function ParseTemplate($data = NULL) {      

      // $dataPegawai[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('data_istri_suami','dataIstriSuami', 'view', 'html').'&dataId='. $idpeg;

    $this->mrTemplate->AddVar('content', 'URL_PSNG', Dispatcher::Instance()->GetUrl('data_istri_suami','dataIstriSuami', 'view', 'html').'&dataId='. $data['id']); 
    $this->mrTemplate->AddVar('content', 'URL_ANAK', Dispatcher::Instance()->GetUrl('data_anak','dataAnak', 'view', 'html').'&dataId='. $data['id']); 
    $this->mrTemplate->AddVar('content', 'URL_ORTU', Dispatcher::Instance()->GetUrl('data_orang_tua','dataOrangTua', 'view', 'html').'&dataId='. $data['id']); 
    $this->mrTemplate->AddVar('content', 'URL_MERTUA', Dispatcher::Instance()->GetUrl('data_mertua','dataMertua', 'view', 'html').'&dataId='. $data['id']); 
    $this->mrTemplate->AddVar('content', 'URL_SDR', Dispatcher::Instance()->GetUrl('data_saudara_kandung','dataSaudaraKandung', 'view', 'html').'&dataId='. $data['id']); 
    $this->mrTemplate->AddVar('content', 'URL_PGW', Dispatcher::Instance()->GetUrl('data_pegawai','detailDataPegawai', 'view', 'html').'&dataId='. $data['id']); 

      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('data_pegawai', 'dataPegawai', 'view', 'html')); 
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
          $this->mrTemplate->AddVar('content', 'TITLE', 'EMPLOYEE DATA');
       }else{
          $this->mrTemplate->AddVar('content', 'TITLE', 'DATA PEGAWAI'); 
       }
       
      $this->mrTemplate->AddVars('content', $this->Data);
      
      if($this->Data['tgllahir']=="0000-00-00"){
        $this->Data['tgllahir']="";
      }else{
         if ($buttonlang=='eng'){
          $this->Data['tgllahir']=', '.$this->periode2stringEng($this->Data['tgllahir']);
         }else{
          $this->Data['tgllahir']=', '.$this->periode2string($this->Data['tgllahir']); 
         }
      }
      
      if($this->Data['jenkel']=="L"){
        $this->Data['jenkel']="Laki-Laki";
      }elseif($this->Data['jenkel']=="P"){
        $this->Data['jenkel']="Perempuan";
      }else{
        $this->Data['jenkel']="";
      }
      
      if($this->Data['tinggibdn']==0){
        $this->Data['tinggibdn']="";
      }else{
        $this->Data['tinggibdn'].=' cm';
      }
      $this->mrTemplate->AddVar('content', 'TINGGIBDN', $this->Data['tinggibdn']);
      
      if($this->Data['beratbdn']==0){
        $this->Data['beratbdn']="";
      }else{
        $this->Data['beratbdn'].=' kg';
      }
      $this->mrTemplate->AddVar('content', 'BERATBDN', $this->Data['beratbdn']);
      
      if($this->Data['tglmasuk']=="0000-00-00"){
        $this->Data['tglmasuk']="";
      }else{
         if ($buttonlang=='eng'){
          $this->Data['tglmasuk']=$this->periode2stringEng($this->Data['tglmasuk']);
         }else{
          $this->Data['tglmasuk']=$this->periode2string($this->Data['tglmasuk']); 
         }
      }
      $this->mrTemplate->AddVar('content', 'TGLMASUK', $this->Data['tglmasuk']);
      
      if($this->Data['pnstmt']=="0000-00-00"){
        $this->Data['pnstmt']="";
      }else{
         if ($buttonlang=='eng'){
          $this->Data['pnstmt']=$this->periode2stringEng($this->Data['pnstmt']);
         }else{
          $this->Data['pnstmt']=$this->periode2string($this->Data['pnstmt']); 
         }
      }
      $this->mrTemplate->AddVar('content', 'PNSTMT', $this->Data['pnstmt']);
      
      if($this->Data['cpnstmt']=="0000-00-00"){
        $this->Data['cpnstmt']="";
      }else{
         if ($buttonlang=='eng'){
          $this->Data['cpnstmt']=$this->periode2stringEng($this->Data['cpnstmt']);
         }else{
          $this->Data['cpnstmt']=$this->periode2string($this->Data['cpnstmt']); 
         }
      }
      $this->mrTemplate->AddVar('content', 'CPNSTMT', $this->Data['cpnstmt']);
      
      $this->mrTemplate->AddVar('content', 'PNSCPNS', $this->Data['pnscpns']);
      $this->mrTemplate->AddVar('content', 'JNSPEGID', $this->Data['jnspegid']);
      $this->mrTemplate->AddVar('content', 'STATR', $this->Data['statr']);
      $this->mrTemplate->AddVar('content', 'STAKEPEG', $this->Data['stakepeg']);
      
      if ($buttonlang=='eng'){
        if($this->Data['statusnpwp']=='Ya'){ $this->Data['statusnpwp'] = 'Yes'; }
        if($this->Data['statusnpwp']=='Tidak'){ $this->Data['statusnpwp'] = 'No'; }
      }
      
      if($this->Data['usiapens']==0){
        $this->Data['usiapens']="";
      }else{
        $this->Data['usiapens'].=' tahun';
      }
      $this->mrTemplate->AddVar('content', 'USIAPENS', $this->Data['usiapens']);
      
      if($this->Data['kodeabsen']==0){
        $this->Data['kodeabsen']="";
      }
      $this->mrTemplate->AddVar('content', 'KODEABSEN', $this->Data['kodeabsen']);
      
      $this->mrTemplate->AddVar('content', 'FOTO', $this->Data['foto']);
      $this->mrTemplate->AddVar('content', 'FOTO2', $this->Data['foto']);
      // print_r(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$this->Data['foto']);
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$this->Data['foto']) || empty($this->Data['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}
	  	
	  	$this->mrTemplate->AddVar('content', 'DIRSPV', $this->Data['nama1']);
	  	$this->mrTemplate->AddVar('content', 'MOR', $this->Data['nama2']);
	  	
	  	$date1 = date("Ymd");
      $date2 = $this->Data['tglmasuk'];
      
      $diff = abs(strtotime($date2) - strtotime($date1));
      
      $years = floor($diff / (365*60*60*24));
      $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
      $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
      
      $this->mrTemplate->AddVar('content', 'LAMA_KERJA', $years . ' <i>years</i> ' . $months . ' <i>months</i> ' . $days . ' <i>days</i> ');

      $this->mrTemplate->AddVar('content', 'PATH_AKTA', GTFWConfiguration::GetValue('application', 'akta_pegawai_path'));
   }
   
   


   function periode2string($date) {
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
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return (int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
	}
	
	function periode2stringEng($date) {
	   $bln = array(
	        1  => 'January',
					2  => 'February',
					3  => 'March',
					4  => 'April',
					5  => 'May',
					6  => 'June',
					7  => 'July',
					8  => 'August',
					9  => 'September',
					10 => 'October',
					11 => 'November',
					12 => 'December'					
	               );
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return (int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
	}
}

?>