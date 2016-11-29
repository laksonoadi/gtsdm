<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/profile/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/AppProfile.class.php';
class ProcessProfile{
	var $_POST;
	var $Obj;
	var $pageView;
	var $pageInput;
	//css hanya dipake di view
	var $cssDone = "notebox-done";
	var $cssFail = "notebox-warning";

	var $return;
	var $decId;
	var $encId;

	function __construct() {
		$this->Obj = new AppProfile();
		$this->_POST = $_POST->AsArray();
		$this->pageView = Dispatcher::Instance()->GetUrl('profile', 'profile', 'view', 'html');
		$this->pageUpdateProf = Dispatcher::Instance()->GetUrl('profile', 'inputProfile', 'view', 'html');
      $this->pageUpdatePass = Dispatcher::Instance()->GetUrl('profile', 'updatePassword', 'view', 'html');
	}

   function Check(){
      if (isset($_POST['btnsimpan'])){
         if(trim($this->_POST['realname'])==""){
            return "empty";
         }else{
            return true;
         }
      }     
      if (isset($_POST['btnganti'])){
         if((trim($this->_POST['passlama'])!=="")or(trim($this->_POST['passbaru1'])!=="")or(trim($this->_POST['passbaru2'])!=="")){
            if ($_POST['passbaru1']==$_POST['passbaru2']) {
               $dataUser = $this->Obj->GetDataUserById($this->_POST['dataId']);
               $passlama = md5($_POST['passlama']);
               if ($dataUser[0]['password'] == $passlama) {
                  return true;
               }else{
                  return "not_valid";
               }
            }else{
               return "not_same";
            }
         }else{
            return "empty";
         }
      }
      return false;
   }

   function UpdateProfile(){
      if (isset($_POST['btnbalik'])) {
         return $this->pageView;
      }
      $cekProfile=$this->Check();
      if($cekProfile===true){
         $dataUser = $this->Obj->GetDataUserById($this->_POST['dataId']);
         $update = $this->Obj->DoUpdateProfile($_POST['realname'], $_POST['deskripsi'],$dataUser[0]['user_id']);
         if($update===true){
            Messenger::Instance()->Send('profile', 'profile', 'view', 'html', array($this->_POST,'Perubahan Data Berhasil Dilakukan', $this->cssDone),Messenger::NextRequest);
         }else{
            Messenger::Instance()->Send('profile', 'profile', 'view', 'html', array($this->_POST,'Perubahan Gagal Berhasil Dilakukan', $this->cssFail),Messenger::NextRequest);
         }
      }elseif($cekProfile="empty"){
         Messenger::Instance()->Send('profile', 'inputProfile', 'view', 'html', array($this->_POST,'Data Nama Lengkap Tidak Boleh Kosong'),Messenger::NextRequest);
			return $this->pageUpdateProf;
      } 
      return $this->pageView;
   }


   function UpdatePassword(){
      $cek=$this->Check();
      if($cek===true){
         $update =$this->Obj->DoUpdatePassword(md5($_POST['passbaru1']), $this->_POST['dataId']);
         if($update===true){
            Messenger::Instance()->Send('profile', 'profile', 'view', 'html', array($this->_POST,'Berhasil Mengganti Password', $this->cssDone),Messenger::NextRequest);
         }else{
            Messenger::Instance()->Send('profile', 'profile', 'view', 'html', array($this->_POST,'Gagal Mengganti Password', $this->cssFail),Messenger::NextRequest);
         }
      }elseif($cek=="empty"){
         Messenger::Instance()->Send('profile','updatePassword','view','html',array($this->_POST,'Lengkapi Isian data'),Messenger::NextRequest);
         return $this->pageUpdatePass;
      }elseif($cek=="not_same"){
         Messenger::Instance()->Send('profile','updatePassword','view','html',array($this->_POST,'Password Baru Tidak Sesuai Dengan Konfirmasi Password Baru'),Messenger::NextRequest);
         return $this->pageUpdatePass;
      }elseif($cek=="not_valid"){
         Messenger::Instance()->Send('profile','updatePassword','view','html',array($this->_POST,'Password Lama Salah'),Messenger::NextRequest);
         return $this->pageUpdatePass;
      }
      return $this->pageView;
   }
}
?>
