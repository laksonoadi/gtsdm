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
      $ObjDatPeg = new DataPegawai();
      $pegId = $ObjDatPeg->GetPegIdByUserName(); //$_GET['dataId']->Integer()->Raw();
      $rs = $ObjDatPeg->GetDatPegDetail2($pegId);
      $rs2 = $ObjDatPeg->GetDatPegDetail3($pegId);
      $rs3 = $ObjDatPeg->GetDatPegDetail4($pegId);
      $rs4 = $ObjDatPeg->GetDatPegDetail5($pegId);
      $rs5 = $ObjDatPeg->GetDatPegDetail6($pegId);
      $rs6 = $ObjDatPeg->GetDatPegDetail7($pegId);
      
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
      
      $this->Data = array('id'=>$rs['id'],'nip'=>$rs['nip'],'kodeint'=>$rs['kodeint'],'kodelain'=>$rs['kodelain'],
		'nama'=>$rs['nama'],'alamat'=>$rs['alamat'],'kodepos'=>$rs['kodepos'],'telp'=>$rs['telp'],
		'hp'=>$rs['hp'],'email'=>$rs['email'],'satwil'=>$rs['satwil'],'tmplahir'=>$rs['tmplahir'],
		'tgllahir'=>$rs['tgllahir'],'jenkel'=>$rs['jenkel'],'jnsidlain'=>$rs['jnsidlain'],'idlain'=>$rs['idlain'],
		'agama'=>$rs['agama'],'nikah'=>$rs['nikah'],'goldar'=>$rs['goldar'],'tinggibdn'=>$rs['tinggibdn'],
		'beratbdn'=>$rs['beratbdn'],'cacat'=>$rs['cacat'],'rambut'=>$rs['warnarmbt'],'wajah'=>$rs['bentukmuka'],'warna'=>$rs['warnakul'],'ciri'=>$rs['cirikhas'],'hobi'=>$rs['hobi'],'jnspegid'=>$rs['jnspegid'],
		'pnstmt'=>$rs['pnstmt'],'tglmasuk'=>$rs['tglmasuk'],'notaspen'=>$rs['notaspen'],'noaskes'=>$rs['noaskes'],
        'nonpwp'=>$rs['nonpwp'],'usiapens'=>$rs['usiapens'],'statr'=>$rs['statr'],'geldep'=>$rs['geldep'],'gelbel'=>$rs['gelbel'],
        'foto'=>$rs['foto'],'kodeabsen'=>$rs['kodeabsen'],'rekening'=>$rs['rekening'],'bank'=>$rs['bank_label'],'resipien'=>$rs['resipien'],
        'kodenikah'=>$rs2['kodenikah'],'katpeg'=>$rs2['katpeg'],'tippeg'=>$rs2['tippeg'],'nama1'=>$rs2['nama1'],'nama2'=>$rs2['nama2'],
		'pktgol'=>$rs3['pktgol'],'jabstruk'=>$rs4['jabstruk'],'jabfung'=>$rs5['jabfung'],'satker'=>$rs6['satker']);   
      
      $return = $this->Data;
      return $return;
   }
   
   function ParseTemplate($data = NULL) {  
      if($this->Pesan){
        $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
        $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
        $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
          
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('data_pegawai', 'inputDataPegawai', 'view', 'html').'&dataId='.$this->Data['id']); 
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
          $this->mrTemplate->AddVar('content', 'TITLE', 'EMPLOYEE DATA');
       }else{
          $this->mrTemplate->AddVar('content', 'TITLE', 'DATA PEGAWAI'); 
       }
       
      $this->mrTemplate->AddVar('content', 'ID', $this->Data['id']);
      $this->mrTemplate->AddVar('content', 'NIP', $this->Data['nip']);
      $this->mrTemplate->AddVar('content', 'KODEINT', $this->Data['kodeint']);
      $this->mrTemplate->AddVar('content', 'KODELAIN', $this->Data['kodelain']);
      $this->mrTemplate->AddVar('content', 'NAMA', $this->Data['nama']);
      
      if($this->Data['geldep']!=""){
        $this->mrTemplate->AddVar('content', 'GELDEP', ''.$this->Data['geldep'].', ');
      }else{
        $this->mrTemplate->AddVar('content', 'GELDEP', $this->Data['geldep']);
      }
	  
	  if($this->Data['gelbel']!=""){
        $this->mrTemplate->AddVar('content', 'GELDEP', ', '.$this->Data['gelbel']);
      }else{
        $this->mrTemplate->AddVar('content', 'GELDEP', $this->Data['gelbel']);
      }
      
      $this->mrTemplate->AddVar('content', 'TMPLAHIR', $this->Data['tmplahir']);
      
      if($this->Data['tgllahir']=="0000-00-00"){
        $this->Data['tgllahir']="";
      }else{
         if ($buttonlang=='eng'){
          $this->Data['tgllahir']=', '.$this->periode2stringEng($this->Data['tgllahir']);
         }else{
          $this->Data['tgllahir']=', '.$this->periode2string($this->Data['tgllahir']); 
         }
      }
      $this->mrTemplate->AddVar('content', 'TGLLAHIR', $this->Data['tgllahir']);
      $this->mrTemplate->AddVar('content', 'JNSIDLAIN', $this->Data['jnsidlain']);
      $this->mrTemplate->AddVar('content', 'IDLAIN', $this->Data['idlain']);
      
      if($this->Data['jenkel']=="L"){
        $this->Data['jenkel']="Laki-Laki";
      }elseif($this->Data['jenkel']=="P"){
        $this->Data['jenkel']="Perempuan";
      }else{
        $this->Data['jenkel']="";
      }
      
      $this->mrTemplate->AddVar('content', 'JENKEL', $this->Data['jenkel']);
      $this->mrTemplate->AddVar('content', 'AGAMA', $this->Data['agama']);
      $this->mrTemplate->AddVar('content', 'KEPER', $this->Data['keper']);
      $this->mrTemplate->AddVar('content', 'NIKAH', $this->Data['nikah']);
      
      $this->mrTemplate->AddVar('content', 'ALAMAT', $this->Data['alamat']);
      $this->mrTemplate->AddVar('content', 'KODEPOS', $this->Data['kodepos']);
      $this->mrTemplate->AddVar('content', 'TELP', $this->Data['telp']);
      $this->mrTemplate->AddVar('content', 'HP', $this->Data['hp']);
      $this->mrTemplate->AddVar('content', 'EMAIL', $this->Data['email']);
      
      $this->mrTemplate->AddVar('content', 'GOLDAR', $this->Data['goldar']);
      
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
      
	  $this->mrTemplate->AddVar('content', 'KATPEG', $this->Data['katpeg']);
      $this->mrTemplate->AddVar('content', 'TIPPEG', $this->Data['tippeg']);
      $this->mrTemplate->AddVar('content', 'WARNARAMBUT', $this->Data['rambut']);
      $this->mrTemplate->AddVar('content', 'WARNAKULIT', $this->Data['warna']);
	  $this->mrTemplate->AddVar('content', 'BENTUKMUKA', $this->Data['wajah']);
	  $this->mrTemplate->AddVar('content', 'CIRIKHAS', $this->Data['ciri']);
	  $this->mrTemplate->AddVar('content', 'CACAT', $this->Data['cacat']);
      $this->mrTemplate->AddVar('content', 'HOBI', $this->Data['hobi']);
      
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
      
      $this->mrTemplate->AddVar('content', 'NOTASPEN', $this->Data['notaspen']);
      $this->mrTemplate->AddVar('content', 'NOASKES', $this->Data['noaskes']);
      $this->mrTemplate->AddVar('content', 'NONPWP', $this->Data['nonpwp']);
      
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
      
      $this->mrTemplate->AddVar('content', 'SATWIL', $this->Data['satwil']);
      
      $this->mrTemplate->AddVar('content', 'FOTO', $this->Data['foto']);
      $this->mrTemplate->AddVar('content', 'FOTO2', $this->Data['foto']);
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$this->Data['foto']) | empty($this->Data['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}
	  	
	  	$this->mrTemplate->AddVar('content', 'KODENIKAH', $this->Data['kodenikah']);
	  	$this->mrTemplate->AddVar('content', 'PKTGOL', $this->Data['pktgol']);
	  	$this->mrTemplate->AddVar('content', 'JABSTRUK', $this->Data['jabstruk']);
	  	$this->mrTemplate->AddVar('content', 'JABFUNG', $this->Data['jabfung']);
	  	$this->mrTemplate->AddVar('content', 'SATKER', $this->Data['satker']);
	  	$this->mrTemplate->AddVar('content', 'DIRSPV', $this->Data['nama1']);
	  	$this->mrTemplate->AddVar('content', 'MOR', $this->Data['nama2']);
	  	$this->mrTemplate->AddVar('content', 'REKENING', $this->Data['rekening']);
		$this->mrTemplate->AddVar('content', 'RESIPIEN', $this->Data['resipien']);
	  	$this->mrTemplate->AddVar('content', 'BANK', $this->Data['bank']);
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