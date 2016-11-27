<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/persetujuan_lembur/business/lembur.class.php';

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
    $this->Obj = new Lembur();
	  $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	  $this->pageView = Dispatcher::Instance()->GetUrl('persetujuan_lembur', 'appDataLembur', 'view', 'html');
    $this->pageInput = Dispatcher::Instance()->GetUrl('persetujuan_lembur', 'inputAppLembur', 'view', 'html');
    $this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
	   if ($this->lang=='eng'){
       $this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       $this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       $this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
       $this->msgReqDataEmpty='All field marked with * and date field must be filled';
     }else{
       $this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       $this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       $this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
       $this->msgReqDataEmpty='Semua data bertanda * dan data tanggal harus diisi';
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
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('persetujuan_lembur', 'inputAppLembur', 'view', 'html', $msg, Messenger::NextRequest);
      
      $return = $this->pageInput;
      if (($this->POST['op']=="edit") and (isset($this->POST['idLembur']))){ 
      $return .= "&dataId2=".$this->POST['idLembur'];
      }
      if (isset($this->POST['idPeg'])){ 
      $return .= "&dataId=".$this->POST['idPeg'];
      }
      return $return;
    }
    return true;
  }
  
  function AddDataLembur(){
    $tgl_stat=$this->POST['tglstat_year'].'-'.$this->POST['tglstat_mon'].'-'.$this->POST['tglstat_day'];    
    $array=array('status'=>$this->POST['status'], 'tgl_stat'=>$tgl_stat, 'id'=>$this->POST['idLembur']);
  
    $result = $this->Obj->Update($array);
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function InputDataLembur(){
      $check = $this->Check();
      if ($check !== true) return $check;
      if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
        $rs_add = $this->AddDataLembur();
        $return = $this->pageView;
        if($rs_add == true){
           Messenger::Instance()->Send('persetujuan_lembur', 'appDataLembur', 'view', 'html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('persetujuan_lembur', 'appDataLembur', 'view', 'html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
        
      }
   }
   
   function Delete(){
    $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
    if($deleteData == true) {
			Messenger::Instance()->Send('persetujuan_lembur', 'appDataLembur', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('persetujuan_lembur', 'appDataLembur', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
    $return = $this->pageView;
    return $return;
   }
}

?>