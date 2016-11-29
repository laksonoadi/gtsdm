<?php

class AppUser extends Database {

   protected $mSqlFile= 'module/user_portal/business/mysqlt/appuser.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      
   }
   //OK
   function GetCountData($nama,$group) {		   
      $result = $this->Open($this->mSqlQueries['get_count_data'], array('%'.$nama.'%',$group));
	  if (empty($result)) return 0;
      return $result[0]['total'];
   }
   //OK
   function GetData($nama,$group,$startRec, $itemViewed) {		   
      $result = $this->Open($this->mSqlQueries['get_data'], array('%'.$nama.'%',$group,$startRec, $itemViewed));
      return $result;
   }
   //OK
   function GetDataUser ($offset, $limit, $userName='', $realName='', $applicationId) {		   
      if(($userName!='') and ($realName!=''))                      
         $str = ' OR ';
      else
         $str = ' AND ';
            
      $sql = sprintf($this->mSqlQueries['get_data_user'], $applicationId, '%s',$str,'%s','%d','%d');      
      $result = $this->Open($sql, array('%'.$userName.'%', '%'.$realName.'%', $offset, $limit));
      return $result;
   }
   //OK
   function GetDataUserById($id) {
      $result = $this->Open($this->mSqlQueries['get_data_user_by_id'], array($id));
      return $result;
   }
   //OK
   function GetCountDuplicateUsername($newUserName, $userId) {
      $result = $this->Open($this->mSqlQueries['get_count_duplicate_username'], array($newUserName, $userId));
      //
      return $result[0]['COUNT'];
   }
   //OK
   function GetCountDuplicateUsernameAdd($newUserName) {
      $result = $this->Open($this->mSqlQueries['get_count_duplicate_username_add'], array($newUserName));
      //
      return $result[0]['COUNT'];
   }
   //OK
   function GetCountDataUser ($userName='', $realName='', $applicationId) {
      $result = $this->Open($this->mSqlQueries['get_count_data_user'], array($applicationId, '%'.$userName.'%', '%'.$realName.'%'));
      if (!$result) {
         return 0;
      } else {
         return $result[0]['total'];
      }
   }
   //OK
   function GetMaxId(){
      $rs = $this->Open($this->mSqlQueries['get_max_id'], array());
      return $rs[0]['id'];
   }
   //OK
   function GetComboUnitKerja($applicationId){
      return $this->Open($this->mSqlQueries['get_combo_unit_kerja'],array($applicationId));
   }
   //OK
   function GetDataGroupByUnitId($groupName, $unitId, $applicationId) {
      $result = $this->Open($this->mSqlQueries['get_data_group_by_unit_id'], array('%'.$groupName.'%', $unitId, $applicationId));
      //
      return $result;
   }
   //OK
   function GetDataPegawaiByGroup($groupId) {
      $result = $this->Open($this->mSqlQueries['get_data_pegawai_by_group'], array($groupId));
      //
      return $result;
   }

//===DO==
   //OK
   function DoAddUserDefGroup($UserId, $GroupId, $ApplicationId) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_user_def_group'], array($UserId, $GroupId, $ApplicationId));
      //
      return $result;
   }
   //OK
   function DoAddUserGroup($UserId, $GroupId) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_user_group'], array($UserId, $GroupId));
      return $result;
   }
   //OK
   function DoAddUserPegawai($UserId, $PegawaiId) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_user_pegawai'], array($UserId, $PegawaiId));
      return $result;
   }
   //OK
   function DoUpdateUserDefGroup($GroupId, $ApplicationId, $UserId) {
      $result = $this->Execute($this->mSqlQueries['do_update_user_def_group'], array($GroupId, $ApplicationId, $UserId));
      //
      return $result;
   }
   //OK
   function DoUpdateUserGroup($GroupId, $UserId) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_update_user_group'], array($GroupId, $UserId));
      //exit;
      return $result;
   }
   //OK
   function DoAddUser($userName, $password, $realName, $description, $active) {
      $result = $this->Execute($this->mSqlQueries['do_add_user'], array($userName, $password, $realName, $description, $active));
      //
      return $result;
   }
   //OK
   function DoUpdateUser($userName, $realName, $active, $decription, $userId) {
      $result = $this->Execute($this->mSqlQueries['do_update_user'], array($userName, $realName, $active, $decription, $userId));
      return $result;
   }
   //OK
   function DoDeleteUserById($userId) {
      $result=$this->Execute($this->mSqlQueries['do_delete_user_by_id'], array($userId));
	  $result=$this->Execute($this->mSqlQueries['do_delete_peg_user_by_id'], array($userId));
      //
      return $result;
   }
   //OK
	function DoDeleteUserByArrayId($arrUserId) {
		$userId = implode("', '", $arrUserId);
		$result=$this->Execute($this->mSqlQueries['do_delete_user_by_array_id'], array($userId));
		$result=$this->Execute($this->mSqlQueries['do_delete_peg_user_by_array_id'], array($userId));
		return $result;
	}
   //tambahan
   //OK 
   function DoUpdatePasswordUser($password, $userId) {
      $result = $this->Execute($this->mSqlQueries['do_update_password_user'], array($password, $userId));
      return $result;
   }
   
    //OK 
   function SetStatus($status,$id) {
      $result = $this->Execute($this->mSqlQueries['set_status'], array($status,$id));
      return $result;
   }
   
   function ResetPassword($userId) {
      $data='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $v1=rand(strlen($data),1);
      $v2=rand(strlen($data),1);
      $v3=rand(strlen($data),1);
      $v4=rand(strlen($data),1);
      $v5=rand(strlen($data),1);
      $v6=rand(strlen($data),1);
      $password=$data[$v1].$data[$v2].$data[$v3].$data[$v4].$data[$v5].$data[$v6];
      
      $result = $this->Execute($this->mSqlQueries['do_update_password_user'], array($password, $userId));
      if ($result===false){
        return false;
      }
      return $password;
   }

}
?>
