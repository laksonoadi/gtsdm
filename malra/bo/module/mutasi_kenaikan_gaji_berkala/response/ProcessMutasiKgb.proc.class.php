<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_kenaikan_gaji_berkala/business/mutasi_kgb.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_pegawai/business/data_pegawai.class.php';

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
   var $pageBack;
   
   function __construct($ret) {
      $this->Obj = new MutasiKgb();
      $this->ObjPeg = new DataPegawai();
      $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
      $this->decId = $_POST['dataKode']->Integer()->Raw();
      $this->profilId = $_POST['pegKode']->Integer()->Raw();
      $this->delId = $_GET['id']->Integer()->Raw();
      if($ret == "html"){
         $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_kenaikan_gaji_berkala','MutasiJabatanStruktural','view','html').'&id='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_kenaikan_gaji_berkala','MutasiKgb','view','html').'&id='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_kenaikan_gaji_berkala','MutasiKgb','view','html').'&id='.$this->delId;
        }else{
         $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_kenaikan_gaji_berkala','MutasiKgb','view','html',true).'&id='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_kenaikan_gaji_berkala','MutasiKgb','view','html',true).'&id='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_kenaikan_gaji_berkala','MutasiKgb','view','html').'&id='.$this->delId;
        }
      $this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
      if ($this->lang=='eng'){
        $this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
        $this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
        $this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
        $this->msgReqDataEmpty='All field marked with * must be filled';
      }else{
        $this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
        $this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
        $this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
        $this->msgReqDataEmpty='Semua data bertanda * harus diisi';
      }
      //print_r($this->pageView);exit();
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray();
      //$this->FILES = $param2->AsArray();
  }
  
  function Check (){
    if (isset($this->POST['btnbatal'])) {
      return $this->pageView;
      }
      
    if (trim($this->POST['gapok']) == ''){
      $error = $this->msgReqDataEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('mutasi_kenaikan_gaji_berkala','MutasiKgb','view','html', $msg, Messenger::NextRequest);
      
      $return = $this->pageInput;
      if (isset($_GET['dataId'])){ 
      $return .= "&dataId=".$this->decId."&id=".$this->profilId;
      }
      return $return;
    }
    return true;
  }
  
/*
kgbPegKode = '%s',
	kgbPktgolId = '%s',
	kgbGajiPokokBaru = '%s',
	kgbMasaKerja = '%s',
	kgbBerlakuTanggal = '%s',
	kgbTanggalAkanDatang = '%s',
	kgbPejabatPenetap = '%s',
	kgbTanggalPenetap = '%s',
	kgbNomorPenetap = '%s',
	kgbAktif = '%s',
	kgbSkUpload = '%s'
*/  
   //menambahkan komponen gaji ke tabel sdm_komponen_gaji_pegawai_detail
  function AddDataMutasi($id,$op){
    $idStruk = $this->POST['nominal'];
    $dateNow=date('Y').date('m').date('d');
    
    if($op=="input"){
      $getMaxId=$this->Obj->GetMaxId();
      $getIdLain=$this->Obj->GetIdLain($getMaxId[0]['MAXID'],$id);
      $result = $this->Obj->AddDataMutasi($id,$idStruk,$dateNow,$getIdLain);
    }else{
      $getIdLain=$this->Obj->GetIdLain($this->POST['dataKode'],$id);
      $idStruk2 = $this->POST['nominal_asli'];
      if($this->POST['dataStatus']=="Tidak Aktif"){
        $result = $this->Obj->AddDataMutasi($id,$idStruk,$dateNow,$getIdLain);
      }else{
        $result = $this->Obj->UpdateDataMutasi($idStruk,$dateNow,$id,$idStruk2);
      }
      
    }
    
    //print_r($aa);
    
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
  
  function AddData($nama_file){
    
    $mulai=$this->POST['mulai_year'].'-'.$this->POST['mulai_mon'].'-'.$this->POST['mulai_day'];
    $yad=$this->POST['yad_year'].'-'.$this->POST['yad_mon'].'-'.$this->POST['yad_day'];
    $tgl_sk=$this->POST['tgl_sk_year'].'-'.$this->POST['tgl_sk_mon'].'-'.$this->POST['tgl_sk_day'];
    
    $array=array(
				'pegKode'=>$this->POST['pegKode'],
				'golongan_ref'=>$this->POST['golongan_ref'],
				'gapok'=>$this->POST['gapok'],
				//'masa'=>$this->POST['masa_label'],
				'masa_thn'=>(int)$this->POST['masa_thn'],
				'masa_bln'=>(int)$this->POST['masa_bln'],
				'mulai'=>$mulai,
				'yad'=>$yad,
				'pejabat'=>$this->POST['pejabat'],
				'tgl_sk'=>$tgl_sk,
				'nosk'=>$this->POST['sk_no'],
				'status'=>$this->POST['status'],
				'upload'=>$nama_file
			);
  //$this->dumper($array);exit();
    $result = $this->Obj->Add($array);
    if ($result){
	  // $getId=$this->Obj->GetMaxId();
	  // if($array['status']=='Aktif'){
	  //    $stat_update=$this->Obj->UpdateStatus('Tidak Aktif',$getId[0]['MAXID'],$this->profilId);
	  // }
       // Update last data
            $insert_id = $this->Obj->LastInsertId();
            $last = $this->Obj->GetLastMutasiKgb($this->POST['pegKode']);
            if($last === TRUE || $last['id'] == $insert_id) {
                $params = array(
                    'pegIdGjBerkala' => $insert_id,
                    'pegGjBerkala' => $last['gajiPokok']
                );
                $result = $result && $this->ObjPeg->UpdateCustom($params, $this->POST['pegKode']);
            }


      return $result;
    }else{
      return false;
    }
  }
	
	function UpdateData($nama_file){ 
     
	 $mulai=$this->POST['mulai_year'].'-'.$this->POST['mulai_mon'].'-'.$this->POST['mulai_day'];
	 $yad=$this->POST['yad_year'].'-'.$this->POST['yad_mon'].'-'.$this->POST['yad_day'];
    $tgl_sk=$this->POST['tgl_sk_year'].'-'.$this->POST['tgl_sk_mon'].'-'.$this->POST['tgl_sk_day'];
    
    $array=array(
				'pegKode'=>$this->POST['pegKode'],
				'golongan_ref'=>$this->POST['golongan_ref'],
				'gapok'=>$this->POST['gapok'],
				//'masa'=>$this->POST['masa_label'],
				'masa_thn'=>(int)$this->POST['masa_thn'],
				'masa_bln'=>(int)$this->POST['masa_bln'],
				'mulai'=>$mulai,
				'yad'=>$yad,
				'pejabat'=>$this->POST['pejabat'],
				'tgl_sk'=>$tgl_sk,
				'nosk'=>$this->POST['sk_no'],
				'status'=>$this->POST['status'],
				'upload'=>$nama_file,
				'id'=>$this->decId
			);
	 //$this->dumper($array);exit();
    $result = $this->Obj->Update($array);
    if ($result){
	  // if($array['status']=='Aktif'){
	  //    $stat_update=$this->Obj->UpdateStatus('Tidak Aktif',$this->decId,$this->profilId);
	  // }
       $last = $this->Obj->GetLastMutasiKgb($this->POST['pegKode']);
            if($last === TRUE || $last['id'] == $this->decId) {
                $params = array(
                    'pegIdGjBerkala' => $this->decId,
                    'pegGjBerkala' => $last['gajiPokok']
                );
                $result = $result && $this->ObjPeg->UpdateCustom($params, $this->POST['pegKode']);
            }
      return $result;
    }else{
      return false;
    }
  }
	
	function InputData(){
      $check = $this->Check();
      if ($check !== true) return $check;
      if ((isset($this->POST['btnsimpan'])) && empty($this->decId)) {
        if (!empty($_FILES['file']['tmp_name'])){
		 	    $nama_file = rand(0,10000).trim($_FILES['file']['name']);
		    }else{
		 	    $nama_file = $this->POST['upload'];
		 	  }
        $rs_add = $this->AddData($nama_file);
		    
        if($rs_add == true){
		   
           if (!empty($_FILES['file']['tmp_name'])){
			       @unlink(GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$this->POST['upload']);
			       move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$nama_file);
	         }
           
           /* untuk setting gaji pegawai negeri 
           if($this->POST['status']=="Aktif"){
             $rs_add_2 = $this->AddDataMutasi($this->POST['pegKode'],"input");
  	         if($rs_add_2 == true){
                Messenger::Instance()->Send('mutasi_kenaikan_gaji_berkala','MutasiKgb','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
                return $this->pageView;
             }else{
                Messenger::Instance()->Send('mutasi_kenaikan_gaji_berkala','MutasiKgb','view','html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
                return $this->pageView;
             }
           }else{*/
             Messenger::Instance()->Send('mutasi_kenaikan_gaji_berkala','MutasiKgb','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
             return $this->pageView;
           //}
        }else{
           Messenger::Instance()->Send('mutasi_kenaikan_gaji_berkala','MutasiKgb','view','html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
           return $this->pageView;
        }
      }else if ((isset($this->POST['btnsimpan'])) && !empty($this->decId)) {
        if (!empty($_FILES['file']['tmp_name'])){
		 	    $nama_file = rand(0,10000).trim($_FILES['file']['name']);
		    }else{
		 	    $nama_file = $this->POST['upload'];
		 	  }
        $rs_update = $this->UpdateData($nama_file);
        	
        if($rs_update == true){
           if (!empty($_FILES['file']['tmp_name'])){
			       @unlink(GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$this->POST['upload']);
			       move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$nama_file);
	         }
           
           /* untuk setting gaji pegawai negeri
           if($this->POST['status']=="Aktif"){
             $rs_update_2 = $this->AddDataMutasi($this->POST['pegKode'],"update");
  	         if($rs_update_2 == true){
                Messenger::Instance()->Send('mutasi_kenaikan_gaji_berkala','MutasiKgb','view','html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
                return $this->pageView;
             }else{
                Messenger::Instance()->Send('mutasi_kenaikan_gaji_berkala','MutasiKgb','view','html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
                return $this->pageView;
             }
           }else{*/
             Messenger::Instance()->Send('mutasi_kenaikan_gaji_berkala','MutasiKgb','view','html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
             return $this->pageView;
           //}
        }else{
           Messenger::Instance()->Send('mutasi_kenaikan_gaji_berkala','MutasiKgb','view','html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
           return $this->pageView;
        }
      }
      //return $urlRedirect;
   }
   
   function Delete(){
   //print_r($this->POST);exit();
   $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
   if($deleteData == true) {
			Messenger::Instance()->Send('mutasi_kenaikan_gaji_berkala', 'MutasiKgb', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('mutasi_kenaikan_gaji_berkala', 'MutasiKgb', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
   $return = $this->pageBack;
   //$return .= "&id=".$this->delId;
	return $return;
   }
   
   function dumper($print){
	   echo"<pre>";print_r($print);echo"</pre>";
	}
}

?>