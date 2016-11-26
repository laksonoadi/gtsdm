<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/AppUser.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/email/business/Email.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';

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
      $this->emailObj = new Email();
      $this->pegawaiObj = new DataPegawai();
      
      $this->applicationId = GTFWConfiguration::GetValue('application', 'application_id');
      $this->_POST = $_POST->AsArray();
      $this->decUsr = Dispatcher::Instance()->Decrypt($_REQUEST['usr']);
      $this->encId = Dispatcher::Instance()->Encrypt($this->decId);
      $this->pageView = Dispatcher::Instance()->GetUrl('home', 'home', 'view', 'html');
      $this->pageInputPassword = Dispatcher::Instance()->GetUrl('ganti_password', 'changePassword', 'view', 'html');
   }

   function IsEmpty($formName, $label, $sub_modul) {
      if (isset($_POST['btnsimpan'])) {
         if(trim($this->_POST[$formName]) == "") {
            $this->SendAlert("Fill $label Required.", $sub_modul);
            return true;
         } else {
            return false;
         }
      }
   }
   
   function IsPasswordInvalid($formPass, $fomRepass, $sub_modul) {
      if (isset($_POST['btnsimpan'])) {
         if(trim($this->_POST[$formPass]) != trim($this->_POST[$fomRepass])) {
            $this->SendAlert("Password and Retype Password Must Be Similar.", $sub_modul);
            return true;
         } else {
            return false;
         }
      }
   }
   
   function SendAlert($alert, $sub_modul, $css='') {
      Messenger::Instance()->Send('ganti_password', $sub_modul, 'view', 'html', array($this->_POST, $alert, $css),Messenger::NextRequest);
   }
   
   function UpdatePassword() {
      if (isset($_POST['btnbalik'])) {
         return $this->pageView;
      }
      
      $cek_old_pass = $this->IsEmpty('old_password', 'Password', 'changePassword');
      $cek_pass = $this->IsEmpty('password', 'Password', 'changePassword');
      $cek_repass = $this->IsEmpty('retype_password', 'Konfirmasi Password', 'changePassword');
      $cek_validitas_old_pass = $this->userObj->cekValiditasOldPassword($_POST['old_password'],$_SESSION['username']);
      if ($cek_pass || $cek_repass || $cek_old_pass) {
         return $this->pageInputPassword;
      } else if ($this->IsPasswordInvalid('password', 'retype_password', 'changePassword')) {
         return $this->pageInputPassword;
      } else if (empty($cek_validitas_old_pass)){
         $this->SendAlert("Your Current Password Invalid.", "changePassword");
         return $this->pageInputPassword;
      } else {
         $updatePassword = $this->userObj->DoUpdatePasswordWithUserName($_POST['password'], $_POST['old_password'], $_SESSION['username']);
         
         if ($updatePassword === true) {
            $dataPegawai=$this->pegawaiObj->GetDataPegawaiByUserName();
            
            //Block Kirim Email
            $to=$dataPegawai['pegEmail'];
            $from=GTFWConfiguration::GetValue('application', 'email_pengirim');
            $arrBody[0]['replace']='{NAMA_PEGAWAI}'; $arrBody[0]['with']=$dataPegawai['pegNama'];
            $arrBody[1]['replace']='{PASSWORD}'; $arrBody[1]['with']=$_POST['password'];
            $body=$this->emailObj->getBodyEmail('email_ganti_password',$arrBody);
            $subject=$this->emailObj->getSubjectEmail('email_ganti_password');
            
            $Status=GTFWConfiguration::GetValue( 'application', 'email_notifications');
            if ($status==true){
              $kirim= $this->emailObj->kirimEmail($to,$bcc,$cc,$from,$subject,$body);
            }else{
              $kirim='<pre><font size=2>'.$body.'</font></pre>';
            }
            
            //--------------------------------------------------------
            
            $this->SendAlert('Change Password Success. <br />'.$kirim, 'changePassword', $this->cssDone);
  			 } else {
  			   $this->SendAlert('Change Password Fail', 'changePassword', $this->cssFail);
  			 }
         return $this->pageInputPassword;
      }
   }

}
?>
