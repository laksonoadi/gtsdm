<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/gaji_pokok/business/gaji_pokok.class.php';

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
    $this->Obj = new GajiPokok();
	  $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	  $this->pageView = Dispatcher::Instance()->GetUrl('gaji_pokok', 'gajiPokok', 'view', 'html');
    $this->decId = $_GET['dataId2']->Integer()->Raw();
    $this->decId2 = $_GET['dataId']->Integer()->Raw();
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
      $return .= "&dataId=".$this->POST['idPang'];
      return $return;
    } 
    if ((trim($this->POST['masa']) == '') or (trim($this->POST['komp']) == '')){
      $error = $this->msgReqDataEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('gaji_pokok', 'gajiPokok', 'view', 'html', $msg, Messenger::NextRequest);
      
      $return = $this->pageView;
      /*if (isset($_GET['dataId2'])){ 
      $return .= "&dataId2=".$this->decId;
      }*/
      if (($this->POST['op']=="edit") and (isset($this->POST['idGapok']))){ 
      $return .= "&dataId2=".$this->POST['idGapok'];
      }
      if (isset($this->POST['idPang'])){ 
      $return .= "&dataId=".$this->POST['idPang'];
      }
      return $return;
    }
    return true;
  }
  
  function AddDatgapok(){
    $array=array('id'=>$this->POST['idPang'],'masa'=>$this->POST['masa'],'komp'=>$this->POST['komp']);
  
    $result = $this->Obj->Add($array);
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function UpdateDatgapok(){ 
    $array=array('masa'=>$this->POST['masa'],'komp'=>$this->POST['komp'],'id'=>$this->POST['idPang'],'id2'=>$this->POST['idGapok']);
  
    $result = $this->Obj->Update($array);
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function InputDatgapok(){
      $check = $this->Check();
      if ($check !== true) return $check;
      if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'add')) {
        $rs_add = $this->AddDatgapok();
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPang'];
        if($rs_add == true){
           Messenger::Instance()->Send('gaji_pokok', 'gajiPokok', 'view', 'html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('gaji_pokok', 'gajiPokok', 'view', 'html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
        
      }else if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
        $rs_update = $this->UpdateDatgapok();
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPang'];
        if($rs_update == true){
           Messenger::Instance()->Send('gaji_pokok', 'gajiPokok', 'view', 'html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('gaji_pokok', 'gajiPokok', 'view', 'html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
      }
   }
   
   function Delete(){
    $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
    if($deleteData == true) {
			Messenger::Instance()->Send('gaji_pokok', 'gajiPokok', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('gaji_pokok', 'gajiPokok', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
    $return = $this->pageView;
    $return .= "&dataId=".$this->decId2;
		return $return;
   }
}

?>