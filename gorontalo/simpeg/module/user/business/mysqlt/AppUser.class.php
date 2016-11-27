<?php

class AppUser extends Database {

   protected $mSqlFile= 'module/user/business/mysqlt/appuser.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      
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

   function GetSatkerById($id) {
      $result = $this->Open($this->mSqlQueries['get_satuan_kerja_by_id'], array($id));
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
      $result = count($result);
      // echo "<pre>";
      // print_r(count($result));
      // echo "</pre>";

      if (!$result) {
         return 0;
      } else {
         // return $result[0]['total'];
                  return $result;
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

   function GetComboUnitKerjaName($applicationId){
      return $this->Open($this->mSqlQueries['get_combo_unit_kerja_nama'],array($applicationId));
   }
   //OK
   function GetComboSatuanKerja($UnitId=null){
      return $this->Open($this->mSqlQueries['get_combo_satuan_kerja'],array($UnitId.'%'));
   }
   //OK
   function GetDataGroupByUnitId($groupName, $unitId, $applicationId) {
      $result = $this->Open($this->mSqlQueries['get_data_group_by_unit_id'], array('%'.$groupName.'%', $unitId, $applicationId));
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
   function DoAddUserSatuanKerja($UserId, $SatuanKerjaId) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_user_satuan_kerja'], array($UserId, $SatuanKerjaId));
      return $result;
   }
   //OK
   function DoAddUserSatuanKerjaGrup($UserId, $SatuanKerjaId) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_user_satuan_kerja_grup'], array($UserId, $SatuanKerjaId));
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
   function DoUpdateUserSatuanKerja($SatuanKerjaId, $UserId) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_update_user_satuan_kerja'], array($SatuanKerjaId, $UserId));
      //exit;
      return $result;
   }
   function DoUpdateUserSatuanKerjaGrup($SatuanKerjaId, $UserId) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_update_user_satuan_kerja_grup'], array($SatuanKerjaId, $UserId));
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
      //
      return $result;
   }

   function DoDeleteUserUnitById($userId) {
      $result=$this->Execute($this->mSqlQueries['do_delete_unit_user'], array($userId));
      //
      return $result;
   }
   
   //OK
	function DoDeleteUserByArrayId($arrUserId) {
		$userId = implode("', '", $arrUserId);
		$result=$this->Execute($this->mSqlQueries['do_delete_user_by_array_id'], array($userId));
		return $result;
	}
   //tambahan
   //OK 
   function DoUpdatePasswordUser($password, $userId) {
      $result = $this->Execute($this->mSqlQueries['do_update_password_user'], array($password, $userId));
      return $result;
   }

   function GetListUnitGroup($id) {
      $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($id));
      return $result;
   }

	function GetListUnit() {
		$result = $this->Open($this->mSqlQueries['get_list_unit'], array());
		return $result;
	}
	
	function GetSatkerByUserId($id) {
		$result = $this->Open($this->mSqlQueries['get_satker_by_user_id'], array($id));
		return $result;
	}
}
?>
