<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_pegawai/business/data_pegawai_gtakademik.class.php';

class Process
{
   var $POST;
   //var $FILES;
   var $user;
   var $ObjGtakademik;
   var $ObjPegawai;
   var $ObjMStatus;
   var $ObjMPagol;
   var $ObjMSatker;
   var $cssAlert = "notebox-alert";
   var $cssDone = "notebox-done";
   var $cssFail = "notebox-warning";
   var $pageInput;
   var $decId;
   var $pageView;
   public $statusMsg;
   public $statusCss;
   
   function __construct($ret) {
    $this->StatusIntegrasiAkademik=GTFWConfiguration::GetValue( 'application', 'status_integrasi_gtakademik');
	if ($this->StatusIntegrasiAkademik){
		$nomorkoneksi=GTFWConfiguration::GetValue( 'application', 'nomor_koneksi_gtakademik');
		$this->ObjGtakademik = new DataPegawaiGtakademik($nomorkoneksi);
	}
	
	  $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
    if($ret == "html"){
      $this->pageInput = Dispatcher::Instance()->GetUrl('data_pegawai', 'inputDataPegawai', 'view', 'html');
      $this->pageView = Dispatcher::Instance()->GetUrl('data_pegawai', 'dataPegawai', 'view', 'html');
    }else{
      $this->pageInput = Dispatcher::Instance()->GetUrl('data_pegawai', 'inputDataPegawai', 'view', 'html',true);
      $this->pageView = Dispatcher::Instance()->GetUrl('data_pegawai', 'dataPegawai', 'view', 'html',true);
    }
    $this->decId = $_POST['pegId']->Integer()->Raw();
    // $this->profilId = $_POST['pegId']->Integer()->Raw();
    $this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
	   if ($this->lang=='eng'){
       $this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';$this->msgAddFail2="Data addition failed, existing employee's number in the database";
       $this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       $this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
       $this->msgReqDataEmpty='All field marked with * must be filled';
       $this->msgReqPktEmpty='Select the grade first';
       $this->msgAddSuccessAll = 'Series of addition has been successfully completed';
     }else{
       $this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';$this->msgAddFail2="Penambahan data gagal dilakukan, NIP sudah ada di database";
       $this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       $this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
       $this->msgReqDataEmpty='Semua data bertanda * harus diisi';
       $this->msgReqPktEmpty='Pilihlah pangkat golongannya';
       $this->msgAddSuccessAll = 'Rangkaian penambahan data pegawai telah berhasil dilakukan';
     }
    $this->statusMsg = '';
    $this->statusCss = $this->cssDone;
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray();
      //$this->FILES = $param2->AsArray();
  }
  
  // ----==== STEP 1 - DATA PEGAWAI ====---- //
  function CheckPegawai (){
    if ((trim($this->POST['datpegNip']) == '') or (trim($this->POST['datpegNama']) == '')){
      $error = $this->msgReqDataEmpty;
    }elseif ((($this->POST['jabstruk'] != '') or ($this->POST['jabfung'] != '')) and ($this->POST['pktgol'] == '') and ($this->POST['idgol'] == '')){
      $error = $this->msgReqPktEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      // Messenger::Instance()->Send('data_pegawai', 'inputDataPegawai', 'view', 'html', $msg, Messenger::NextRequest);
        $this->statusMsg = $error;
        $this->statusCss = $this->cssAlert;
      
		return false;
    }
    return true;
  }
  
  function GetDataId(){  
    $result = $this->ObjPegawai->GetDataId($this->POST['datpegNip']);
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
  
  function AddDatpeg($nama_file){
    
    $a=$this->POST['datpegTglLahir_year'].'-'.$this->POST['datpegTglLahir_mon'].'-'.$this->POST['datpegTglLahir_day'];
    $b=$this->POST['datpegTglMasuk_year'].'-'.$this->POST['datpegTglMasuk_mon'].'-'.$this->POST['datpegTglMasuk_day'];
    $c=$this->POST['datpegPnsTmt_year'].'-'.$this->POST['datpegPnsTmt_mon'].'-'.$this->POST['datpegPnsTmt_day'];
	$d=$this->POST['datpegCpnsTmt_year'].'-'.$this->POST['datpegCpnsTmt_mon'].'-'.$this->POST['datpegCpnsTmt_day'];
    
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
	if($this->POST['datpegUsiaPens']==""){
      $usp=0;
    }else{
	  $usp=$this->POST['datpegUsiaPens'];
	}
	if($this->POST['datpegKodeAbsen']==""){
      $kda=0;
    }else{
	  $kda=$this->POST['datpegKodeAbsen'];
	}
	if($this->POST['PegJenFungsional']==""){
      $pjf=NULL;
    }else{
    $pjf=$this->POST['PegJenFungsional'];
  }
    
    $array=array('nip'=>$this->POST['datpegNip'],'kodeGateAccess'=>$rs['datpegKodeGateAccess'], 'kodeInter'=>$this->POST['datpegKodeInter'],'kodeLain'=>$this->POST['datpegKodeLain'],
      'nama'=>$this->POST['datpegNama'],'gelDep'=>$this->POST['datpegGelDep'],'gelBel'=>$this->POST['datpegGelBel'],'tmpLahir'=>$this->POST['datpegTmpLahir'],'tglLahir'=>$a,
	  'idLain'=>$this->POST['datpegIdLain'],
	  'idSkck'=>$this->POST['datpegSKCK'],'jenKel'=>$this->POST['pegJenkel'],'agama'=>$ag,'statNikah'=>$stn,
	  'alamat'=>$this->POST['datpegAlamat'],'kodePos'=>$this->POST['datpegKodePos'],'noTelp'=>$this->POST['datpegNoTelp'],'noHp'=>$this->POST['datpegNoHp'],
	  'email'=>$this->POST['datpegEmail'],
	  'kir'=>$this->POST['datpegNoKir'],'golDar'=>$gol,'tinggiBdn'=>$tgb,'beratBdn'=>$brb,
	  'cacat'=>$this->POST['datpegCacat'],'hobi'=>$this->POST['datpegHobi'],'tglmasuk'=>$b,'pnstmt'=>$c,'cpnstmt'=>$d,
	  'notaspen'=>$this->POST['datpegNoTaspen'],'noaskes'=>$this->POST['datpegNoAskes'],'statusnpwp'=>$this->POST['statusnpwp'],'nonpwp'=>$this->POST['datpegNoNpwp'],'usiapens'=>$usp,
	  'kodeabsen'=>$kda,'jnsidlain'=>$this->POST['datpegJenIdLain'],'jnspeg'=>$jnp,'statpeg'=>$stp,
      'satwilpeg'=>$s,'pegLevelId'=>$lvl,'pegStatusWargaNeg'=>$this->POST['pegStatusWargaNeg'], 'foto'=>$nama_file
	  ,'kelurahan'	=> $this->POST['datpegKelurahan']
	  ,'kecamatan'	=> $this->POST['datpegKecamatan']
	  ,'kepemilikanRumah'	=> $this->POST['datpegKepemilikanRumah']
	  ,'pegNoKarpeg'	=> $this->POST['pegNoKarpeg']
	  ,'pegNoKpe'	=> $this->POST['pegNoKpe']
    ,'PegJenFungsional' => $pjf
	  ,'userId'=>$this->user);

    $result = $this->ObjPegawai->Add($array);
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
    
    $cekKode = $this->ObjPegawai->GetKodeNikah($id);
    
    if(empty($cekKode)){
      $p2="in";
    }else{
      $p2="up";
    }
    
    if(!empty($this->POST['jabstruk'])){
      $idStruk = $this->ObjPegawai->GetIdStruk($this->POST['jabstruk']);
    }
    if(!empty($this->POST['jabfung'])){
      $idFung = $this->ObjPegawai->GetIdFung($this->POST['jabfung']);
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
    
    $result = $this->ObjPegawai->AddDatGaji($id,$kdn,$this->POST['satker'],$aa,$this->POST['jabstruk'],$this->POST['jabfung'],$array,$op,$p,$p2,$idStruk,$idFung,$dateNow,$this->POST['pegKatPeg'],$this->POST['pegTipPeg'],$this->POST['pegId1'],$this->POST['pegId2'],$this->POST['bank'],$this->POST['rekening'],$this->POST['resipien'],$this->user);
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
			$result=$this->ObjPegawai->AddDataRekening($id,$op,$bank,$rekening,$penerima);
			
			return $result;
		}else{
			return true;
		}
  }
	
  function AddDataBahasa($id,$op){
		if (!empty($this->POST['pegdatBahasa'])){
			$result=$this->ObjPegawai->AddDataBahasa($id,$this->POST['pegdatBahasa']);
			
			return $result;
		}else{
			return true;
		}
  }
	
    function InputDatpeg(){
        require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_pegawai/business/data_pegawai.class.php';
        $this->ObjPegawai = new DataPegawai();
        
        $validation = $this->CheckPegawai();
        // if($this->Check() !== true) return false;
        if(!$validation) return false;
        
        $nip_cek = $this->ObjPegawai->getNip($this->POST['datpegNip']);
		$this->POST['datpegJenIdLain'] = 'KTP';
        
        if (!empty($_FILES['file']['tmp_name'])){
            $nama_file = rand(0,10000).trim($_FILES['file']['name']);
        }
          
        if(empty($nip_cek)){
            $rs_add = $this->AddDatpeg($nama_file);
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
                    // Messenger::Instance()->Send('data_pegawai', 'dataPegawai', 'view', 'html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
                    $this->statusMsg = $this->msgAddSuccess;
                    $this->statusCss = $this->cssDone;
                    return true;
                }
                else{
                    // Messenger::Instance()->Send('data_pegawai', 'dataPegawai', 'view', 'html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
                    $this->statusMsg = $this->msgAddFail;
                    $this->statusCss = $this->cssFail;
                    return false;
                }
            }
            else{
                // Messenger::Instance()->Send('data_pegawai', 'dataPegawai', 'view', 'html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
                $this->statusMsg = $this->msgAddFail;
                $this->statusCss = $this->cssFail;
                return false;
            }
        }
        else{
            // Messenger::Instance()->Send('data_pegawai', 'inputDataPegawai', 'view', 'html', array($this->POST,$this->msgAddFail2,$this->cssFail),Messenger::NextRequest);
            $this->statusMsg = $this->msgAddFail2;
            $this->statusCss = $this->cssFail;
            return false;
        }
    }
   
  // ----==== END STEP 1 - DATA PEGAWAI ====---- //
  
  // ----==== STEP 2 - STATUS ====---- //
	function CheckStatus (){
		if (trim($this->POST['statr']) == ''){
			$error = $this->msgReqDataEmpty;
		}
    
		if (isset($error)){
			$msg = array($this->POST, $error, $this->cssAlert);
			// Messenger::Instance()->Send('mutasi_status','MutasiStatus','view','html', $msg, Messenger::NextRequest);
            
            $this->statusMsg = $error;
            $this->statusCss = $this->cssAlert;
      
			return false;
		}
		return true;
	}
  
	function AddStatus($nama_file){
		$tmt=$this->POST['tmt_year'].'-'.$this->POST['tmt_mon'].'-'.$this->POST['tmt_day'];
		$tgl_sk=$this->POST['tgl_sk_year'].'-'.$this->POST['tgl_sk_mon'].'-'.$this->POST['tgl_sk_day'];

		$array=array(
				'pegKode'=>$this->POST['pegId'],
				'statr'=>$this->POST['statr'],
				'tmt'=>$tmt,
				'pejabat'=>$this->POST['pejabat'],
				'nosk'=>$this->POST['sk_no'],
				'tgl_sk'=>$tgl_sk,
				'status'=>$this->POST['status'],
				'upload'=>$nama_file
			);
		
		$result = $this->ObjMStatus->Add($array);
		if ($result){
			$getId=$this->ObjMStatus->GetMaxId();
			if($array['status']=='Aktif'){
				$stat_update=$this->ObjMStatus->UpdateStatus('Tidak Aktif',$getId[0]['MAXID'],$this->decId);
			}
			return $result;
		}else{
			return false;
		}
	}
	
	function InputStatus(){
        require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_status/business/mutasi_status.class.php';
        $this->ObjMStatus = new MutasiStatus();
        
        $validation = $this->CheckStatus();
        // if($this->Check() !== true) return false;
        if(!$validation) return false;
        
        if (!empty($_FILES['file']['tmp_name'])){
            $nama_file = rand(0,10000).trim($_FILES['file']['name']);
        }else{
            $nama_file = $this->POST['upload'];
        }
        $rs_add = $this->AddStatus($nama_file);
        
        if($rs_add == true){
            if (!empty($_FILES['file']['tmp_name'])){
                @unlink(GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$this->POST['upload']);
                move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$nama_file);
            }
            // Messenger::Instance()->Send('mutasi_status','MutasiStatus','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
            $this->statusMsg = $this->msgAddSuccess;
            $this->statusCss = $this->cssDone;
            return true;
        }else{
            // Messenger::Instance()->Send('mutasi_status','MutasiStatus','view','html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
            $this->statusMsg = $this->msgAddFail;
            $this->statusCss = $this->cssFail;
            return false;
        }
		//return $urlRedirect;
	}
  
  // ----==== END STEP 2 - STATUS ====---- //
  
  // ----==== STEP 3 - PANGKAT/GOLONGAN ====---- //
  
    function CheckPagol(){
        if (trim($this->POST['golongan_ref']) == ''){
            $error = $this->msgReqDataEmpty;
        }

        if (isset($error)){
            $msg = array($this->POST, $error, $this->cssAlert);
            // Messenger::Instance()->Send('mutasi_pangkat_golongan','MutasiPangkatGolongan','view','html', $msg, Messenger::NextRequest);
            $this->statusMsg = $error;
            $this->statusCss = $this->cssAlert;
          
            return false;
        }
        return true;
    }
  
    function AddPagol($nama_file){
        $tmt=$this->POST['tmt_year'].'-'.$this->POST['tmt_mon'].'-'.$this->POST['tmt_day'];
        $tgl_sk=$this->POST['tgl_sk_year'].'-'.$this->POST['tgl_sk_mon'].'-'.$this->POST['tgl_sk_day'];
        $tgl_naik=$this->POST['tgl_naik_year'].'-'.$this->POST['tgl_naik_mon'].'-'.$this->POST['tgl_naik_day'];
        $array=array(
                    'pegKode'=>$this->POST['pegId'],
                    'jenis_pegawai'=>$this->POST['jenis_pegawai'],
                    'golongan'=>$this->POST['golongan_ref'],
                    'tmt'=>$tmt,
                    'tgl_naik'=>$tgl_naik,
                    'pejabat'=>$this->POST['pejabat'],
                    'nosk'=>$this->POST['sk_no'],
                    'tgl_sk'=>$tgl_sk,
                    'dasar'=>$this->POST['dasar'],
                    'status'=>$this->POST['status'],
                    'upload'=>$nama_file
                );
        $result = $this->ObjMPagol->Add($array);
        if ($result){
          $getId=$this->ObjMPagol->GetMaxId();
          if($array['status']=='Aktif'){
             $stat_update=$this->ObjMPagol->UpdateStatus('Tidak Aktif',$getId[0]['MAXID'],$this->decId);
          }
          return $result;
        }else{
          return false;
        }
    }
	
	function InputPagol(){
        require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pangkat_golongan/business/mutasi_pangkat_golongan.class.php';
        $this->ObjMPagol = new MutasiPangkatGolongan();
        
        $validation = $this->CheckPagol();
        // if($this->Check() !== true) return false;
        if(!$validation) return false;
        
        if (!empty($_FILES['file']['tmp_name'])){
		 	    $nama_file = rand(0,10000).trim($_FILES['file']['name']);
        }
        $rs_add = $this->AddPagol($nama_file);
		    
        if($rs_add == true){
            if (!empty($_FILES['file']['tmp_name'])){
                @unlink(GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$this->POST['upload']);
                move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$nama_file);
            }
            // Messenger::Instance()->Send('mutasi_pangkat_golongan','MutasiPangkatGolongan','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
            $this->statusMsg = $this->msgAddSuccess;
            $this->statusCss = $this->cssDone;
            return true;
        }else{
            // Messenger::Instance()->Send('mutasi_pangkat_golongan','MutasiPangkatGolongan','view','html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
            $this->statusMsg = $this->msgAddFail;
            $this->statusCss = $this->cssFail;
            return false;
        }
   }
   
  // ----==== END STEP 3 - PANGKAT/GOLONGAN ====---- //
  
  // ----==== STEP 4 - UNIT KERJA ====---- //
  
    function CheckSatker(){
        if (trim($this->POST['satker']) == ''){
            $error = $this->msgReqDataEmpty;
        }

        if (isset($error)){
            $msg = array($this->POST, $error, $this->cssAlert);
            // Messenger::Instance()->Send('mutasi_satuan_kerja','MutasiSatuanKerja','view','html', $msg, Messenger::NextRequest);
            $this->statusMsg = $error;
            $this->statusCss = $this->cssAlert;

            return false;
        }
        return true;
    }
  
    function AddSatker($nama_file){
        $tmt=$this->POST['tmt_year'].'-'.$this->POST['tmt_mon'].'-'.$this->POST['tmt_day'];
        $tgl_sk=$this->POST['tgl_sk_year'].'-'.$this->POST['tgl_sk_mon'].'-'.$this->POST['tgl_sk_day'];

        $array=array(
                    'pegKode'=>$this->POST['pegId'],
                    'satker'=>$this->POST['satker'],
                    'ref_jab'=>$this->POST['ref_jab'],
                    'jenpeg'=>$this->POST['jenpeg'],
                    'tmt'=>$tmt,
                    'pejabat'=>$this->POST['pejabat'],
                    'nosk'=>$this->POST['sk_no'],
                    'tgl_sk'=>$tgl_sk,
                    'status'=>$this->POST['status'],
                    'upload'=>$nama_file,
                    'tugas'=>$this->POST['tugas'],
                );
        $result = $this->ObjMSatker->Add($array);
        if ($result){
          $getId=$this->ObjMSatker->GetMaxId();
          if($array['status']=='Aktif'){
            $stat_update=$this->ObjMSatker->UpdateStatus('Tidak Aktif',$getId[0]['MAXID'],$this->decId);
          }
          return $result;
        }else{
          return false;
        }
    }
	
	function InputSatker(){
        require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_satuan_kerja/business/mutasi_satuan_kerja.class.php';
        $this->ObjMSatker = new MutasiSatuanKerja();
        
        $headlevel = $this->ObjMSatker->GetKepalaSatker($this->POST['satker']);

        // print_r($headlevel);
        // echo $this->POST['pgtgkt'];
        if(empty($headlevel)){
         $headlevel[0]['level']= 50; 
        }else{
          $headlevel[0]['level'] = $headlevel[0]['level'];
        }

        if($headlevel[0]['level'] <  $this->POST['pgtgkt'] ){
            // echo 'in';exit();
            // Messenger::Instance()->Send('mutasi_satuan_kerja','MutasiSatuanKerja','view','html', array($this->POST,'Data gagal ditambah!, Pangkat Pegawai lebih tinggi dari Kepala Satuan Kerja',$this->cssFail),Messenger::NextRequest);
            // return $this->pageView;      
            $this->statusMsg = 'Data gagal ditambah!, Pangkat Pegawai lebih tinggi dari Kepala Satuan Kerja';
            $this->statusCss = $this->cssFail;
            return false;
        }else{
          $validation = $this->CheckSatker();
          // if($this->Check() !== true) return false;
          if(!$validation) return false;
        
          if (!empty($_FILES['file']['tmp_name'])){
              $nama_file = rand(0,10000).trim($_FILES['file']['name']);
          }else{
              $nama_file = $this->POST['upload'];
          }
          $rs_add = $this->AddSatker($nama_file);

          if($rs_add == true){
              if (!empty($_FILES['file']['tmp_name'])){
                  @unlink(GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$this->POST['upload']);
                  move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$nama_file);
              }
              // Messenger::Instance()->Send('mutasi_satuan_kerja','MutasiSatuanKerja','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
              $this->statusMsg = $this->msgAddSuccess;
              $this->statusCss = $this->cssDone;
              return true;
          }else{
              // Messenger::Instance()->Send('mutasi_satuan_kerja','MutasiSatuanKerja','view','html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
              $this->statusMsg = $this->msgAddFail;
              $this->statusCss = $this->cssFail;
              return false;
          }
        }

        // exit();
      
        
   }
  
  // ----==== END STEP 4 - UNIT KERJA ====---- //
}

?>
