<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/balance_benefit/business/balance_benefit.class.php';

class Process{

  var $_POST;
  var $Obj;
  var $user;
  var $pageView;
  var $pageInput;
  
  var $cssDone = "notebox-done";
  var $cssAlert = "notebox-alert";
  var $cssFail = "notebox-warning";
  
  var $return;
  var $decId;
  var $encId;
  
  function __construct() {
    $this->Obj = new BalanceBenefit;
    $this->_POST = $_REQUEST->AsArray();
    $this->decId = $_GET['id']->Integer()->Raw();
    $this->pegId = $_POST['pegId'];
    $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
    $this->encId = $this->decId;
    $this->awal = $this->POST['balancebenefitAwal_year'].'-'.$this->POST['balancebenefitAwal_mon'].'-'.$this->POST['balancebenefitAwal_day'];
    $this->akhir = $this->POST['balancebenefitAkhir_year'].'-'.$this->POST['balancebenefitAkhir_mon'].'-'.$this->POST['balancebenefitAkhir_day'];
    $this->pageView = Dispatcher::Instance()->GetUrl('balance_benefit', 'balanceBenefit', 'view', 'html') . '&pegId=' . $this->pegId;
    $this->pageInput = Dispatcher::Instance()->GetUrl('balance_benefit', 'balanceBenefit', 'view', 'html');
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
  
  function Check (){
    if (isset($this->_POST['btnbalik'])) return $this->pageView;
    if (trim($this->_POST['total']) == ''){
      $error = $this->msgReqDataEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->_POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('balance_benefit', 'balanceBenefit', 'view', 'html', $msg, Messenger::NextRequest);
    
      $return = $this->pageInput;
      if (isset($_GET['id'])){ 
         $return .= "&id=".$this->decId;
      }
      return $return;
    }
    return true;
  }
  
  function Update(){
    $check = $this->Check();
    if ($check !== true){ 
      return $check;
    }
  
    $awal=$this->_POST['balancebenefitAwal_year'].'-'.$this->_POST['balancebenefitAwal_mon'].'-'.$this->_POST['balancebenefitAwal_day'];
    $akhir=$this->_POST['balancebenefitAkhir_year'].'-'.$this->_POST['balancebenefitAkhir_mon'].'-'.$this->_POST['balancebenefitAkhir_day'];
    
    $status = (isset($this->_POST['status'])) ? $this->_POST['status'] : 'Expired';
    if(trim($status)=='')	  
	     $is_aktif='Expired';
			
    $data=array('pegId'=>$this->_POST['pegId'],'awal'=>$awal,'akhir'=>$akhir,'total'=>$this->_POST['total'],'status'=>$status,'id'=>$this->encId);

    $result = $this->Obj->Update($data);
    if($result){
      $msg = array(1=>$this->msgUpdateSuccess, $this->cssDone);
    }else{
      $msg = array(1=>$this->msgUpdateFail, $this->cssFail);
    }
    Messenger::Instance()->Send('balance_benefit', 'balanceBenefit', 'view', 'html', $msg, Messenger::NextRequest);
    return $this->pageView;
  }
  
  function Add(){
    $check = $this->Check();
    $awal=$this->_POST['balancebenefitAwal_year'].'-'.$this->_POST['balancebenefitAwal_mon'].'-'.$this->_POST['balancebenefitAwal_day'];
    $akhir=$this->_POST['balancebenefitAkhir_year'].'-'.$this->_POST['balancebenefitAkhir_mon'].'-'.$this->_POST['balancebenefitAkhir_day'];
    
    if (!isset($this->_POST['status'])) {
		  $this->_POST['status']='Expired';
		} else {
      $this->_POST['status']='Active';
    }
    
    if(trim($this->_POST['status'])==''){  
	     $this->_POST['status']='Expired';
    }
    		
    if ($check !== true) return $check;
    
    $data=array('pegId'=>$this->_POST['pegId'],'awal'=>$awal,'akhir'=>$akhir,'total'=>$this->_POST['total'],'status'=>$this->_POST['status']);
    //print_r($data);exit();
    $result = $this->Obj->Add($data);
    
    if($result){
      $msg = array(1=>$this->msgAddSuccess, $this->cssDone);
    }else{
      $msg = array(1=>$this->msgAddFail, $this->cssFail);
    }
    
    Messenger::Instance()->Send('balance_benefit', 'balanceBenefit', 'view', 'html', $msg, Messenger::NextRequest);
    return $this->pageView;
  }
  
  function Delete(){
    $result = $this->Obj->Delete($this->_POST);
    if ($result){
      $msg = array(1=>$this->msgDeleteSuccess, $this->cssDone);
    }else{
      $msg = array(1=>$this->msgDeleteFail, $this->cssFail);
    }
    Messenger::Instance()->Send('balance_benefit', 'balanceBenefit', 'view', 'html', $msg, Messenger::NextRequest);
    return $this->pageView;
  }
}

?>