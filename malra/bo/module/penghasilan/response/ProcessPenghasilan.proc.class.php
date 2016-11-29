<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/penghasilan/business/penghasilan.class.php';

class Process
{
   var $_POST;
	var $Obj;
	var $user;
	var $pageView;
	var $pageInput;
	var $pghsId;
	var $move;
	var $cssDone = "notebox-done";
	var $cssAlert = "notebox-alert";
	var $cssFail = "notebox-warning";

	var $return;
	var $decId;
	var $encId;
	
	function __construct() {
	   $this->Obj = new Penghasilan;
	   $this->_POST = $_REQUEST->AsArray();
	   $this->decId = $_GET['id']->Integer()->Raw();
	   $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	   $this->encId = $this->decId;
	   $this->pghsId=$_GET['phslId']->Integer()->Raw();
	   $this->move=$_GET['move']->Raw();
	   $this->pageView = Dispatcher::Instance()->GetUrl('penghasilan', 'penghasilan', 'view', 'html');
	   $this->pageInput = Dispatcher::Instance()->GetUrl('penghasilan', 'penghasilan', 'view', 'html');
	   $this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
	   if ($this->lang=='eng'){
	     $this->msgAddSuccess='Data added successfully';$this->msgAddFail='Failed to add data';
	     $this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Failed to update data';
	     $this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Failed to delete data';
	     $this->msgMoveSuccess='Data moved successfully';$this->msgMoveFail='Failed to move data';
	     $this->msgReqDataEmpty='All field marked with * must be filled';
      }else{
        $this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
        $this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
        $this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
        $this->msgMoveSuccess='Perubahan letak data berhasil dilakukan';$this->msgMoveFail='Perubahan letak data gagal dilakukan';
        $this->msgReqDataEmpty='Semua data bertanda * harus diisi';
      }
	}
	
	function Check ()
   {
      if (isset($this->_POST['btnbalik'])) return $this->pageView;
	  if (trim($this->_POST['nama']) == ''){
         $error = $this->msgReqDataEmpty;
	  }
	  
	  if (isset($error))
      {
         $msg = array($this->_POST, $error, $this->cssAlert);
         Messenger::Instance()->Send('penghasilan', 'penghasilan', 'view', 'html', $msg, Messenger::NextRequest);
         
         $return = $this->pageInput;
         if (isset($_GET['id'])){ 
		    $return .= "&id=".$this->decId;
		 }
         return $return;
      }
	  return true;
   }
   
   function Update()
   {
      $check = $this->Check();
	  if ($check !== true){ 
	     return $check;
	  }
	  
	  $data=array('nama'=>$this->_POST['nama'],'userId'=>$this->user,'id'=>$this->decId, 'order'=>$this->_POST['order']);
	  $result = $this->Obj->Update($data);
	  if ($result){
         $msg = array(1=>$this->msgUpdateSuccess, $this->cssDone);
	  }else 
	  {
	     $msg = array(1=>$this->msgUpdateFail, $this->cssFail);
	   }
	   Messenger::Instance()->Send('penghasilan', 'penghasilan', 'view', 'html', $msg, Messenger::NextRequest);
	   return $this->pageView;
   }
   
   function Add()
   {
     $check = $this->Check();
      if ($check !== true) return $check;
	  $data=array('nama'=>$this->_POST['nama'],'userId'=>$this->user,'order'=>$this->_POST['order']);//print_r($data);exit();
	  $result = $this->Obj->Add($data);
	  if ($result){
         $msg = array(1=>$this->msgAddSuccess, $this->cssDone);
	  }else 
	  {
	     $msg = array(1=>$this->msgAddFail, $this->cssFail);
	   }
	   Messenger::Instance()->Send('penghasilan', 'penghasilan', 'view', 'html', $msg, Messenger::NextRequest);
	   return $this->pageView;
   }
   
   function Delete()
   {
      //print_r($this->_POST);exit();
      $result = $this->Obj->Delete($this->_POST);
	  if ($result){
         $msg = array(1=>$this->msgDeleteSuccess, $this->cssDone);
	  }else 
	  {
	     $msg = array(1=>$this->msgDeleteFail, $this->cssFail);
	   }
	   Messenger::Instance()->Send('penghasilan', 'penghasilan', 'view', 'html', $msg, Messenger::NextRequest);
	   return $this->pageView;
   }
   
   function MoveOrder()
   {
      $this->move = $this->move== 'up'?'up':'down';//print_r(array($this->pghsId, $this->move));exit();
	  $result = $this->Obj->MoveOrder($this->pghsId, $this->move);
	  if ($result){
         $msg = array(1=>$this->msgMoveSuccess, $this->cssDone);
	  }else 
	  {
	     $msg = array(1=>$this->msgMoveFail, $this->cssFail);
	   }
	   Messenger::Instance()->Send('penghasilan', 'penghasilan', 'view', 'html', $msg, Messenger::NextRequest);
	   return $this->pageView;
   }
}

?>