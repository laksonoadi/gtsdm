<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_pak/business/mutasi_pak.class.php';

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
      $this->Obj = new MutasiPak();
      $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
      $this->decId = $_POST['dataId']->Integer()->Raw();
      $this->profilId = $_POST['pegId']->Integer()->Raw();
      $this->delId = $_GET['id']->Integer()->Raw();
      $this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
      if ($this->lang=='eng'){
       	$this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       	$this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       	$this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
       	$this->msgReqDataEmpty='All field marked with * and date field must be filled';
    		$this->error='Data incomplete!';
    		$this->activeStatus='Active';$this->inactiveStatus='Inactive';
      }else{
       	$this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       	$this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       	$this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
       	$this->msgReqDataEmpty='Semua data bertanda * dan data tanggal harus diisi';
    		$this->error='Data tidak lengkap!';
    		$this->activeStatus='Aktif';$this->inactiveStatus='Tidak Aktif';
      }
      
      if($ret == "html"){
         $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_pak','MutasiPak','view','html').'&id='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_pak','MutasiPak','view','html').'&id='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_pak','MutasiPak','view','html').'&id='.$this->delId;
        }else{
         $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_pak','MutasiPak','view','html',true).'&id='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_pak','MutasiPak','view','html',true).'&id='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_pak','MutasiPak','view','html').'&id='.$this->delId;
        }
      //print_r($this->pageView);exit();
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray(); 
      //echo "<pre>"; print_r($this->POST); echo "</pre>";
      //$this->FILES = $param2->AsArray();
  }
  
  function Check (){
    if (isset($this->POST['btnbatal'])) {
      return $this->pageView;
    }
    
    if (isset($this->error)){
      $msg = array($this->POST, $this->error, $this->cssAlert);
      Messenger::Instance()->Send('mutasi_pak','MutasiPak','view','html', $msg, Messenger::NextRequest);
      
      $return = $this->pageInput;
      if (isset($_GET['dataId'])){ 
      $return .= "&dataId=".$this->decId."&id=".$this->profilId;
      }
      return $return;
    }
    return true;
  }
  
  function AddData(){
    
    $mulai=$this->POST['mulai_year'].'-'.$this->POST['mulai_mon'].'-'.$this->POST['mulai_day'];
    $selesai=$this->POST['selesai_year'].'-'.$this->POST['selesai_mon'].'-'.$this->POST['selesai_day'];
    $tgl_penetapan=$this->POST['tgl_penetapan_year'].'-'.$this->POST['tgl_penetapan_mon'].'-'.$this->POST['tgl_penetapan_day'];
    
    $array=array(
				'pegId'=>$this->POST['pegId'],
				'no_pak'=>$this->POST['no_pak'],
				'tgl_penetapan'=>$tgl_penetapan,
				'pejabat'=>$this->POST['pejabat'],
				'mulai'=>$mulai,
				'selesai'=>$selesai,
				'diangkat'=>$this->POST['jabatan']
		);
    //$this->dumper($array);exit();
    $this->Obj->StartTrans();
    $result = $this->Obj->Add($array);
    if ($result===true){
    	  $getId=$this->Obj->GetMaxId();
    	  $jumlah_unsur_utama=$this->POST['utama_jumlah'];
    	  $jumlah_unsur_penunjang=$this->POST['penunjang_jumlah'];
    	   
    	  //Input Unsur Utama
    	  if ($jumlah_unsur_utama==0) $result1=true;
    	  for ($i=1; $i<=$jumlah_unsur_utama; $i++){
    	     $idref=$this->POST['uidref_'.$i];
    	     $lama=$this->POST['ulama_'.$i];
    	     $baru=$this->POST['ubaru_'.$i];
    	     $digunakan=$this->POST['udigunakan_'.$i];
    	     $lebihan=$this->POST['ulebihan_'.$i]; 
    	     $result1 = $this->Obj->AddUnsur(array($getId,$idref,$lama,$baru,$digunakan,$lebihan));
    	     if ($result1===false) break;
        }
        
        //Input Unsur Penunjang
        if ($jumlah_unsur_penunjang==0) $result2=true;
    	  for ($i=1; $i<=$jumlah_unsur_penunjang; $i++){
    	     $idref=$this->POST['pidref_'.$i];
    	     $lama=$this->POST['plama_'.$i];
    	     $baru=$this->POST['pbaru_'.$i];
    	     $digunakan=$this->POST['pdigunakan_'.$i];
    	     $lebihan=$this->POST['plebihan_'.$i];
    	     $result2 = $this->Obj->AddUnsur(array($getId,$idref,$lama,$baru,$digunakan,$lebihan));
    	     if ($result2===false) break;
        }
    }
    
    $result=$result && $result1 && $result2;
    $this->Obj->EndTrans($result);
    
	  if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function UpdateData(){ 
     
	  $mulai=$this->POST['mulai_year'].'-'.$this->POST['mulai_mon'].'-'.$this->POST['mulai_day'];
    $selesai=$this->POST['selesai_year'].'-'.$this->POST['selesai_mon'].'-'.$this->POST['selesai_day'];
    $tgl_penetapan=$this->POST['tgl_penetapan_year'].'-'.$this->POST['tgl_penetapan_mon'].'-'.$this->POST['tgl_penetapan_day'];
    
    $array=array(
				'pegId'=>$this->POST['pegId'],
				'no_pak'=>$this->POST['no_pak'],
				'tgl_penetapan'=>$tgl_penetapan,
				'pejabat'=>$this->POST['pejabat'],
				'mulai'=>$mulai,
				'selesai'=>$selesai,
				'diangkat'=>$this->POST['jabatan'],
				'id'=>$this->POST['dataId']
		);
		
	  $this->Obj->StartTrans();
    $result = $this->Obj->Update($array);
    if ($result===true){
    	  $getId=$this->POST['dataId'];
    	  $jumlah_unsur_utama=$this->POST['utama_jumlah'];
    	  $jumlah_unsur_penunjang=$this->POST['penunjang_jumlah'];
    	   
    	  //Input Unsur Utama
    	  if ($jumlah_unsur_utama==0) $result1=true;
    	  for ($i=1; $i<=$jumlah_unsur_utama; $i++){
    	     $id=$this->POST['uid_'.$i];
    	     $idref=$this->POST['uidref_'.$i];
    	     $lama=$this->POST['ulama_'.$i];
    	     $baru=$this->POST['ubaru_'.$i];
    	     $digunakan=$this->POST['udigunakan_'.$i];
    	     $lebihan=$this->POST['ulebihan_'.$i]; 
    	     $result1 = $this->Obj->UpdateUnsur(array($getId,$idref,$lama,$baru,$digunakan,$lebihan,$id));
    	     if ($result1===false) break;
        }
        
        //Input Unsur Penunjang
        if ($jumlah_unsur_penunjang==0) $result2=true;
    	  for ($i=1; $i<=$jumlah_unsur_penunjang; $i++){
    	     $id=$this->POST['pid_'.$i];
    	     $idref=$this->POST['pidref_'.$i];
    	     $lama=$this->POST['plama_'.$i];
    	     $baru=$this->POST['pbaru_'.$i];
    	     $digunakan=$this->POST['pdigunakan_'.$i];
    	     $lebihan=$this->POST['plebihan_'.$i];
    	     $result2 = $this->Obj->UpdateUnsur(array($getId,$idref,$lama,$baru,$digunakan,$lebihan,$id));
    	     if ($result2===false) break;
        }
    }
    
    $result=$result && $result1 && $result2;
    $this->Obj->EndTrans($result);
    
	  if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function InputData(){
      //$check = $this->Check();
      //if ($check !== true) return $check;
      if ((isset($this->POST['btnsimpan'])) && empty($this->decId)) {
        $rs_add = $this->AddData();
        if($rs_add== true){
            Messenger::Instance()->Send('mutasi_pak','MutasiPak','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
            return $this->pageView;
        }else{
            Messenger::Instance()->Send('mutasi_pak','MutasiPak','view','html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
            return $this->pageView;
        }
     }else if ((isset($this->POST['btnsimpan'])) && !empty($this->decId)) {
        $rs_update = $this->UpdateData();
        if($rs_update== true){
            Messenger::Instance()->Send('mutasi_pak','MutasiPak','view','html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
            return $this->pageView;
        }else{
            Messenger::Instance()->Send('mutasi_pak','MutasiPak','view','html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
            return $this->pageView;
        }
      }
      //return $urlRedirect;
   }
   
   function Delete(){
   //print_r($this->POST);exit();
   $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
   if($deleteData == true) {
			Messenger::Instance()->Send('mutasi_pak', 'MutasiPak', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('mutasi_pak', 'MutasiPak', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
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
