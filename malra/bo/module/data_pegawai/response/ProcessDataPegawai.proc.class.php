<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_pegawai/business/data_pegawai.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_pegawai/business/data_pegawai_gtakademik.class.php';

class Process
{
   var $POST;
   //var $FILES;
   var $user;
   var $Obj;
   var $cssAlert = "notebox-alert";
   var $cssDone = "notebox-done";
   var $cssFail = "notebox-warning";
   var $pageInput;
   var $decId;
   var $pageView;
   var $detail;
   
   function __construct($ret) {
    $this->StatusIntegrasiAkademik=GTFWConfiguration::GetValue( 'application', 'status_integrasi_gtakademik');
	if ($this->StatusIntegrasiAkademik){
		$nomorkoneksi=GTFWConfiguration::GetValue( 'application', 'nomor_koneksi_gtakademik');
		$this->ObjGtakademik = new DataPegawaiGtakademik($nomorkoneksi);
	}
	
    $this->Obj = new DataPegawai();
	  $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
    if($ret == "html"){
      $this->pageInput = Dispatcher::Instance()->GetUrl('data_pegawai', 'inputDataPegawai', 'view', 'html');
      $this->pageView = Dispatcher::Instance()->GetUrl('data_pegawai', 'dataPegawai', 'view', 'html');
    }else{
      $this->pageInput = Dispatcher::Instance()->GetUrl('data_pegawai', 'inputDataPegawai', 'view', 'html',true);
      $this->pageView = Dispatcher::Instance()->GetUrl('data_pegawai', 'dataPegawai', 'view', 'html',true);
    }
    $this->decId = $_POST['pegId']->Integer()->Raw();
    $this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
	   if ($this->lang=='eng'){
       $this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';$this->msgAddFail2="Data addition failed, existing employee's number in the database";
       $this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       $this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
       $this->msgReqDataEmpty='All field marked with * must be filled';
       $this->msgReqPktEmpty='Select the grade first';
     }else{
       $this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';$this->msgAddFail2="Penambahan data gagal dilakukan, NIP sudah ada di database";
       $this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       $this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
       $this->msgReqDataEmpty='Semua data bertanda * harus diisi';
       $this->msgReqPktEmpty='Pilihlah pangkat golongannya';
     }
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray();
      //$this->FILES = $param2->AsArray();
  }
  
  function Check (){
    if (isset($this->POST['btnbatal'])) return $this->pageView;
    if ((trim($this->POST['datpegNip']) == '') or (trim($this->POST['datpegNama']) == '')){
      $error = $this->msgReqDataEmpty;
    }elseif ((($this->POST['jabstruk'] != '') or ($this->POST['jabfung'] != '')) and ($this->POST['pktgol'] == '') and ($this->POST['idgol'] == '')){
      $error = $this->msgReqPktEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('data_pegawai', 'inputDataPegawai', 'view', 'html', $msg, Messenger::NextRequest);
      
      $return = $this->pageInput;
      if ($this->POST['op']=="edit"){ 
      $return .= "&dataId=".$this->decId;
      }
      return $return;
    }
    return true;
  }
  
  function CleanFilename($name) {
      if(!is_string($name)) {
          $name = (string) $name;
      }
      
      // Cannot start or end with a . or space
      $name = trim($name);
      $name = trim($name, '.');
      
      // Replace invalid characters with _
      $invalid = '/[^A-Za-z0-9-_.]/';
      $name = preg_replace($invalid, '_', $name);
      return $name;
  }
  
  function GetDataId(){  
    $result = $this->Obj->GetDataId($this->POST['datpegNip']);
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
  
  function AddDatpeg($nama_file, $file_akta){
    
    $a=$this->POST['datpegTglLahir_year'].'-'.$this->POST['datpegTglLahir_mon'].'-'.$this->POST['datpegTglLahir_day'];
    // $b=$this->POST['datpegTglMasuk_year'].'-'.$this->POST['datpegTglMasuk_mon'].'-'.$this->POST['datpegTglMasuk_day'];
    $c=$this->POST['datpegPnsTmt_year'].'-'.$this->POST['datpegPnsTmt_mon'].'-'.$this->POST['datpegPnsTmt_day'];
	$d=$this->POST['datpegCpnsTmt_year'].'-'.$this->POST['datpegCpnsTmt_mon'].'-'.$this->POST['datpegCpnsTmt_day'];
	// $e=$this->POST['datpegTglKir_year'].'-'.$this->POST['datpegTglKir_mon'].'-'.$this->POST['datpegTglKir_day'];
	$f=$this->POST['datpegTglNpwp_year'].'-'.$this->POST['datpegTglNpwp_mon'].'-'.$this->POST['datpegTglNpwp_day'];
	// $g=$this->POST['datpegTglSkbn_year'].'-'.$this->POST['datpegTglSkbn_mon'].'-'.$this->POST['datpegTglSkbn_day'];
	// $h=$this->POST['datpegTglSkkb_year'].'-'.$this->POST['datpegTglSkkb_mon'].'-'.$this->POST['datpegTglSkkb_day'];
    if($this->POST['datpegCpnsTmt_year'] != '0000' && $this->POST['datpegCpnsTmt_mon'] != '00' && $this->POST['datpegCpnsTmt_day'] != '00') {
        $b = $d;
    } elseif($this->POST['datpegPnsTmt_year'] != '0000' && $this->POST['datpegPnsTmt_mon'] != '00' && $this->POST['datpegPnsTmt_day'] != '00') {
        $b = $c;
    } else {
        $b = '0000-00-00';
    }
    
    if($this->POST['pegSatwilId']==""){
      $s=NULL;
    }else{
	  $s=$this->POST['pegSatwilId'];
	}
    if($this->POST['pegAsalSatwilId']==""){
      $as=NULL;
    }else{
	  $as=$this->POST['pegAsalSatwilId'];
	}
	if($this->POST['pegAgamaId']==""){
      $ag=NULL;
    }else{
	  $ag=$this->POST['pegAgamaId'];
	}
	if($this->POST['pegStatnikahId']==""){
      $stn=NULL;
    }else{
	  $stn=$this->POST['pegStatnikahId'];
	}
	if($this->POST['pegGoldrhId']==""){
      $gol=NULL;
    }else{
	  $gol=$this->POST['pegGoldrhId'];
	}
    if($this->POST['pegJnspegrId']==""){
      $jnp=NULL;
    }else{
    $jnp=$this->POST['pegJnspegrId'];
  }
  if($this->POST['pegStatrId']==""){
      $stp=NULL;
    }else{
    $stp=$this->POST['pegStatrId'];
  }
	if($this->POST['pegLevelId']==""){
      $lvl=NULL;
    }else{
	  $lvl=$this->POST['pegLevelId'];
	}
	
	if($this->POST['datpegTinggiBdn']==""){
      $tgb=0;
    }else{
	  $tgb=$this->POST['datpegTinggiBdn'];
	}
	if($this->POST['datpegBeratBdn']==""){
      $brb=0;
    }else{
	  $brb=$this->POST['datpegBeratBdn'];
	}
	/* if($this->POST['datpegUsiaPens']==""){
      $usp=0;
    }else{
	  $usp=$this->POST['datpegUsiaPens'];
	} */
    $usp = NULL;
	if($this->POST['datpegKodeAbsen']==""){
      $kda=0;
    }else{
	  $kda=$this->POST['datpegKodeAbsen'];
	}
	/* if($this->POST['PegJenFungsional']==""){
      $pjf=NULL;
    }else{
    $pjf=$this->POST['PegJenFungsional'];
    } */
    $pjf = NULL;
    
    $array=array('nip'=>$this->POST['datpegNip'],'kodeGateAccess'=>$rs['datpegKodeGateAccess'], 'kodeInter'=>$this->POST['datpegKodeInter'],'kodeLain'=>$this->POST['datpegKodeLain'],
      'nama'=>$this->POST['datpegNama'],'gelDep'=>$this->POST['datpegGelDep'],'gelBel'=>$this->POST['datpegGelBel'],'tmpLahir'=>$this->POST['datpegTmpLahir'],'tglLahir'=>$a,'noAkta'=>$this->POST['datpegNoAkta'],'akta'=>$file_akta,
	  'idLain'=>$this->POST['datpegIdLain'],
	  'idSkck'=>/* $this->POST['datpegSKCK'] */NULL,'jenKel'=>$this->POST['pegJenkel'],'agama'=>$ag,'statNikah'=>$stn,
	  'alamat'=>$this->POST['datpegAlamat'],'kodePos'=>$this->POST['datpegKodePos'],'noTelp'=>$this->POST['datpegNoTelp'],'noHp'=>$this->POST['datpegNoHp'],
	  'email'=>$this->POST['datpegEmail'],
	  // 'kir'=>$this->POST['datpegNoKir'],'tglkir'=>$e,'noskbn'=>$this->POST['datpegNoSkbn'],'tglskbn'=>$g,'noskkb'=>$this->POST['datpegNoSkkb'],'tglskkb'=>$h,
	  'kir'=>NULL,'tglkir'=>NULL,'noskbn'=>NULL,'tglskbn'=>NULL,'noskkb'=>NULL,'tglskkb'=>NULL,
      'golDar'=>$gol,'tinggiBdn'=>$tgb,'beratBdn'=>$brb,
	  'cacat'=>$this->POST['datpegCacat'],'hobi'=>$this->POST['datpegHobi'],'tglmasuk'=>$b,'pnstmt'=>$c,'cpnstmt'=>$d,
	  'notaspen'=>$this->POST['datpegNoTaspen'],'noaskes'=>$this->POST['datpegNoAskes'],'statusnpwp'=>$this->POST['statusnpwp'],'nonpwp'=>$this->POST['datpegNoNpwp'],'tglnpwp'=>$f,'usiapens'=>$usp,
	  'kodeabsen'=>$kda,'jnsidlain'=>$this->POST['datpegJenIdLain'],'jnspeg'=>$jnp,'statpeg'=>$stp,
      'satwilpeg'=>$s,'datpegAsalDesa'=>$this->POST['datpegAsalDesa'],'datpegAsalKecamatan'=>$this->POST['datpegAsalKecamatan'],'asalsatwil'=>$as,
      'pegLevelId'=>$lvl,'pegStatusWargaNeg'=>$this->POST['pegStatusWargaNeg'], 'foto'=>$nama_file
	  ,'kelurahan'	=> $this->POST['datpegKelurahan']
	  ,'kecamatan'	=> $this->POST['datpegKecamatan']
	  ,'kepemilikanRumah'	=> $this->POST['datpegKepemilikanRumah']
	  ,'pegNoKarpeg'	=> $this->POST['pegNoKarpeg']
	  ,'pegNoKpe'	=> $this->POST['pegNoKpe']
    ,'PegJenFungsional' => $pjf
	  ,'userId'=>$this->user);

    $result = $this->Obj->Add($array);
	if ($result) {
	    if ($this->StatusIntegrasiAkademik){
			$input_gtakademik=$this->ObjGtakademik->AddPegawai($array,$this->POST['pegKatPeg']);
		}
	}
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
  
  function AddDatGajipeg($id,$op){
    $c=$this->POST['datpegPnsTmt_year'].'-'.$this->POST['datpegPnsTmt_mon'].'-'.$this->POST['datpegPnsTmt_day'];
    
    $array=array('jnspeg'=>$this->POST['pegJnspegrId'],'mulai'=>$c);
    if(($op=="input") or (empty($this->POST['idgol']))){
      $aa=$this->POST['pktgol'];
      $p="in";
    }else{
      $aa=$this->POST['idgol'];
      $p="up";
    }
    
    $cekKode = $this->Obj->GetKodeNikah($id);
    
    if(empty($cekKode)){
      $p2="in";
    }else{
      $p2="up";
    }
    
    if(!empty($this->POST['jabstruk'])){
      $idStruk = $this->Obj->GetIdStruk($this->POST['jabstruk']);
    }
    if(!empty($this->POST['jabfung'])){
      $idFung = $this->Obj->GetIdFung($this->POST['jabfung']);
    }
    $dateNow=date('Y').date('m').date('d');
	
	 if($this->POST['kodenik']==""){
      $kdn=1;
    }else{
	   $kdn=$this->POST['kodenik'];
	 }
	
 	 if($this->POST['bank']==""){
      $this->POST['bank']=NULL;
    }
    
    $result = $this->Obj->AddDatGaji($id,$kdn,$this->POST['satker'],$aa,$this->POST['jabstruk'],$this->POST['jabfung'],$array,$op,$p,$p2,$idStruk,$idFung,$dateNow,$this->POST['pegKatPeg'],$this->POST['pegTipPeg'],$this->POST['pegId1'],$this->POST['pegId2'],$this->POST['bank'],$this->POST['rekening'],$this->POST['resipien'],$this->user);
    //print_r($aa);
    
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
  
  function AddDataRekening($id,$op){
		if ($this->POST['bank']!=''){
			$bank=$this->POST['bank'];
			$rekening=$this->POST['rekening'];
			$penerima=$this->POST['resipien'];
			$result=$this->Obj->AddDataRekening($id,$op,$bank,$rekening,$penerima);
			
			return $result;
		}else{
			return true;
		}
  }
	
  function AddDataBahasa($id,$op){
		if (!empty($this->POST['pegdatBahasa'])){
			$result=$this->Obj->AddDataBahasa($id,$this->POST['pegdatBahasa']);
			
			return $result;
		}else{
			return true;
		}
  }
	
	function UpdateDatpeg($nama_file, $file_akta){ 
     
	$a=$this->POST['datpegTglLahir_year'].'-'.$this->POST['datpegTglLahir_mon'].'-'.$this->POST['datpegTglLahir_day'];
    // $b=$this->POST['datpegTglMasuk_year'].'-'.$this->POST['datpegTglMasuk_mon'].'-'.$this->POST['datpegTglMasuk_day'];
    $c=$this->POST['datpegPnsTmt_year'].'-'.$this->POST['datpegPnsTmt_mon'].'-'.$this->POST['datpegPnsTmt_day'];
	$d=$this->POST['datpegCpnsTmt_year'].'-'.$this->POST['datpegCpnsTmt_mon'].'-'.$this->POST['datpegCpnsTmt_day'];
	// $e=$this->POST['datpegTglKir_year'].'-'.$this->POST['datpegTglKir_mon'].'-'.$this->POST['datpegTglKir_day'];
	$f=$this->POST['datpegTglNpwp_year'].'-'.$this->POST['datpegTglNpwp_mon'].'-'.$this->POST['datpegTglNpwp_day'];
	// $g=$this->POST['datpegTglSkbn_year'].'-'.$this->POST['datpegTglSkbn_mon'].'-'.$this->POST['datpegTglSkbn_day'];
	// $h=$this->POST['datpegTglSkkb_year'].'-'.$this->POST['datpegTglSkkb_mon'].'-'.$this->POST['datpegTglSkkb_day'];
    if($this->POST['datpegCpnsTmt_year'] != '0000' && $this->POST['datpegCpnsTmt_mon'] != '00' && $this->POST['datpegCpnsTmt_day'] != '00') {
        $b = $d;
    } elseif($this->POST['datpegPnsTmt_year'] != '0000' && $this->POST['datpegPnsTmt_mon'] != '00' && $this->POST['datpegPnsTmt_day'] != '00') {
        $b = $c;
    } else {
        $b = '0000-00-00';
    }
    
    $detail = $this->detail;
    
    if($this->POST['pegAsalSatwilId']==""){
      $as=NULL;
    }else{
	  $as=$this->POST['pegAsalSatwilId'];
	}
    if($this->POST['pegSatwilId']==""){
      $s=NULL;
    }else{
	  $s=$this->POST['pegSatwilId'];
	}
	if($this->POST['pegAgamaId']==""){
      $ag=NULL;
    }else{
	  $ag=$this->POST['pegAgamaId'];
	}
	if($this->POST['pegStatnikahId']==""){
      $stn=NULL;
    }else{
	  $stn=$this->POST['pegStatnikahId'];
	}
	if($this->POST['pegGoldrhId']==""){
      $gol=NULL;
    }else{
	  $gol=$this->POST['pegGoldrhId'];
	}
	if($this->POST['pegJnspegrId']==""){
      $jnp=NULL;
    }else{
	  $jnp=$this->POST['pegJnspegrId'];
	}
	if($this->POST['pegStatrId']==""){
      $stp=NULL;
    }else{
	  $stp=$this->POST['pegStatrId'];
	}
	if($this->POST['pegLevelId']==""){
      $lvl=NULL;
    }else{
	  $lvl=$this->POST['pegLevelId'];
	}
	
	if($this->POST['datpegTinggiBdn']==""){
      $tgb=0;
    }else{
	  $tgb=$this->POST['datpegTinggiBdn'];
	}
	if($this->POST['datpegBeratBdn']==""){
      $brb=0;
    }else{
	  $brb=$this->POST['datpegBeratBdn'];
	}
	/* if($this->POST['datpegUsiaPens']==""){
      $usp=0;
    }else{
	  $usp=$this->POST['datpegUsiaPens'];
	} */
    $usp = $detail['pegUsiaPensiun'];
	if($this->POST['datpegKodeAbsen']==""){
      $kda=0;
    }else{
	  $kda=$this->POST['datpegKodeAbsen'];
	}
  /* if($this->POST['PegJenFungsional']==""){
      $pjf=NULL;
    }else{
    $pjf=$this->POST['PegJenFungsional'];
    } */
    $pjf = $detail['pegJenFungsional'];
	
    
    $array=array('nip'=>$this->POST['datpegNip'],'kodeGateAccess'=>$this->POST['datpegKodeGateAccess'],'kodeInter'=>$this->POST['datpegKodeInter'],'kodeLain'=>$this->POST['datpegKodeLain'],
      'nama'=>$this->POST['datpegNama'],'gelDep'=>$this->POST['datpegGelDep'],'gelBel'=>$this->POST['datpegGelBel'],'tmpLahir'=>$this->POST['datpegTmpLahir'],'tglLahir'=>$a,'noAkta'=>$this->POST['datpegNoAkta'],'akta'=>$file_akta,
	  'idLain'=>$this->POST['datpegIdLain'],
	  'idSkck'=>/* $this->POST['datpegSKCK'] */$detail['pegSkck'],'jenKel'=>$this->POST['pegJenkel'],'agama'=>$ag,'statNikah'=>$stn,
	  'asal'=>$this->POST['datpegAsal'],'alamat'=>$this->POST['datpegAlamat'],'kodePos'=>$this->POST['datpegKodePos'],'noTelp'=>$this->POST['datpegNoTelp'],'noHp'=>$this->POST['datpegNoHp'],
	  'email'=>$this->POST['datpegEmail'],
	  // 'kir'=>$this->POST['datpegNoKir'],'tglkir'=>$e,'noskbn'=>$this->POST['datpegNoSkbn'],'tglskbn'=>$g,'noskkb'=>$this->POST['datpegNoSkkb'],'tglskkb'=>$h,
	  'kir'=>$detail['pegKir'],'tglkir'=>$detail['pegTglKir'],'noskbn'=>$detail['pegNoSkbn'],'tglskbn'=>$detail['pegTglSkbn'],'noskkb'=>$detail['pegNoSkkb'],'tglskkb'=>$detail['pegTglSkkb'],
      'golDar'=>$gol,'tinggiBdn'=>$tgb,'beratBdn'=>$brb,
	  'cacat'=>$this->POST['datpegCacat'],'hobi'=>$this->POST['datpegHobi'],'tglmasuk'=>$b,'pnstmt'=>$c,'cpnstmt'=>$d,
	  'notaspen'=>$this->POST['datpegNoTaspen'],'noaskes'=>$this->POST['datpegNoAskes'],'statusnpwp'=>$this->POST['statusnpwp'],'nonpwp'=>$this->POST['datpegNoNpwp'],'tglnpwp'=>$f,'usiapens'=>$usp,
	  'kodeabsen'=>$kda,'jnsidlain'=>$this->POST['datpegJenIdLain'],'jnspeg'=>$jnp,'statpeg'=>$stp,
      'satwilpeg'=>$s,'datpegAsalDesa'=>$this->POST['datpegAsalDesa'],'datpegAsalKecamatan'=>$this->POST['datpegAsalKecamatan'],'asalsatwil'=>$as,
      'pegLevelId'=>$lvl,'pegStatusWargaNeg'=>$this->POST['pegStatusWargaNeg'],'foto'=>$nama_file,'userId'=>$this->user
	  ,'kelurahan'	=> $this->POST['datpegKelurahan']
	  ,'kecamatan'	=> $this->POST['datpegKecamatan']
	  ,'kepemilikanRumah'	=> $this->POST['datpegKepemilikanRumah']
	  ,'pegNoKarpeg'	=> $this->POST['pegNoKarpeg']
	  ,'pegNoKpe'	=> $this->POST['pegNoKpe']
    ,'PegJenFungsional' => $pjf
	  ,'id'=>$this->POST['pegId']);
    
    $result = $this->Obj->Update($array);
	if ($result) {
		$rs_add_2 = $this->AddDataBahasa($this->POST['pegId'],"edit");
	    if ($this->StatusIntegrasiAkademik){
			$input_gtakademik=$this->ObjGtakademik->AddPegawai($array,$this->POST['pegKatPeg']);
		}
	}
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
    function InputDatpeg(){
        $check = $this->Check();
        if($this->Check() !== true) return $check;
        
        $nip_cek = $this->Obj->getNip($this->POST['datpegNip']);
		$this->POST['datpegJenIdLain'] = 'KTP';
        
        if((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'add')){
            if (!empty($_FILES['file']['tmp_name'])){
		 	    $nama_file = rand(0,10000).trim($_FILES['file']['name']);
		    }
		    else{
		 	    $nama_file = $this->POST['datpegFoto'];
		 	}
		 	  
		 	if(empty($nip_cek)){
                // Nama file akta kelahiran
                $path_akta = GTFWConfiguration::GetValue('application', 'akta_pegawai_path');
                $upload_akta = false;
                if(isset($_FILES['pegAkta']) && $_FILES['pegAkta']['error'] == UPLOAD_ERR_OK && !empty($_FILES['pegAkta']['tmp_name'])) {
                    $upload_akta = true;
                    $file_akta = date('ymdHis').'-lahir-'.$this->CleanFilename($_FILES['pegAkta']['name']);
                }
                
                $rs_add = $this->AddDatpeg($nama_file, $file_akta);
                if($rs_add == true){
                    if (!empty($_FILES['file']['tmp_name'])){

  			            @unlink(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$this->POST['datpegFoto']);
  			            move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'photo_save_path').$nama_file);
  	                }
                    
  	                $idPegawai = $this->GetDataId();
  	                 $rs_add_2 = true;//$this->AddDatGajipeg($idPegawai,"input");
			        // $rs_add_2 = $this->AddDataRekening($idPegawai,"input");
			        $rs_add_2 = $rs_add_2 && $this->AddDataBahasa($idPegawai,"input");
  	                if($rs_add_2 == true){
                        // Commit file akta
                        if($upload_akta) {
                            move_uploaded_file($_FILES['pegAkta']['tmp_name'], $path_akta.$file_akta);
                        }
                        
                        Messenger::Instance()->Send('data_pegawai', 'dataPegawai', 'view', 'html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
                        return $this->pageView;
                    }
                    else{
                        Messenger::Instance()->Send('data_pegawai', 'dataPegawai', 'view', 'html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
                        return $this->pageView;
                    }
                }
                else{
                    Messenger::Instance()->Send('data_pegawai', 'dataPegawai', 'view', 'html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
                    return $this->pageView;
                }
            }
            else{
                Messenger::Instance()->Send('data_pegawai', 'inputDataPegawai', 'view', 'html', array($this->POST,$this->msgAddFail2,$this->cssFail),Messenger::NextRequest);
                return $this->pageInput;
            }
        }
        else if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
        if (!empty($_FILES['file']['tmp_name'])){
		 	    $nama_file = rand(0,10000).trim($_FILES['file']['name']);
		    }else{
		 	    $nama_file = $this->POST['datpegFoto'];
		 	}
        
        if(!empty($nip_cek) AND ($this->POST['datpegNip']==$this->POST['op1'])){
            $this->detail = $this->Obj->GetDatPegDetail($this->POST['pegId']);
            $detail = $this->detail;
            
            // Nama file akta kelahiran
            $path_akta = GTFWConfiguration::GetValue('application', 'akta_pegawai_path');
            $upload_akta = false;
            if(isset($_FILES['pegAkta']) && $_FILES['pegAkta']['error'] == UPLOAD_ERR_OK && !empty($_FILES['pegAkta']['tmp_name'])) {
                $upload_akta = true;
                $file_akta = date('ymdHis').'_lahir_'.$this->CleanFilename($_FILES['pegAkta']['name']);
            } else {
                $file_akta = $detail['pegAkta'];
            }
            
            $rs_update = $this->UpdateDatpeg($nama_file, $file_akta);
            if($rs_update == true){
                if (!empty($_FILES['file']['tmp_name'])){
  			        @unlink(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$this->POST['datpegFoto']);
  			        move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue('application', 'photo_save_path').$nama_file);
  	            }
  	            
  	            $rs_update_2 = true; //$this->AddDatGajipeg($this->POST['pegId'], "edit");
			    // $rs_update_2 = $this->AddDataRekening($this->POST['pegId'], "edit");
				$rs_update_2 = $rs_update_2 && $this->AddDataBahasa($idPegawai,"edit");
			    
  	            if($rs_update_2 == true){
                    // Commit file akta
                    if($upload_akta) {
                        if(!empty($detail['pegAkta'])) {
                            @unlink($path_akta.$detail['pegAkta']);
                        }
                        move_uploaded_file($_FILES['pegAkta']['tmp_name'], $path_akta.$file_akta);
                    }
                    Messenger::Instance()->Send('data_pegawai', 'dataPegawai', 'view', 'html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
                    return $this->pageView;
                }
                else{
                    Messenger::Instance()->Send('data_pegawai', 'dataPegawai', 'view', 'html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
                    return $this->pageView;
                }
          }
          else{
             Messenger::Instance()->Send('data_pegawai', 'dataPegawai', 'view', 'html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
             return $this->pageView;
          }
        }else{
          $ret=$this->pageInput;
  	      $ret .= "&dataId=".$this->decId;
          Messenger::Instance()->Send('data_pegawai', 'inputDataPegawai', 'view', 'html', array($this->POST,$this->msgAddFail2,$this->cssFail),Messenger::NextRequest);
          return $ret;
        }
      }
   }
   
   function Delete(){	
    $deleteData = $this->Obj->Delete($this->POST['idDelete']);
    if($deleteData == true) {
			Messenger::Instance()->Send('data_pegawai', 'dataPegawai', 'view', 'html', array($this->_POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('data_pegawai', 'dataPegawai', 'view', 'html', array($this->_POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}

		return $this->pageView;
   }
}

?>