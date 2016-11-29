<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user_portal/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/AppUser.class.php';

class ProcessUser {

   var $_POST;
   var $userObj;
   var $groupObj;
   var $pageView;
   var $pageInput;
   //css hanya dipake di view
   var $cssDone = "notebox-done";
   var $cssFail = "notebox-warning";

   var $return;
   var $decUsr;
   var $encId;
   
   var $applicationId;

   function __construct() {
      $this->userObj = new AppUser();
      
      $this->applicationId = GTFWConfiguration::GetValue('application', 'application_portal_id');
      $this->_POST = $_POST->AsArray();
      $this->decUsr = Dispatcher::Instance()->Decrypt($_REQUEST['usr']);
      $this->encId = Dispatcher::Instance()->Encrypt($this->decId);
      $this->pageView = Dispatcher::Instance()->GetUrl('user_portal', 'user', 'view', 'html');
      $this->pageInput = Dispatcher::Instance()->GetUrl('user_portal', 'inputUser', 'view', 'html');
      $this->pageInputPassword = Dispatcher::Instance()->GetUrl('user_portal', 'changePassword', 'view', 'html');
   }

   function IsEmpty($formName, $label, $sub_modul) {
      if (isset($_POST['btnsimpan'])) {
         if(trim($this->_POST[$formName]) == "") {
            $this->SendAlert("Isian $label tidak boleh kosong.", $sub_modul);
            return true;
         } else {
            return false;
         }
      }
   }
   
   function IsPasswordInvalid($formPass, $fomRepass, $sub_modul) {
      if (isset($_POST['btnsimpan'])) {
         if(trim($this->_POST[$formPass]) != trim($this->_POST[$fomRepass])) {
            $this->SendAlert("Password dan Konfirmasi password harus sama.", $sub_modul);
            return true;
         } else {
            return false;
         }
      }
   }
   
   function SendAlert($alert, $sub_modul, $css='') {
      Messenger::Instance()->Send('user_portal', $sub_modul, 'view', 'html', array($this->_POST, $alert, $css),Messenger::NextRequest);
   }

   function Add() {
      if (isset($_POST['btnbalik'])) {
         return $this->pageView;
      }
      $cek_user = $this->IsEmpty('username', 'Username', 'inputUser');
      $cek_pass = $this->IsEmpty('password', 'Password', 'inputUser');
      $cek_repass = $this->IsEmpty('retype_password', 'Retype Password', 'inputUser');
      $cek_unit = $this->IsEmpty('unit_kerja', 'Work Unit', 'inputUser');
      $cek_group = $this->IsEmpty('group', 'Group', 'inputUser');
      $cek_group = $this->IsEmpty('pegawai', 'Employee Name', 'inputUser');
      
      $new_username = $_POST['username'];
      $cek = $this->userObj->GetCountDuplicateUsernameAdd($new_username);
      
      if ($cek_user || $cek_pass || $cek_repass || $cek_unit || $cek_group) {
         return $this->pageInput;
      } else if ($cek >= 1) {
         $this->SendAlert("Nama pengguna $new_username sudah ada.", 'inputUser');
         return $this->pageInput;
      } else if ($this->IsPasswordInvalid('password', 'retype_password', 'inputUser')) {
         return $this->pageInput;
      } else {
         $this->userObj->StartTrans();
         
         $addUser = $this->userObj->DoAddUser($_POST['username'], $_POST['password'], $_POST['realname'], $_POST['deskripsi'], $_POST['status']);

         $last_insert_id = $this->userObj->GetMaxId();

         $addUserDefGroup = $this->userObj->DoAddUserDefGroup($last_insert_id, $_POST['group'], $this->applicationId);
         
         $addUserGroup = $this->userObj->DoAddUserGroup($last_insert_id, $_POST['group']);
         $addUserPegawai = $this->userObj->DoAddUserPegawai($last_insert_id, $_POST['pegawai']);

         $addData = $addUser && $addUserDefGroup && $addUserGroup && $addUserPegawai;
/*var_dump($addUser);
var_dump($addUserDefGroup);
var_dump($addUserGroup);exit;*/
         $this->userObj->EndTrans($addData);
         
         if ($addData === true) {
            $this->SendAlert('Penambahan data Berhasil Dilakukan', 'user', $this->cssDone);
			} else {
			   $this->SendAlert('Gagal Menambah Data', 'user', $this->cssFail);
			}

         return $this->pageView;
      }
   }

   function Update() {
      if (isset($_POST['btnbalik'])) {
         return $this->pageView;
      }
      $cek_user = $this->IsEmpty('username', 'Nama Pengguna', 'inputUser');
      $cek_unit = $this->IsEmpty('unit_kerja', 'Unit Kerja', 'inputUser');
      $cek_group = $this->IsEmpty('group', 'Group', 'inputUser');
      if ($cek_user || $cek_unit || $cek_group) {
         return $this->pageInput;
      } else {
         $new_username = $_POST['username'];
         $cek = $this->userObj->GetCountDuplicateUsername($new_username, $this->decUsr);
         if ($cek >= 1) {
            $this->SendAlert("Nama pengguna $new_username sudah ada.", 'inputUser');
            return $this->pageInput;
         }
         
         $this->userObj->StartTrans();
         $updateUser = $this->userObj->DoUpdateUser($_POST['username'], $_POST['realname'], $_POST['status'], $_POST['deskripsi'], $this->decUsr);
         
         $updateUserDefGroup = $this->userObj->DoUpdateUserDefGroup($_POST['group'], $this->applicationId, $this->decUsr);
         
         $updateUserGroup = $this->userObj->DoUpdateUserGroup($_POST['group'], $this->decUsr);
         
         $updateData = $updateUser && $updateUserDefGroup && $updateUserGroup;

         $this->userObj->EndTrans($updateData);
         
         if ($updateData === true) {
            $this->SendAlert('Pengubahan data Berhasil Dilakukan', 'user', $this->cssDone);
			} else {
			   $this->SendAlert('Gagal Mengubah Data', 'user', $this->cssFail);
			}

         return $this->pageView;
      }
   }
   
   function UpdatePassword() {
      if (isset($_POST['btnbalik'])) {
         return $this->pageView;
      }
      $cek_pass = $this->IsEmpty('password', 'Password', 'changePassword');
      $cek_repass = $this->IsEmpty('retype_password', 'Konfirmasi Password', 'changePassword');
      if ($cek_pass || $cek_repass) {
         return $this->pageInputPassword;
      } else if ($this->IsPasswordInvalid('password', 'retype_password', 'changePassword')) {
         return $this->pageInputPassword;
      } else {
         $updatePassword = $this->userObj->DoUpdatePasswordUser($_POST['password'], $_POST['usr']);
         
         if ($updatePassword === true) {
            $this->SendAlert('Pengubahan Password Berhasil Dilakukan', 'user', $this->cssDone);
			} else {
			   $this->SendAlert('Gagal Mengubah Password', 'user', $this->cssFail);
			}
         return $this->pageView;
      }
   }

   function Delete() {
      $arrId = $this->_POST['idDelete'];
      $label_sukses = 'Penghapusan Data Berhasil Dilakukan.';
      $label_gagal = 'Data Tidak Dapat Dihapus.';

      if (is_array($arrId)) {
         $deleteArrData = $this->userObj->DoDeleteUserByArrayId($arrId);
         if($deleteArrData === true) {
            $this->SendAlert($label_sukses, 'user', $this->cssDone);
         } else {
            //jika masuk disini, berarti PASTI ada salah satu atau lebih data yang gagal dihapus
            for($i=0;$i<sizeof($arrId);$i++) {
               $deleteData = false;
               $deleteData = $this->userObj->DoDeleteUserById($arrId[$i]);
               if($deleteData === true) $sukses += 1;
               else $gagal += 1;
            }
            $this->SendAlert($label_gagal, 'user', $this->cssFail);
         }
      } else {
         $deleteData = $this->userObj->DoDeleteUserById($arrId);
         if ($deleteData === true)
            $this->SendAlert($label_sukses, 'user', $this->cssDone);
         else
            $this->SendAlert($label_gagal, 'user', $this->cssFail);
      }
      return $this->pageView;
   }
}
?>
