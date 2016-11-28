<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/gelombang/business/gelombang.class.php';

class Process
{
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
	   $this->Obj = new Gelombang;
	   $this->_POST = $_REQUEST->AsArray();
	   $this->decId = $_GET['id']->Integer()->Raw();
	   $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	   $this->encId = $this->decId;
	   $this->pageView = Dispatcher::Instance()->GetUrl('gelombang','gelombang','view','html');
	   $this->pageInput = Dispatcher::Instance()->GetUrl('gelombang','gelombang','view','html');
	}
	
	function Check ()
   {
      if (isset($this->_POST['btnbalik'])) return $this->pageView;
	  if (trim($this->_POST['nama']) == ''){
         $error = 'Tidak ada data yang dimasukan!';
	  }
	  
	  if (isset($error))
      {
         $msg = array($this->_POST, $error, $this->cssAlert);
         Messenger::Instance()->Send('gelombang','gelombang','view','html', $msg, Messenger::NextRequest);
         
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
	  
	  $data=array('nama'=>$this->_POST['nama'],'userId'=>$this->user,'id'=>$this->decId);
	  $result = $this->Obj->Update($data);
	  if ($result){
         $msg = array(1=>'Perubahan Data Berhasil Dilakukan.', $this->cssDone);
	  }else 
	  {
	     $msg = array(1=>'Tidak Berhasil Mengubah Data!', $this->cssFail);
	   }
	   Messenger::Instance()->Send('gelombang','gelombang','view','html', $msg, Messenger::NextRequest);
	   return $this->pageView;
   }
   
   function Add()
   {
     $check = $this->Check();
      if ($check !== true) return $check;
	  $data=array('nama'=>$this->_POST['nama'],'userId'=>$this->user);//print_r($data);
	  $result = $this->Obj->Add($data);
	  if ($result){
         $msg = array(1=>'Penambahan Data Berhasil Dilakukan.', $this->cssDone);
	  }else 
	  {
	     $msg = array(1=>'Tidak Berhasil Menambah Data!', $this->cssFail);
	   }
	   Messenger::Instance()->Send('gelombang','gelombang','view','html', $msg, Messenger::NextRequest);
	   return $this->pageView;
   }
   
   function Delete()
   {
      //print_r($this->_POST);exit();
      $result = $this->Obj->Delete($this->_POST);
	  if ($result){
         $msg = array(1=>'Penghapusan Data Berhasil Dilakukan.', $this->cssDone);
	  }else 
	  {
	     $msg = array(1=>'Tidak Berhasil Menghapus Data!', $this->cssFail);
	   }
	   Messenger::Instance()->Send('gelombang','gelombang','view','html', $msg, Messenger::NextRequest);
	   return $this->pageView;
   }
}

?>