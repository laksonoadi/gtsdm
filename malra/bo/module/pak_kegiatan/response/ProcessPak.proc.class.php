<?php
require_once GTFWConfiguration::GetValue('applicaton','docroot').'module/pak_kegiatan/business/pak.class.php';

class ProcessPak {
   
   var $_POST;
   var $Obj;
   var $pageView;
   var $pageInput;
   
   var $cssDone = "notebox-done";
   var $cssFail = "notebox-warning";
   var $cssAlert = "notebox-alert";
   
   function __construct() {
      $this->Obj = new Pak();
      $this->_POST = $_POST->AsArray();
      $this->pageView = Dispatcher::Instance()->GetUrl('pak_kegiatan','Pak','view','html');
      $this->pageInput = Dispatcher::Instance()->GetUrl('pak_kegiatan','inputPak','view','html');
   }
   
    function Check(){
      if (isset($_POST['btnsimpan'])){
         if (trim($this->_POST['nama'])=="" ){
            return false;
         }if (trim($this->_POST['angka_kredit'])=="" ){
            return false;
         }else{
            return true;
         }
      }
   }
   
   function Add() {
      if (isset($_POST['btnsimpan'])) {
         if($this->Check()){
         $add = $this->Obj->DoAddPak($this->_POST['nama'], $this->_POST['unsur'], $this->_POST['angka_kredit']);
            if ($add == true){
               Messenger::Instance()->Send('pak_kegiatan','Pak','view','html', array($this->_POST,'Penambahan data berhasil dilakukan', $this->cssDone),Messenger::NextRequest);
            }else{
               Messenger::Instance()->Send('pak_kegiatan','Pak','view','html', array($this->_POST,'Penambahan data gagal dilakukan', $this->cssFail),Messenger::NextRequest);
            }
            $urlRedirect = $this->pageView;
         }else{
            Messenger::Instance()->Send('pak_kegiatan', 'inputPak','view','html', array($this->_POST,'Semua data bertanda * harus diisi', $this->cssAlert), Messenger::NextRequest);
            return $this->pageInput;
         }
      }
      
         return $this->pageView;
      
   }
   
   function Update(){
      if(isset($_POST['btnsimpan'])){
         $id = $this->_POST['id_post'];
         if($this->Check()){
            $proc = $this->Obj->DoUpdatePak($this->_POST['nama'], $this->_POST['unsur'],$this->_POST['angka_kredit'],$id);
         if ($proc === true){
            Messenger::Instance()->Send('pak_kegiatan','Pak','view','html', array($this->_POST,'Pengupdatean data berhasil dilakukan', $this->cssDone),Messenger::NextRequest);
         }else{
            Messenger::Instance()->Send('pak_kegiatan','Pak','view','html', array($this->_POST,'Pegupdatean data gagal dilakukan', $this->cssFail),Messenger::NextRequest);
         }
         
         $urlRedirect = $this->pageView;
      }else{
         Messenger::Instance()->Send('pak_kegiatan', 'inputPak','view','html', array($this->_POST,'Semua data bertanda * harus diisi', $this->cssFail), Messenger::NextRequest);
         $urlRedirect = $this->pageInput.'&id='.$id;
      } 
   }
   return $urlRedirect;
   }
   
   function Delete(){
		$arrId = $this->_POST['idDelete'];		
    $deleteData = $this->Obj->DoDeletePak($arrId);
      
    if($deleteData === true) {
			Messenger::Instance()->Send('pak_kegiatan', 'Pak', 'view', 'html', array($this->_POST,'Penghapusan Data Berhasil Dilakukan', $this->cssDone),Messenger::NextRequest);   
		}
      else if($deleteData === false){
		  $deleteArrData = $this->Obj->DoDeletePakByArrayId($arrId);
		}
      
		if($deleteArrData === true) {
			Messenger::Instance()->Send('pak_kegiatan', 'Pak', 'view', 'html', array($this->_POST,'Penghapusan Data Berhasil Dilakukan', $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('pak_kegiatan', 'Pak', 'view', 'html', array($this->_POST,'Penghapusan Data Gagal Dilakukan', $this->cssFail),Messenger::NextRequest);   
		}

		return $this->pageView;
   }
  
}
?>
