<?php
class AppProfile extends Database{
   protected $mSqlFile= 'module/profile/business/mysqlt/appprofile.sql.php';
   
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
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_data_group_by_id'], array($groupId));
      return $result;
   }
   function DoUpdateProfile($realName,$description,$userId){
      return $this->Execute($this->mSqlQueries['do_update_profile'], array($realName, $description, $userId));
   }
   function DoUpdatePassword($passbaru,$passlama){
      $result=$this->Execute($this->mSqlQueries['do_update_password'],array($passbaru,$passlama));
      return $result;
   }
}
?>
