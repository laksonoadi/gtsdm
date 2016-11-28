<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/jabatan_struktural/business/mysqlt/jabatan_struktural.class.php';

class ProcessJabatanStruktural {
   
   var $_POST;
   var $Obj;
   var $pageView;
   var $pageInput;
   
   var $cssDone = "notebox-done";
   var $cssFail = "notebox-warning";
   var $cssAlert = "notebox-alert";
   
   function __construct() {
      $this->Obj = new JabatanStruktural();
      $this->_POST = $_POST->AsArray();
      $this->pageView = Dispatcher::Instance()->GetUrl('jabatan_struktural','JabatanStruktural','view','html');
      $this->pageInput = Dispatcher::Instance()->GetUrl('jabatan_struktural','inputJabatanStruktural','view','html');
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
         if (trim($this->_POST['nama'])=="" ){
            return false;
         }else{
            return true;
         }
      }
   }
   
   function Add() {
      if (isset($_POST['btnsimpan'])) {
         if($this->Check()){

          $nama_file = '';
          if (!empty($_FILES['anjab_file']['tmp_name'])){
            $nama_file = rand(0,10000).trim(str_replace(' ', '_', $_FILES['anjab_file']['name']));
          }       

         $add = $this->Obj->DoAddJabstruk($this->_POST['nama'], $this->_POST['tingkat'], $this->_POST['batas'], $this->_POST['tpstr'], $this->_POST['komp'],$this->_POST['satker'],$nama_file);
         
         if($add) {
              if (!empty($_FILES['anjab_file']['tmp_name'])){
             // @unlink(GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/template_anjab/'.$this->POST['old_file']);
             move_uploaded_file($_FILES['anjab_file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/template_anjab/'.$nama_file);
           }
    
         }

            if ($add == true){
               Messenger::Instance()->Send('jabatan_struktural','JabatanStruktural','view','html', array($this->_POST,$this->_POST,$this->msgAddSuccess, $this->cssDone),Messenger::NextRequest);
            }else{
               Messenger::Instance()->Send('jabatan_struktural','JabatanStruktural','view','html', array($this->_POST,$this->_POST,$this->msgAddFail, $this->cssFail),Messenger::NextRequest);
            }
            $urlRedirect = $this->pageView;
         }else{
            Messenger::Instance()->Send('jabatan_struktural', 'inputJabatanStruktural','view','html', array($this->_POST,$this->msgReqDataEmpty, $this->cssAlert), Messenger::NextRequest);
            return $this->pageInput;
         }
      }
      
         return $this->pageView;
      
   }
   
   function Update(){
      if(isset($_POST['btnsimpan'])){
         $id = $this->_POST['id_post'];


         if($this->Check()){

          if (!empty($_FILES['anjab_file']['tmp_name'])){
            $nama_file = rand(0,10000).trim(str_replace(' ', '_', $_FILES['anjab_file']['name']));
          }else{
            $nama_file = $this->_POST['old_file'];
          }

         // echo "<pre>".$nama_file;
         // print_r($_POST);
         // // print_r($_FILES);
         // echo "</pre>";
         // exit();

            $proc = $this->Obj->DoUpdateJabstruk($this->_POST['nama'], $this->_POST['tingkat'], $this->_POST['batas'], $this->_POST['tpstr'], $this->_POST['komp'],$this->_POST['satker'],$nama_file,$id);
         if ($proc === true){
           
           if (!empty($_FILES['anjab_file']['tmp_name'])){
             @unlink(GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/template_anjab/'.$this->_POST['old_file']);
             move_uploaded_file($_FILES['anjab_file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/template_anjab/'.$nama_file);
           }
           
            Messenger::Instance()->Send('jabatan_struktural','JabatanStruktural','view','html', array($this->_POST,$this->msgUpdateSuccess, $this->cssDone),Messenger::NextRequest);
         }else{
            Messenger::Instance()->Send('jabatan_struktural','JabatanStruktural','view','html', array($this->_POST,$this->msgUpdateFail, $this->cssFail),Messenger::NextRequest);
         }
         
         $urlRedirect = $this->pageView;
      }else{
         Messenger::Instance()->Send('jabatan_struktural', 'inputJabatanStruktural','view','html', array($this->_POST,'Semua data bertanda * harus diisi', $this->cssFail), Messenger::NextRequest);
         $urlRedirect = $this->pageInput.'&id='.$id;
      } 
   }
   return $urlRedirect;
   }
   
   function Delete(){
		$arrId = $this->_POST['idDelete'];		
    $deleteData = $this->Obj->DoDeleteJabstruk($arrId);
      
    if($deleteData === true) {
			Messenger::Instance()->Send('jabatan_struktural', 'JabatanStruktural', 'view', 'html', array($this->_POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}
      else if($deleteData === false){
		  $deleteArrData = $this->Obj->DoDeleteJabstrukByArrayId($arrId);
		}
      
		if($deleteArrData === true) {
			Messenger::Instance()->Send('jabatan_struktural', 'JabatanStruktural', 'view', 'html', array($this->_POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('jabatan_struktural', 'JabatanStruktural', 'view', 'html', array($this->_POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}

		return $this->pageView;
   }
  
}
?>
