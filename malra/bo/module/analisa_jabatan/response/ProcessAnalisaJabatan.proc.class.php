<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_jabatan_struktural/business/mutasi_jabatan_struktural.class.php';

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
      $this->Obj = new MutasiJabatanStruktural();
      $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
      $this->decId = $_POST['dataKode']->Integer()->Raw();
      $this->profilId = $_POST['pegKode']->Integer()->Raw();
      $this->delId = $_GET['id']->Integer()->Raw();
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
         $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_jabatan_struktural','MutasiJabatanStruktural','view','html').'&id='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_jabatan_struktural','MutasiJabatanStruktural','view','html').'&id='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_jabatan_struktural','MutasiJabatanStruktural','view','html').'&id='.$this->delId;
        }else{
         $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_jabatan_struktural','MutasiJabatanStruktural','view','html',true).'&id='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_jabatan_struktural','MutasiJabatanStruktural','view','html',true).'&id='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_jabatan_struktural','MutasiJabatanStruktural','view','html').'&id='.$this->delId;
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
      
    #if ((trim($this->POST['jabs_ref']) == '') or (trim($this->POST['eselon']) == '')){
	  if (trim($this->POST['jabs_ref']) == ''){
       $this->msgReqDataEmpty;
    }

    if (isset($this->error)){
      $msg = array($this->POST, $this->msgReqDataEmpty, $this->cssAlert);
      Messenger::Instance()->Send('mutasi_jabatan_struktural','MutasiJabatanStruktural','view','html', $msg, Messenger::NextRequest);
      
      $return = $this->pageInput;
      if (isset($_GET['dataId'])){ 
        $return .= "&dataId=".$this->decId."&id=".$this->profilId;
      }
      return $return;
    }

    return true;
  }
  
  function AddDataMutasi($id,$op){
    $idStruk = $this->Obj->GetIdStruk($this->POST['jabs_ref']);
    $dateNow=date('Y').date('m').date('d');
    
    if($op=="input"){
      $getMaxId=$this->Obj->GetMaxId();
      $getIdLain=$this->Obj->GetIdLain($getMaxId[0]['MAXID'],$id);
      $result = $this->Obj->AddDataMutasi($id,$idStruk,$dateNow,$getIdLain);
    }else{
      $getIdLain=$this->Obj->GetIdLain($this->POST['dataKode'],$id);
      $idStruk2 = $this->Obj->GetIdStruk($this->POST['dataStruk']);
      if($this->POST['dataStatus']==$this->inactiveStatus){
        $result = $this->Obj->AddDataMutasi($id,$idStruk,$dateNow,$getIdLain);
      }else{
        $result = $this->Obj->UpdateDataMutasi($idStruk,$dateNow,$id,$idStruk2);
      }
      
    }
    
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
  
  function AddData($nama_file){
    
    $mulai=$this->POST['mulai_year'].'-'.$this->POST['mulai_mon'].'-'.$this->POST['mulai_day'];
    $selesai=$this->POST['selesai_year'].'-'.$this->POST['selesai_mon'].'-'.$this->POST['selesai_day'];
    $tgl_sk=$this->POST['tgl_sk_year'].'-'.$this->POST['tgl_sk_mon'].'-'.$this->POST['tgl_sk_day'];
    
    $array=array(
				'pegKode'=>$this->POST['pegKode'],
				'jabs_ref'=>$this->POST['jabs_ref'],
				'eselon'=>$this->POST['eselon'],
				'golongan_ref'=>$this->POST['golongan_ref'],
				'mulai'=>$mulai,
				'selesai'=>$selesai,
				'pejabat'=>$this->POST['pejabat'],
				'nosk'=>$this->POST['sk_no'],
				'tgl_sk'=>$tgl_sk,
				'status'=>$this->POST['status'],
				'upload'=>$nama_file
			);
    //$this->dumper($array);
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
     
	 $mulai=$this->POST['mulai_year'].'-'.$this->POST['mulai_mon'].'-'.$this->POST['mulai_day'];
	 $selesai=$this->POST['selesai_year'].'-'.$this->POST['selesai_mon'].'-'.$this->POST['selesai_day'];
    $tgl_sk=$this->POST['tgl_sk_year'].'-'.$this->POST['tgl_sk_mon'].'-'.$this->POST['tgl_sk_day'];
    
    $array=array(
				'pegKode'=>$this->POST['pegKode'],
				'jabs_ref'=>$this->POST['jabs_ref'],
				'eselon'=>$this->POST['eselon'],
				'golongan_ref'=>$this->POST['golongan_ref'],
				'mulai'=>$mulai,
				'selesai'=>$selesai,
				'pejabat'=>$this->POST['pejabat'],
				'nosk'=>$this->POST['sk_no'],
				'tgl_sk'=>$tgl_sk,
				'status'=>$this->POST['status'],
				'upload'=>$nama_file,
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
	         
           if(($this->POST['status']==$this->activeStatus) and ($this->POST['kat']=="Academic")){
             $rs_add_2 = true; //$this->AddDataMutasi($this->POST['pegKode'],"input");
  	         if($rs_add_2 == true){
                Messenger::Instance()->Send('mutasi_jabatan_struktural','MutasiJabatanStruktural','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
                return $this->pageView;
             }else{
                Messenger::Instance()->Send('mutasi_jabatan_struktural','MutasiJabatanStruktural','view','html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
                return $this->pageView;
             }
           }else{
             Messenger::Instance()->Send('mutasi_jabatan_struktural','MutasiJabatanStruktural','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
             return $this->pageView;
           }
	         
        }else{
           Messenger::Instance()->Send('mutasi_jabatan_struktural','MutasiJabatanStruktural','view','html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
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
	         if(($this->POST['status']==$this->activeStatus) and ($this->POST['kat']=="Academic")){
             $rs_update_2 = true; //$this->AddDataMutasi($this->POST['pegKode'],"update");
  	         if($rs_update_2 == true){
                Messenger::Instance()->Send('mutasi_jabatan_struktural','MutasiJabatanStruktural','view','html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
                return $this->pageView;
             }else{
                Messenger::Instance()->Send('mutasi_jabatan_struktural','MutasiJabatanStruktural','view','html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
                return $this->pageView;
             }
           }else{
             Messenger::Instance()->Send('mutasi_jabatan_struktural','MutasiJabatanStruktural','view','html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
             return $this->pageView;
           }
        }else{
           Messenger::Instance()->Send('mutasi_jabatan_struktural','MutasiJabatanStruktural','view','html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
           return $this->pageView;
        }
      }

      //return $urlRedirect;
   }
   
   function Delete(){
   //print_r($this->POST);exit();
   $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
   if($deleteData == true) {
			Messenger::Instance()->Send('mutasi_jabatan_struktural', 'MutasiJabatanStruktural', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('mutasi_jabatan_struktural', 'MutasiJabatanStruktural', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
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
