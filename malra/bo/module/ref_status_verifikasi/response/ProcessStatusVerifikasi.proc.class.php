<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/ref_status_verifikasi/business/status_verifikasi.class.php';

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
		$this->Obj = new StatusVerifikasi;
		$this->_POST = $_REQUEST->AsArray();
		$this->decId = $_GET['id']->Integer()->Raw();
		$this->pegId = $_POST['pegId'];
		$this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
		$this->encId = $this->decId;
		$this->pageView = Dispatcher::Instance()->GetUrl('ref_status_verifikasi', 'statusVerifikasi', 'view', 'html') . '&pegId=' . $this->pegId;
		$this->pageInput = Dispatcher::Instance()->GetUrl('ref_status_verifikasi', 'statusVerifikasi', 'view', 'html');
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
		if (trim($this->_POST['verstatName']) == ''){
			$error = $this->msgReqDataEmpty;
		}
    
		if (isset($error)){
			$msg = array($this->_POST, $error, $this->cssAlert);
			Messenger::Instance()->Send('ref_status_verifikasi', 'statusVerifikasi', 'view', 'html', $msg, Messenger::NextRequest);
    
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
		
		$this->_POST['verstatIsApproved'] = (isset($this->_POST['verstatIsApproved'])) ? 1 : 0;
		$data=array(
				'verstatName'=>$this->_POST['verstatName'],
				'verstatIsApproved'=>$this->_POST['verstatIsApproved'],
				'verstatIcon'=>$this->_POST['verstatIcon'],
				'verstatId'=>$this->encId
				);
		$result = $this->Obj->Update($data);
		if($result){
			$msg = array(1=>$this->msgUpdateSuccess, $this->cssDone);
		}else{
			$msg = array(1=>$this->msgUpdateFail, $this->cssFail);
		}
		Messenger::Instance()->Send('ref_status_verifikasi', 'statusVerifikasi', 'view', 'html', $msg, Messenger::NextRequest);
		return $this->pageView;
	}
  
	function Add(){
		$check = $this->Check();
    
		$this->_POST['verstatIsApproved'] = (isset($this->_POST['verstatIsApproved'])) ? 1 : 0;
    		
		if ($check !== true) return $check;
    
		$data=array(
				'verstatName'=>$this->_POST['verstatName'],
				'verstatIsApproved'=>$this->_POST['verstatIsApproved'],
				'verstatIcon'=>$this->_POST['verstatIcon']
				);
		$result = $this->Obj->Add($data);
    
		if($result){
			$msg = array(1=>$this->msgAddSuccess, $this->cssDone);
		}else{
			$msg = array(1=>$this->msgAddFail, $this->cssFail);
		}
    
		Messenger::Instance()->Send('ref_status_verifikasi', 'statusVerifikasi', 'view', 'html', $msg, Messenger::NextRequest);
		return $this->pageView;
	}
  
	function Delete(){
		$result = $this->Obj->Delete($this->_POST);
		if ($result){
			$msg = array(1=>$this->msgDeleteSuccess, $this->cssDone);
		}else{
			$msg = array(1=>$this->msgDeleteFail, $this->cssFail);
		}
		Messenger::Instance()->Send('ref_status_verifikasi', 'statusVerifikasi', 'view', 'html', $msg, Messenger::NextRequest);
		return $this->pageView;
	}
}

?>