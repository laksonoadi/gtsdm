<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_jabatan_fungsional/business/jabatan_fungsional.class.php';

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
   
   function __construct() {
    	$this->Obj = new JabatanFungsional();
	$this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	$this->decId = $_GET['editId']->Integer()->Raw();
	$this->profilId = $_GET['profil']->Integer()->Raw();
    
      	$this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional','JabatanFungsional','view','html').'&id='.$this->profilId;
      	$this->pageView = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional','JabatanFungsional','view','html').'&id='.$this->profilId;
      	$this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional','pegawai','view','html');
	//print_r($this->pageView);exit();
  $this->error='Data incomplete!';   
	$this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
    	if ($this->lang=='eng'){
       		$this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       		$this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       		$this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
       		$this->msgReqDataEmpty='All field marked with * and date field must be filled';
		      $this->activeStatus='Active';$this->inactiveStatus='Inactive';
     	}else{
       		$this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       		$this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       		$this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
       		$this->msgReqDataEmpty='Semua data bertanda * dan data tanggal harus diisi.';
		$this->activeStatus='Aktif';$this->inactiveStatus='Tidak Aktif';     	
	}
		
		if($ret == "html"){
         $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional','JabatanFungsional','view','html').'&id='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional','JabatanFungsional','view','html').'&id='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional','JabatanFungsional','view','html').'&id='.$this->delId;
        }else{
         $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional','JabatanFungsional','view','html',true).'&id='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional','JabatanFungsional','view','html',true).'&id='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional','JabatanFungsional','view','html').'&id='.$this->delId;
        }
    
    }
	
    function SetPost($param){
      $this->POST = $param->AsArray();
      //$this->FILES = $param2->AsArray();
    }
  
  function Check (){
    if (isset($this->POST['btnbatal'])) return $this->pageView;
    //if ((trim($this->POST['ref_jab']) == '') or (trim($this->POST['gol']) == '')){
    if (trim($this->POST['ref_jab']) == ''){
      $error=$this->msgReqDataEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->POST, $this->msgReqDataEmpty, $this->cssAlert);
      Messenger::Instance()->Send('mutasi_jabatan_fungsional','JabatanFungsional','view','html', $msg, Messenger::NextRequest);
      
      $return = $this->pageInput;
      if (isset($_GET['editId'])){ 
      $return .= "&editId=".$this->decId."&id=".$this->profilId;
      }
      return $return;
    }
    return true;
  }
  
  
  function AddDataMutasi($id,$op){
    $idFung = $this->Obj->GetIdFung($this->POST['ref_jab']);
    $dateNow=date('Y').date('m').date('d');
    
    if($op=="input"){
      $getMaxId=$this->Obj->GetMaxId();
      $getIdLain=$this->Obj->GetIdLain($getMaxId[0]['MAXID'],$id);
      $result = $this->Obj->AddDataMutasi($id,$idFung[0]['komp1'],$this->POST['komp'],$dateNow,$getIdLain);
    }else{
      $getIdLain=$this->Obj->GetIdLain($this->POST['dataKode'],$id);
      $idFung2 = $this->Obj->GetIdFung($this->POST['dataFung']);
      if($this->POST['dataStatus']==$this->inactiveStatus){
        $result = $this->Obj->AddDataMutasi($id,$idFung[0]['komp1'],$this->POST['komp'],$dateNow,$getIdLain);
      }else{
        $result = $this->Obj->UpdateDataMutasi($idFung[0]['komp1'],$this->POST['komp'],$dateNow,$id,$idFung2[0]['komp1'],$this->POST['komp2']);
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
    $selesai=$this->POST['selesai_year'].'-'.$this->POST['selesai_mon'].'-'.$this->POST['selesai_day'];
    $sk_tgl=$this->POST['sk_tgl_year'].'-'.$this->POST['sk_tgl_mon'].'-'.$this->POST['sk_tgl_day'];
    
    $array=array(
				'pegKode'=>$this->POST['pegKode'],
				'ref_jab'=>$this->POST['ref_jab'],
				'gol'=>$this->POST['golongan_ref'],
				'mulai'=>$mulai,
				'selesai'=>$selesai,
				'pejabat'=>$this->POST['sk'],
				'nomor'=>$this->POST['sk_no'],
				'tanggal'=>$sk_tgl,
				'status'=>$this->POST['status'],
				'upload'=>$nama_file,
				'komp'=>$this->POST['komp'],
				'sksmaks'=>$this->POST['sksMaks'],
				'ak'=>$this->POST['ak']
			);
    //$this->dumper($array);exit();
    $result = $this->Obj->Add($array);
    if ($result){
	  $getId=$this->Obj->GetMaxId();
	  if($array['status']==$this->activeStatus){
	     $stat_update=$this->Obj->UpdateStatus($this->inactiveStatus,$getId[0]['MAXID'],$this->POST['pegKode']);
	  }
      return $result;
    }else{
      return false;
    }
  }
	
	function UpdateData($nama_file){ 
     
	  $mulai=$this->POST['mulai_year'].'-'.$this->POST['mulai_mon'].'-'.$this->POST['mulai_day'];
    $selesai=$this->POST['selesai_year'].'-'.$this->POST['selesai_mon'].'-'.$this->POST['selesai_day'];
    $sk_tgl=$this->POST['sk_tgl_year'].'-'.$this->POST['sk_tgl_mon'].'-'.$this->POST['sk_tgl_day'];
    
    $array=array(
				'pegKode'=>$this->POST['pegKode'],
				'ref_jab'=>$this->POST['ref_jab'],
				'gol'=>$this->POST['golongan_ref'],
				'mulai'=>$mulai,
				'selesai'=>$selesai,
				'pejabat'=>$this->POST['sk'],
				'nomor'=>$this->POST['sk_no'],
				'tanggal'=>$sk_tgl,
				'status'=>$this->POST['status'],
				'upload'=>$nama_file,
				'komp'=>$this->POST['komp'],
				'sksmaks'=>$this->POST['sksMaks'],
				'ak'=>$this->POST['ak'],
				'id'=>$this->decId
			);

    $result = $this->Obj->Update($array);
	
    if ($result){
	  if($array['status']==$this->activeStatus){
	     $stat_update=$this->Obj->UpdateStatus($this->inactiveStatus,$this->decId,$this->POST['pegKode']);
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
			 
           if($this->POST['status']==$this->activeStatus){
             $rs_add_2 = true; //$this->AddDataMutasi($this->POST['pegKode'],"input");
  	         if($rs_add_2 == true){
                Messenger::Instance()->Send('mutasi_jabatan_fungsional','JabatanFungsional','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
                return $this->pageView;
             }else{
                Messenger::Instance()->Send('mutasi_jabatan_fungsional','JabatanFungsional','view','html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
                return $this->pageView;
             }
           }else{
             Messenger::Instance()->Send('mutasi_jabatan_fungsional','JabatanFungsional','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
             return $this->pageView;
           }
           
        }else{
           Messenger::Instance()->Send('mutasi_jabatan_fungsional','JabatanFungsional','view','html', array($this->POST,$this->msgAddFail,$this->cssDone),Messenger::NextRequest);
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
	         
           if($this->POST['status']==$this->activeStatus){
             $rs_update_2 = true;//$this->AddDataMutasi($this->POST['pegKode'],"update");
  	         if($rs_update_2 == true){
                Messenger::Instance()->Send('mutasi_jabatan_fungsional','JabatanFungsional','view','html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
                return $this->pageView;
             }else{
                Messenger::Instance()->Send('mutasi_jabatan_fungsional','JabatanFungsional','view','html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
                return $this->pageView;
             }
           }else{
             Messenger::Instance()->Send('mutasi_jabatan_fungsional','JabatanFungsional','view','html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
             return $this->pageView;
           }
           
        }else{
           Messenger::Instance()->Send('mutasi_jabatan_fungsional','JabatanFungsional','view','html', array($this->POST,$this->msgUpdateFail,$this->cssDone),Messenger::NextRequest);
           return $this->pageView;
        }
      }
      //return $urlRedirect;
   }
   
   function Delete(){
      //print_r($this->_POST);exit();
      $result = $this->Obj->Delete($this->POST);
      if ($result == true){
        $msg = array(1=>$this->msgDeleteSuccess, $this->cssDone);
      }else {
        $msg = array(1=>$this->msgDeleteFail, $this->cssFail);
      }
      Messenger::Instance()->Send('mutasi_jabatan_fungsional','JabatanFungsional','view','html', $msg, Messenger::NextRequest);
      return $this->pageBack.$_GET['id'];
   }
   
   function dumper($print){
	   echo"<pre>";print_r($print);echo"</pre>";
	}
}

?>
