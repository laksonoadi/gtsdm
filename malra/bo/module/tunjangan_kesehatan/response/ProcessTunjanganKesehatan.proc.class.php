<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/tunjangan_kesehatan/business/tunjangan_kesehatan.class.php';

class Process
{
   var $POST;
   var $user;
   var $Obj;
   var $cssAlert = "notebox-alert";
   var $cssDone = "notebox-done";
   var $cssFail = "notebox-warning";
   var $pageInput;
   var $decId;
   var $decId2;
   var $pageView;
   
   function __construct() {
    $this->Obj = new TunjanganKesehatan();
	  $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	  $this->pageView = Dispatcher::Instance()->GetUrl('tunjangan_kesehatan', 'tunjanganKesehatan', 'view', 'html');
    $this->pageInput = Dispatcher::Instance()->GetUrl('tunjangan_kesehatan', 'inputTunjanganKesehatan', 'view', 'html');
    $this->decId = $_GET['dataId']->Integer()->Raw();
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
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray();
  }
  
  function Check (){
    if (isset($this->POST['btnbatal'])){
      $return = $this->pageView;
      return $return;
    } 
    
    if (trim($this->POST['jenis']) == ''){
      $error = $this->msgReqDataEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('tunjangan_kesehatan', 'inputTunjanganKesehatan', 'view', 'html', $msg, Messenger::NextRequest);
      
      $return = $this->pageInput;
      if (isset($this->POST['tunId']) and $this->POST['op']=="edit"){ 
      $return .= "&dataId=".$this->POST['tunId'];
      }
      return $return;
    }
    return true;
  }
  
  function AddDattun(){
    $array=array('jenis'=>$this->POST['jenis'],'nikah'=>$this->POST['nikah'],'maks'=>$this->POST['maks'],
      'klaim'=>$this->POST['klaim'],'periode'=>$this->POST['periode'],'pla_uang'=>$this->POST['pla_uang'],
      'pla_persen'=>$this->POST['pla_persen']);
  
    $result = $this->Obj->Add($array);
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function UpdateDattun(){ 
    $array=array('jenis'=>$this->POST['jenis'],'nikah'=>$this->POST['nikah'],'maks'=>$this->POST['maks'],
      'klaim'=>$this->POST['klaim'],'periode'=>$this->POST['periode'],'pla_uang'=>$this->POST['pla_uang'],
      'pla_persen'=>$this->POST['pla_persen'],'id'=>$this->POST['tunId']);
  
    $result = $this->Obj->Update($array);
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function InputDattun(){
      $check = $this->Check();
      if ($check !== true) return $check;
      if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'add')) {
        $rs_add = $this->AddDattun();
        $return = $this->pageView;
        if($rs_add == true){
           Messenger::Instance()->Send('tunjangan_kesehatan', 'tunjanganKesehatan', 'view', 'html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('tunjangan_kesehatan', 'tunjanganKesehatan', 'view', 'html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
        
      }else if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
        $rs_update = $this->UpdateDattun();
        $return = $this->pageView;
        if($rs_update == true){
           Messenger::Instance()->Send('tunjangan_kesehatan', 'tunjanganKesehatan', 'view', 'html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('tunjangan_kesehatan', 'tunjanganKesehatan', 'view', 'html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
      }
   }
   
   function Delete(){
    $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
    if($deleteData == true) {
			Messenger::Instance()->Send('tunjangan_kesehatan', 'tunjanganKesehatan', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('tunjangan_kesehatan', 'tunjanganKesehatan', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
    $return = $this->pageView;
		return $return;
   }
}

?>