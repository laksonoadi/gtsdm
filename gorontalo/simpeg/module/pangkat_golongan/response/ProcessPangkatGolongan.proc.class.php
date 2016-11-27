<?php
require_once GTFWConfiguration::GetValue('applicaton','docroot').'module/pangkat_golongan/business/mysqlt/pangkat_golongan.class.php';

class ProcessPangkatGolongan {
   
   var $_POST;
   var $Obj;
   var $pageView;
   var $pageInput;
   
   var $cssDone = "notebox-done";
   var $cssFail = "notebox-warning";
   var $cssAlert = "notebox-alert";
   
   function __construct() {
      $this->Obj = new PangkatGolongan();
      $this->_POST = $_POST->AsArray();
      $this->pageView = Dispatcher::Instance()->GetUrl('pangkat_golongan','PangkatGolongan','view','html');
      $this->pageInput = Dispatcher::Instance()->GetUrl('pangkat_golongan','InputPangkatGolongan','view','html');
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
   
    function Check(){
      if (isset($_POST['btnsimpan'])){
         if (trim($this->_POST['pangkat'])=="" || trim($this->_POST['nama'])==""){
            return false;
         }else{
            return true;
         }
      }
   }
   
   function Add() {
      if (isset($_POST['btnsimpan'])) {
         if($this->Check()){
         $add = $this->Obj->DoAddPangkatGolongan($this->_POST['pangkat'], $this->_POST['nama'], $this->_POST['tingkat'], $this->_POST['masa'], $this->_POST['urut']);
            if ($add == true){
               Messenger::Instance()->Send('pangkat_golongan','PangkatGolongan','view','html', array($this->_POST,$this->msgAddSuccess, $this->cssDone),Messenger::NextRequest);
            }else{
               Messenger::Instance()->Send('pangkat_golongan','PangkatGolongan','view','html', array($this->_POST,$this->msgAddFail, $this->cssFail),Messenger::NextRequest);
            }
            $urlRedirect = $this->pageView;
         }else{
            Messenger::Instance()->Send('pangkat_golongan', 'InputPangkatGolongan','view','html', array($this->_POST,$this->msgReqDataEmpty, $this->cssAlert), Messenger::NextRequest);
            return $this->pageInput;
         }
      }
      
         return $this->pageView;
      
   }
   
   function Update(){
      if(isset($_POST['btnsimpan'])){
         $id = $this->_POST['id_post'];
         if($this->Check()){
            $proc = $this->Obj->DoUpdatePangkatGolongan($this->_POST['pangkat'], $this->_POST['nama'],$this->_POST['tingkat'], $this->_POST['masa'],$this->_POST['urut'],$id);
         if ($proc === true){
            Messenger::Instance()->Send('pangkat_golongan','PangkatGolongan','view','html', array($this->_POST,$this->msgUpdateSuccess, $this->cssDone),Messenger::NextRequest);
         }else{
            Messenger::Instance()->Send('pangkat_golongan','PangkatGolongan','view','html', array($this->_POST,$this->msgUpdateFail, $this->cssFail),Messenger::NextRequest);
         }
         
         $urlRedirect = $this->pageView;
      }else{
         Messenger::Instance()->Send('pangkat_golongan', 'InputPangkatGolongan','view','html', array($this->_POST,$this->msgReqDataEmpty, $this->cssFail), Messenger::NextRequest);
         $urlRedirect = $this->pageInput.'&id='.$id;
      } 
   }
   return $urlRedirect;
   }
   
   function Delete(){
		$arrId = $this->_POST['idDelete'];		
    $deleteData = $this->Obj->DoDeletePangkatGolongan($arrId);
      
    if($deleteData === true) {
			Messenger::Instance()->Send('pangkat_golongan', 'PangkatGolongan', 'view', 'html', array($this->_POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}
      else if($deleteData === false){
		  $deleteArrData = $this->Obj->DoDeletePangkatGolonganByArrayId($arrId);
		}
      
		if($deleteArrData === true) {
			Messenger::Instance()->Send('pangkat_golongan', 'PangkatGolongan', 'view', 'html', array($this->_POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('pangkat_golongan', 'PangkatGolongan', 'view', 'html', array($this->_POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}

		return $this->pageView;
   }
  
}
?>
