<?php

class AppUser extends Database {

   protected $mSqlFile= 'module/user/business/appuser.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      
   }
      
   function GetDataUser ($offset, $limit, $userName='', $realName='') {		   
      if(($userName!='') and ($realName!=''))                      
         $str = ' OR ';
      else
         $str = ' AND ';
            
      $sql = sprintf($this->mSqlQueries['get_data_user'], '%s',$str,'%s','%d','%d');      
      $result = $this->Open($sql, array('%'.$userName.'%', '%'.$realName.'%', $offset, $limit));
      return $result;
   }
   
   function GetDataUserByInstansi ($realName, $instansiId, $offset, $limit) {
      $result = $this->Open($this->mSqlQueries['get_data_user_by_instansi'], array($realName, $instansiId, $offset, $limit));
      return $result;
   }
   
   function GetDataUserById($id) {
      $result = $this->Open($this->mSqlQueries['get_data_user_by_id'], array($id));
      return $result;
   }
   
   function GetCountDuplicateUsername($newUserName, $userId) {
      $result = $this->Open($this->mSqlQueries['get_count_duplicate_username'], array($newUserName, $userId));
      //
      return $result[0]['COUNT'];
   }

   function GetCountDataUser ($userName='', $realName='') {
      $result = $this->Open($this->mSqlQueries['get_count_data_user'], array('%'.$userName.'%', '%'.$realName.'%'));
      if (!$result) {
         return 0;
      } else {
         return $result[0]['total'];
      }
   }
   
   function GetCountDataUserByInstansi ($realName, $instansiId) {
      $result = $this->Open($this->mSqlQueries['get_count_data_user_by_instansi'], array($realName, $instansiId));
      if (!$result) {
         return 0;
      } else {
         return $result[0]['total'];
      }
   }
    
   /*function GetDataUserById($userId) {
      $result = $this->Open($this->mSqlQueries['get_data_user_by_id'], array($userId));  
      return $result;
   }*/

   function GetNoPegawai($id){
      return $this->Open($this->mSqlQueries['get_no_pegawai'], array($id));
   }

   function GetMaxId(){
      $rs = $this->Open($this->mSqlQueries['get_max_id'], array());
      return $rs[0]['id'];
   }

   function GetComboUnitKerja(){
      return $this->Open($this->mSqlQueries['get_combo_unit_kerja'],array());
   }

   function GetDataGroup($groupName, $withPrivilege= false) {

      if ($withPrivilege){
         $result = $this->Open($this->mSqlQueries['get_data_group_with_privilege'], array('%'.$groupName.'%'));
      } else {
         $result = $this->Open($this->mSqlQueries['get_data_group'], array('%'.$groupName.'%'));
      }
      return $result;
   }

   function GetDataGroupByUnitId($groupName, $unitId) {
      $result = $this->Open($this->mSqlQueries['get_data_group_by_unit_id'], array('%'.$groupName.'%', $unitId));
      //
      return $result;
   }

//===DO==

   function DoAddUserDefGroup($UserId, $GroupId, $ApplicationId) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_user_def_group'], array($UserId, $GroupId, $ApplicationId));
      return $result;
   }

   function DoAddUserGroup($UserId, $GroupId) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_user_group'], array($UserId, $GroupId));
      return $result;
   }

   function DoUpdateUserDefGroup($GroupId, $ApplicationId, $UserId) {
      $result = $this->Execute($this->mSqlQueries['do_update_user_def_group'], array($GroupId, $ApplicationId, $UserId));
      return $result;
   }

   function DoUpdateUserGroup($GroupId, $UserId) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_update_user_group'], array($GroupId, $UserId));
      return $result;
   }
   
   function DoAddUser($userName, $password, $realName, $description, $active) {
      $result = $this->Execute($this->mSqlQueries['do_add_user'], array($userName, $password, $realName, $description, $active));
      return $result;
   }
   
   function DoUpdateUser($userName, $realName, $active, $decription, $userId) {
      $result = $this->Execute($this->mSqlQueries['do_update_user'], array($userName, $realName, $active, $decription, $userId));
      return $result;
   }
   
   function DoUpdateProfile($realName,$description,$userId){
      return $this->Execute($this->mSqlQueries['do_update_profile'], array($realName, $description, $userId));
   }
   
   function DoDeleteUserById($userId) {
      $result=$this->Execute($this->mSqlQueries['do_delete_user_by_id'], array($userId));
      //
      return $result;
   }
   
	function DoDeleteUserByArrayId($arrUserId) {
		$userId = implode("', '", $arrUserId);
		$result=$this->Execute($this->mSqlQueries['do_delete_user_by_array_id'], array($userId));
		return $result;
	}
   
   //tambahan 
   function DoUpdatePasswordUser($password, $userId) {
      $result = $this->Execute($this->mSqlQueries['do_update_password_user'], array($password, $userId));
      return $result;
   }

   function DoAddNoPeg($noPeg){
      return $this->Execute($this->mSqlQueries['do_add_no_peg'], array($noPeg));
   }

   function DoUpdateAddNoPeg($id,$noPeg){
      return $this->Execute($this->mSqlQueries['do_update_add_no_peg'], array($id,$noPeg));
   }

   function DoUpdateNoPeg($id,$noPeg){
      return $this->Execute($this->mSqlQueries['do_update_no_peg'], array($noPeg,$id));
   }

   /*function DoAddUserGroup($group){
      return $this->Execute($this->mSqlQueries['add_user_group'], array($group));
   }*/

   /*function DoUpdateUserGroup($group,$id){
      return $this->Execute($this->mSqlQueries['update_user_group'],array($group,$id));
   }*/

}
?>
