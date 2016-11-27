<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_saudara_kandung/business/data_saudara_kandung.class.php';

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
    $this->Obj = new SaudaraKandung();
	  $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	  $this->pageView = Dispatcher::Instance()->GetUrl('data_saudara_kandung', 'dataSaudaraKandung', 'view', 'html');
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
      $return .= "&dataId=".$this->POST['idPeg'];
      return $return;
    } 
    if (trim($this->POST['nama']) == ''){
      $error = $this->msgReqDataEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('data_saudara_kandung', 'dataSaudaraKandung', 'view', 'html', $msg, Messenger::NextRequest);
      
      $return = $this->pageView;
      if (($this->POST['op']=="edit") and (isset($this->POST['idSdr']))){ 
      $return .= "&dataId2=".$this->POST['idSdr'];
      }
      if (isset($this->POST['idPeg'])){ 
      $return .= "&dataId=".$this->POST['idPeg'];
      }
      return $return;
    }
    return true;
  }
  
  function AddDatsdr(){
    $a=$this->POST['tgl_lahir_year'].'-'.$this->POST['tgl_lahir_mon'].'-'.$this->POST['tgl_lahir_day'];
 
    $array=array('id'=>$this->POST['idPeg'],'nama'=>$this->POST['nama'],'jenkel'=>$this->POST['jenkel'],
      'tmpt_lahir'=>$this->POST['tmpt_lahir'],'tgl_lahir'=>$a,'kerja'=>$this->POST['kerja'],
	  'ket'=>$this->POST['ket'],'educ'=>$this->POST['educ'],'telp'=>$this->POST['telp'],'mati'=>$this->POST['mati']);
  
    $result = $this->Obj->Add($array);
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function UpdateDatsdr(){ 
    $a=$this->POST['tgl_lahir_year'].'-'.$this->POST['tgl_lahir_mon'].'-'.$this->POST['tgl_lahir_day'];
    
    $array=array('nama'=>$this->POST['nama'],'jenkel'=>$this->POST['jenkel'],
      'tmpt_lahir'=>$this->POST['tmpt_lahir'],'tgl_lahir'=>$a,'kerja'=>$this->POST['kerja'],
      'ket'=>$this->POST['ket'],'educ'=>$this->POST['educ'],'telp'=>$this->POST['telp'],'mati'=>$this->POST['mati'],'id'=>$this->POST['idSdr']);
  
    $result = $this->Obj->Update($array);
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function InputDatsdr(){
      $check = $this->Check();
      if ($check !== true) return $check;
      if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'add')) {
        $rs_add = $this->AddDatsdr();
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPeg'];
        if($rs_add == true){
           Messenger::Instance()->Send('data_saudara_kandung', 'dataSaudaraKandung', 'view', 'html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('data_saudara_kandung', 'dataSaudaraKandung', 'view', 'html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
        
      }else if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
        $rs_update = $this->UpdateDatsdr();
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPeg'];
        if($rs_update == true){
           Messenger::Instance()->Send('data_saudara_kandung', 'dataSaudaraKandung', 'view', 'html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('data_saudara_kandung', 'dataSaudaraKandung', 'view', 'html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
      }
   }
   
   function Delete(){
    $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
    if($deleteData == true) {
			Messenger::Instance()->Send('data_saudara_kandung', 'dataSaudaraKandung', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('data_saudara_kandung', 'dataSaudaraKandung', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
    $return = $this->pageView;
    $return .= "&dataId=".$this->decId2;
		return $return;
   }
}

?>