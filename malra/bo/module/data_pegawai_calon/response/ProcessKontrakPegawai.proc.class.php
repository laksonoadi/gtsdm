<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_pegawai/business/data_kontrak_pegawai.class.php';

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
      $this->Obj = new DataKontrakPegawai();
      $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
      $this->decId = $_POST['dataId']->Integer()->Raw();
      $this->profilId = $_POST['nip']->Integer()->Raw();
      $this->delId = $_POST['nip']->Integer()->Raw();
      $this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
      if ($this->lang=='eng'){
       	$this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       	$this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       	$this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
       	$this->msgReqDataEmpty='All field marked with * and date field must be filled';
        $this->activeStatus='Active';$this->inactiveStatus='Inactive';
        $this->error='Data incomplete!';
      }else{
       	$this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       	$this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       	$this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
       	$this->msgReqDataEmpty='Semua data bertanda * dan data tanggal harus diisi';
	      $this->error='Data tidak lengkap!';
      	$this->activeStatus='Aktif';$this->inactiveStatus='Tidak Aktif';
      }

      if($ret == "html"){
         $this->pageInput = Dispatcher::Instance()->GetUrl('data_pegawai','historyKontrakPegawai','view','html').'&pegId='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('data_pegawai','historyKontrakPegawai','view','html').'&pegId='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('data_pegawai','historyKontrakPegawai','view','html').'&pegId='.$this->delId;
        }else{
         $this->pageInput = Dispatcher::Instance()->GetUrl('data_pegawai','historyKontrakPegawai','view','html',true).'&pegId='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('data_pegawai','historyKontrakPegawai','view','html',true).'&pegId='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('data_pegawai','historyKontrakPegawai','view','html').'&pegId='.$this->delId;
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

    if (isset($this->error)){
      $msg = array($this->POST, $this->msgReqDataEmpty, $this->cssAlert);
      Messenger::Instance()->Send('data_pegawai','historyKontrakPegawai','view','html', $msg, Messenger::NextRequest);
      
      $return = $this->pageInput;
      if (isset($_GET['dataId'])){ 
        $return .= "&dataId=".$this->decId."&pegId=".$this->profilId;
      }
      return $return;
    }

    return true;
  }
  
  function AddData($nama_file){
    
    $tgl_awal=$this->POST['tgl_awal_year'].'-'.$this->POST['tgl_awal_mon'].'-'.$this->POST['tgl_awal_day'];
    $tgl_akhir=$this->POST['tgl_akhir_year'].'-'.$this->POST['tgl_akhir_mon'].'-'.$this->POST['tgl_akhir_day'];
    $tgl_sk=$this->POST['tgl_sk_year'].'-'.$this->POST['tgl_sk_mon'].'-'.$this->POST['tgl_sk_day'];
    $tgl_status=$this->POST['tgl_status_year'].'-'.$this->POST['tgl_status_mon'].'-'.$this->POST['tgl_status_day'];

    $array=array(
				'nip'=>$this->POST['nip'],
				'tgl_awal'=>$tgl_awal,
				'tgl_akhir'=>$tgl_akhir,
				'pejabat'=>$this->POST['pejabat'],
				'nosk'=>$this->POST['sk_no'],
				'tgl_sk'=>$tgl_sk,
				'status'=>$this->POST['status'],
				'tgl_status'=>$tgl_sk,
				'upload'=>$nama_file,
				'userId'=>$this->user
			);

    $result = $this->Obj->Add($array);
    if ($result){
	  $getId=$this->Obj->GetMaxId();
	  if($this->activeStatus == $array['status']){
	     $stat_update=$this->Obj->UpdateStatus($this->inactiveStatus,$getId[0]['MAXID'],$this->profilId);
	  }
      return $result;
    }else{
      return false;
    }
  }
	
	function UpdateData($nama_file){ 
     
	  $tgl_awal=$this->POST['tgl_awal_year'].'-'.$this->POST['tgl_awal_mon'].'-'.$this->POST['tgl_awal_day'];
	  $tgl_akhir=$this->POST['tgl_akhir_year'].'-'.$this->POST['tgl_akhir_mon'].'-'.$this->POST['tgl_akhir_day'];
    $tgl_sk=$this->POST['tgl_sk_year'].'-'.$this->POST['tgl_sk_mon'].'-'.$this->POST['tgl_sk_day'];
    $tgl_status=$this->POST['tgl_status_year'].'-'.$this->POST['tgl_status_mon'].'-'.$this->POST['tgl_status_day'];
    
    $array=array(
				'nip'=>$this->POST['nip'],
				'tgl_awal'=>$tgl_awal,
				'tgl_akhir'=>$tgl_akhir,
				'pejabat'=>$this->POST['pejabat'],
				'nosk'=>$this->POST['sk_no'],
				'tgl_sk'=>$tgl_sk,
				'status'=>$this->POST['status'],
				'tgl_status'=>$tgl_status,
				'upload'=>$nama_file,
				'userId'=>$this->user,
				'id'=>$this->decId
			);
	 //$this->dumper($array);
	 
    $result = $this->Obj->Update($array);
    if ($result){
	  if($this->activeStatus == $array['status']){
	     $stat_update=$this->Obj->UpdateStatus($this->inactiveStatus,$this->decId,$this->profilId);
	  }
      return $result;
    }else{
      return false;
    }
  }
	
	function InputData(){
      #$check = $this->Check();
      #if ($check !== true) return $check;
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
	         
	         if($this->POST['dataStatus']=$this->activeStatus){
	           $tgl_akhir=$this->POST['tgl_akhir_year'].'-'.$this->POST['tgl_akhir_mon'].'-'.$this->POST['tgl_akhir_day'];
             $tgl_awal=$this->Obj->GetTanggalAwalKontrakPegawaiById($this->POST['nip']);
             
             $rs_update_tgl_keluar = $this->UpdateTglKeluarInstitusi($tgl_akhir,$this->profilId);
             $rs_update_tgl_masuk = $this->UpdateTglMasukInstitusi($tgl_awal,$this->profilId);
             
  	         if($rs_update_tgl_keluar == true){
                Messenger::Instance()->Send('data_pegawai','historyKontrakPegawai','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
                return $this->pageView;
             }else{
                Messenger::Instance()->Send('data_pegawai','historyKontrakPegawai','view','html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
                return $this->pageView;
             }
           }else{
             Messenger::Instance()->Send('data_pegawai','historyKontrakPegawai','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
             return $this->pageView;
           }
	         
        }else{
           Messenger::Instance()->Send('data_pegawai','historyKontrakPegawai','view','html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
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
	         
           if($this->POST['dataStatus']=$this->activeStatus){
	           $tgl_akhir=$this->POST['tgl_akhir_year'].'-'.$this->POST['tgl_akhir_mon'].'-'.$this->POST['tgl_akhir_day'];
             $tgl_awal=$this->Obj->GetTanggalAwalKontrakPegawaiById($this->POST['nip']);

             $rs_update_tgl_keluar = $this->Obj->UpdateTglKeluarInstitusi($tgl_akhir,$this->profilId);
             $rs_update_tgl_masuk = $this->Obj->UpdateTglMasukInstitusi($tgl_awal,$this->profilId);
             
  	         if($rs_update_tgl_keluar == true){
                Messenger::Instance()->Send('data_pegawai','historyKontrakPegawai','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
                return $this->pageView;
             }else{
                Messenger::Instance()->Send('data_pegawai','historyKontrakPegawai','view','html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
                return $this->pageView;
             }
           }else{
             Messenger::Instance()->Send('data_pegawai','historyKontrakPegawai','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
             return $this->pageView;
           }
        }else{
           Messenger::Instance()->Send('data_pegawai','historyKontrakPegawai','view','html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
           return $this->pageView;
        }
      }

      //return $urlRedirect;
   }
   
   function Delete(){
   //print_r($this->POST);exit();
   $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
   if($deleteData == true) {
			Messenger::Instance()->Send('data_pegawai', 'historyKontrakPegawai', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('data_pegawai', 'historyKontrakPegawai', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
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
