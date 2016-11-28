<?php

class AppGroup extends Database {

   protected $mSqlFile= 'module/group/business/appgroup.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //
   }
   
//===GET===  
   function GetDataGroupById($groupId) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_data_group_by_id'], array($groupId));
      return $result;
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

   function GetLastGroupId() {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_last_group_id'], array());
      return $result;
   }
   
   function GetPrivilegeById($dmMenuId) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_privilege_by_id'], array($dmMenuId));
      return $result;
   }
   
   function GetPrivilegeByArrayId($arrDmMenuId) {
		
		foreach($arrDmMenuId as $value){
			$arrS[] = '%s';
		}
      $strId = implode(',', $arrS);
		$newSql = sprintf($this->mSqlQueries['get_privilege_by_array_id'],$strId);
		
      $result = $this->GetAllDataAsArray($newSql, $arrDmMenuId);
      return $result;
   }
   
   function GetAllPrivilege() {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_all_privilege'], array());
      return $result;
   }
   
   function GetGroupPrivilege($groupId) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_group_privilege'], array($groupId));
      return $result;
   }
   
   function GetDataGroupMenuByGroupId($groupId) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_data_group_menu_by_group_id'], array($groupId));
      return $result;
   }
   
   function GetDataGroupMenu() {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_data_group_menu'], array());
      return $result;
   }
   
   function GetMaxMenuId() {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_max_menu_id'], array());
      return $result[0]['max_id'];
   }
   
   function GetComboUnitKerja(){
      return $this->Open($this->mSqlQueries['get_combo_unit_kerja'],array());
   }
   
//===DO===
   function DoAddUserDefGroup($UserId, $GroupId, $ApplicationId) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_user_def_group'], array($UserId, $GroupId, $ApplicationId));
      return $result;
   }
   
   function DoAddUserGroup($UserId, $GroupId) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_user_group'], array($UserId, $GroupId));
      return $result;
   }

   function DoAddGroup($groupName, $description,$unitId) {
      return $this->ExecuteInsertQuery($this->mSqlQueries['do_add_group'], array($groupName, $description,$unitId));
   }
   
   function DoUpdateUserDefGroup($GroupId, $ApplicationId, $UserId) {
      $result = $this->Execute($this->mSqlQueries['do_update_user_def_group'], array($GroupId, $ApplicationId, $UserId));
      return $result;
   }
   
   function DoUpdateUserGroup($GroupId, $UserId) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_update_user_group'], array($GroupId, $UserId));
      return $result;
   }
   
   function DoUpdateGroup($groupName, $description, $unitId, $groupId) {
      $result = $this->Execute($this->mSqlQueries['do_update_group'], array($groupName, $description, $unitId, $groupId));
      return $result;
   }
   
   function DoDeleteGroup($groupId) {
      $result = $this->Execute($this->mSqlQueries['do_delete_group'], array($groupId));
      return $result;
   }
   
   function DoAddGroupModuleByModuleName($moduleName, $groupId = '') {
      if ($groupId != '') {
         $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_group_module_by_module_name'], array($groupId, $moduleName));
      } else {
         $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_group_module_by_module_name_new_group'], array($moduleName));
      }
      return $result;
   }
   
   function DoAddGroupMenuForNewGroup($menuName, $moduleId, $parentMenuId, $flagShow) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_group_menu_for_new_group'], array($menuName, $moduleId, $parentMenuId, $flagShow));
      return $result;
   }
   
   function DoAddGroupMenu($menuName, $groupId, $moduleId, $parentMenuId, $flagShow) {
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_group_menu'], array($menuName, $groupId, $moduleId, $parentMenuId, $flagShow));
      return $result;
   }
   
   function DoAddGroupModuleFromDummyMenu($dmMenuId, $groupId='') {
      if ($groupId!= '') {
         $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_group_module_from_dummy_menu'], array($groupId, $dmMenuId));
      } else {
         $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_group_module_from_dummy_menu_new_group'], array($dmMenuId));
      }
      return $result;
   }
   
   function DoAddGroupModule($groupId, $moduleId, $newGroup= false) {
      if ($newGroup === false) {
         $result = $this->Execute($this->mSqlQueries['do_add_group_module'], array($groupId, $moduleId));
      } else {
         $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_add_group_module_newgroup'], array($moduleId));
      }
      return $result;
   }
   
   function DoDeleteGroupMenu($groupId) {
      $result = $this->ExecuteDeleteQuery($this->mSqlQueries['do_delete_group_menu'], array($groupId));
      return $result;
   }
   
   function DoDeleteGroupModule($groupId) {
      #printf($this->mSqlQueries['do_delete_group_module'], $groupId);
      $result = $this->ExecuteDeleteQuery($this->mSqlQueries['do_delete_group_module'], array($groupId));
      return $result;
   }
   
   function IsCanAccessMenu($menuName, $groupId) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['is_can_access_menu'], array($menuName, $groupId));     
      if ($result[0]['result'] > 0) {
         return true;
      } else {
         return false;
      }
   }
}
?>
