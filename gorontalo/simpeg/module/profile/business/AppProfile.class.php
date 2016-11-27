<?php
class AppProfile extends Database{
   protected $mSqlFile= 'module/profile/business/appprofile.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
             
   }
   function GetDataUserByUsername($username) {
      $result = $this->Open($this->mSqlQueries['get_data_user_by_username'], array($username));  
      //print($this->GetLastError());
      return $result;
   }
   function GetDataUserById($userId) {
      $result = $this->Open($this->mSqlQueries['get_data_user_by_id'], array($userId));  
      return $result;
   }
   function GetDataGroupById($groupId) {
      $this->DoMaxGroupConcat();
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_data_group_by_id'], array($groupId));
      return $result;
   }
   function DoMaxGroupConcat(){
      return $this->Execute($this->mSqlQueries['do_max_group_concat'], array());
   }
   function DoUpdateProfile($realName,$description,$userId){
      return $this->Execute($this->mSqlQueries['do_update_profile'], array($realName, $description, $userId));
   }
   function DoUpdatePassword($passbaru,$passlama){
      $result=$this->Execute($this->mSqlQueries['do_update_password'],array($passbaru,$passlama));
      return $result;
   }
   
   function GetProfilePegawai($id) { 
      $result = $this->Open($this->mSqlQueries['get_profile_pegawai'],array($id));
      return $result[0];
   }
}
?>
