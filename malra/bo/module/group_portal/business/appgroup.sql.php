<?php
//===GET===

$sql['get_data_group'] = 
   "SELECT 
      GroupId AS id,
      GroupName AS groupname,
      g.Description AS description,
      g.UnitId AS group_unit_id,
      ut.UnitName AS unit_kerja
   FROM 
      gtfw_group g
      JOIN gtfw_unit ut ON ut.UnitId=g.UnitId
   WHERE
      GroupName like '%s' 
   /*AND 
      GroupId != '2'*/
   ORDER BY 
      GroupName";

$sql['get_data_group_by_unit_id'] = 
   "SELECT 
      GroupId AS id,
      GroupName AS groupname,
      g.Description AS description,
      g.UnitId AS group_unit_id,
      ut.UnitName AS unit_kerja
   FROM 
      gtfw_group g
      JOIN gtfw_unit ut ON ut.UnitId=g.UnitId
   WHERE
      GroupName like '%s' and g.UnitId = '%s'
   /*AND 
      GroupId != '2'*/
   ORDER BY 
      GroupName";
   
$sql['get_data_group_with_privilege'] = 
   "SELECT 
      g.GroupId AS group_id,
      g.GroupName AS groupname,
      g.Description AS description,
      g.UnitId AS group_unit_id,
      ut.UnitName AS unit_kerja,
      GROUP_CONCAT(`MenuId` ORDER BY `MenuName` SEPARATOR '|') AS `menu_id`,
      GROUP_CONCAT(`MenuName` ORDER BY `MenuName` SEPARATOR '|') AS `menu_name`,
      GROUP_CONCAT(`ParentMenuId` ORDER BY `MenuName` SEPARATOR '|') AS `parent_menu`
   FROM 
      gtfw_group g
      JOIN gtfw_unit ut ON ut.UnitId=g.UnitId
      LEFT JOIN gtfw_group_menu gm ON g.GroupId=gm.GroupId
   WHERE
      GroupName like '%s' /*AND g.GroupId>2*/
   GROUP BY g.GroupId
   ORDER BY GroupName";   
   
$sql['get_data_group_by_id']= 
   "SELECT 	
	   g.GroupId AS group_id,
      g.GroupName AS groupname,
      g.Description AS description,
      g.UnitId AS group_unit_id,
      ut.UnitName AS unit_kerja,
      GROUP_CONCAT(`MenuId` ORDER BY `MenuName` SEPARATOR '|') AS `menu_id`,
      GROUP_CONCAT(`MenuName` ORDER BY `MenuName` SEPARATOR '|') AS `menu_name`,
      GROUP_CONCAT(`ParentMenuId` ORDER BY `MenuName` SEPARATOR '|') AS `parent_menu`
   FROM 
    	gtfw_group g
    	JOIN gtfw_unit ut ON ut.UnitId=g.UnitId
    	LEFT JOIN gtfw_group_menu gm ON g.GroupId=gm.GroupId
   WHERE
	    g.GroupId = '%s'
   GROUP BY g.GroupId";

$sql['get_last_group_id'] = 
   "SELECT MAX(GroupId) 
   FROM gtfw_group;";

$sql['is_can_access_menu'] = "
   SELECT
      count(*) as result
   FROM
      gtfw_group_menu m
   WHERE 
      MenuName='%s'
   AND groupId='%s'";
   
$sql['get_all_privilege'] = 
   "SELECT 
  	  dmMenuId AS menu_id,
  	  dmMenuName AS menu_name,
  	  dmMenuParentId AS menu_parent_id,
  	  dmMenuDefaultModuleId as default_module_id,
     dmIsShow as is_show
   FROM 
    	dummy_menu
   WHERE DmIsShow = 'Yes'
   ORDER BY DmMenuParentId, DmMenuName";

$sql['get_group_privilege'] = 
   "SELECT 
      MenuId AS menu_id,
      MenuName AS menu_name,
      ParentMenuId AS parent_menu_id,  
      ModuleId AS module_id
   FROM 
      gtfw_group g
      JOIN gtfw_group_menu gm ON g.groupId=gm.groupId
   WHERE 
      parentMenuId != 0 AND
      gm.groupId = '%s'";

$sql['get_privilege_by_id'] = 
    "SELECT 
      dmMenuId AS menu_id,
      dmMenuName AS menu_name,
      dmMenuParentId AS menu_parent_id,
      dmMenuDefaultModuleId as default_module_id,
      dmIsShow as is_show 
   FROM 
      dummy_menu
   WHERE 
      dmMenuId = '%s'";

$sql['get_privilege_by_array_id'] = 
    "SELECT 
      dmMenuId AS menu_id,
      dmMenuName AS menu_name,
      dmMenuParentId AS menu_parent_id,
      dmMenuDefaultModuleId as default_module_id,
      dmIsShow as is_show
   FROM 
      dummy_menu
   WHERE 
      dmMenuId IN (%s)";

$sql['get_max_menu_id']= "
   SELECT 
      MAX(MenuId) as max_id
   FROM
      gtfw_group_menu
   ";

$sql['get_data_group_menu'] = 
   "SELECT 
      MenuId AS menu_id,
      MenuName AS menu_name, 
      GroupId AS group_id 
	FROM 
      gtfw_group_menu
   WHERE 
     parentMenuId != 0";  
     
$sql['get_data_group_menu_by_group_id'] = 
   "SELECT 
      MenuId AS menu_id,
      MenuName AS menu_name, 
      GroupId AS group_id 
	FROM 
      gtfw_group_menu
   WHERE 
     ParentMenuId != 0 and GroupId='%s'";

$sql['get_combo_unit_kerja'] = "
   SELECT 
      UnitId AS id,
      UnitName AS name
   FROM gtfw_unit
";

//===DO===
$sql['do_add_user_def_group'] = "
   INSERT INTO gtfw_user_def_group
   SET
      UserId = %s,
      GroupId = %s,
      ApplicationId = %s
";

$sql['do_add_user_group'] = "
   INSERT INTO gtfw_user_group
   SET
      UserId = %s,
      GroupId = %s
";

$sql['do_add_group'] = 
   "INSERT INTO gtfw_group 
      (GroupName, Description,UnitId)
   VALUES
      ('%s','%s','%s')";

$sql['do_update_user_def_group'] = "
   UPDATE gtfw_user_def_group
   SET
      GroupId = %s,
      ApplicationId = %s
   WHERE
      UserId = '%s'
";

$sql['do_update_user_group'] = "
   UPDATE gtfw_user_group
   SET
      GroupId = %s
   WHERE
      UserId = %s
";
 
$sql['do_update_group'] =
   "UPDATE gtfw_group
   SET 
      GroupName='%s',
      Description='%s',
      UnitId='%s'
   WHERE 
      GroupId='%s'";
   
$sql['do_delete_group'] = 
   "DELETE FROM gtfw_group
   WHERE GroupId = '%s'";

$sql['do_add_privilege'] = 
   "INSERT INTO gtfw_user
      (UserName, Password, RealName, Decription, Active, GroupId)
   VALUES 
      ('%s', md5('%s'), '%s', '%s', 'Yes', '%s')";
      
$sql['do_add_group_menu_for_new_group'] = 
   "INSERT INTO gtfw_group_menu
      (MenuName, GroupId, ModuleId, ParentMenuId, IsShow)
   SELECT 
      '%s', MAX(GroupId), '%s', '%s', '%s'
      FROM gtfw_group";
      
$sql['do_add_group_menu'] = 
   "INSERT INTO gtfw_group_menu
      (MenuName, GroupId, ModuleId, ParentMenuId, IsShow)
   VALUES 
      ('%s', '%s', '%s', '%s', '%s')";
      
$sql['do_add_group_module_by_module_name_new_group'] = "
   INSERT INTO 
      gtfw_group_module
   SELECT MAX(g.GroupId), m.ModuleId
      FROM gtfw_module m, gtfw_group g
      WHERE module='%s'
      GROUP BY ModuleId ";
      
$sql['do_add_group_module_by_module_name'] = "
   INSERT INTO 
      gtfw_group_module
   SELECT %d, m.ModuleId
      FROM gtfw_module m
      WHERE module='%s'
      GROUP BY ModuleId ";
      
$sql['do_add_group_module_from_dummy_menu_new_group'] = "
   INSERT INTO 
      gtfw_group_module
   SELECT MAX(g.GroupId), m.ModuleId
      FROM gtfw_module m JOIN dummy_module b ON m.moduleId = b.moduleId , gtfw_group g
      WHERE DmMenuId='%s'
      GROUP BY m.ModuleId ";
      
$sql['do_add_group_module_from_dummy_menu'] = "
   INSERT INTO 
      gtfw_group_module
   SELECT '%s', b.ModuleId FROM gtfw_module a
      JOIN dummy_module b ON a.moduleId = b.moduleId
      WHERE b.DmMenuId='%s' ";

$sql['do_add_group_module'] =
   "INSERT INTO gtfw_group_module
      (GroupId, ModuleId)
   VALUES
      ('%s', '%s')";
      
$sql['do_add_group_module_newgroup'] =
   "INSERT INTO gtfw_group_module
      (GroupId, ModuleId)
   SELECT MAX(GroupId), '%s'
      FROM gtfw_group";
      
         
$sql['do_delete_group_menu'] =
   "DELETE 
   FROM 
      gtfw_group_menu
   WHERE 
      GroupId='%s'";

$sql['do_delete_group_module']  = 
   "DELETE 
   FROM 
      gtfw_group_module
   WHERE 
      GroupId='%s'";

?>
