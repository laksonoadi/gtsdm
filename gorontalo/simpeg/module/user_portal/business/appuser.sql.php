<?php

//===GET===
$sql['get_count_data_user_by_instansi'] = 
   "SELECT 
      count(UserId) AS total
   FROM 
      gtfw_user u
      JOIN ref_instansi r ON u.UserRefInstansiId=r.refInstansiId
   WHERE
      RealName like '%s' and UserRefInstansiId like '%s'";
      
$sql['get_count_data_user'] = "
   SELECT 
      count(u.UserId) AS total
   FROM 
      gtfw_user u
      JOIN gtfw_user_def_group dg ON u.UserId = dg.UserId
      JOIN gtfw_group g ON dg.GroupId = g.GroupId
      JOIN gtfw_unit ut ON ut.UnitId = g.UnitId
   WHERE
      u.userId>2 AND
      UserName like '%s' AND
      RealName like '%s'
";
/*
$sql['get_data_user'] = 
   "SELECT 
      u.UserId AS user_id,
      UserName AS user_name,
      RealName AS real_name,
      u.Description AS description,
      Active AS is_active,
      g.GroupId AS group_id,
      GroupName AS group_name
   FROM 
      gtfw_user u
      LEFT JOIN gtfw_user_group a ON a.UserId = u.UserId
      JOIN gtfw_group g ON a.GroupId=g.GroupId      
   WHERE
      u.userId!=1 AND
      UserName like '%s' %s
      RealName like '%s'
   ORDER BY 
      UserName, RealName
   LIMIT %s, %s";
*/

$sql['add_user_group'] = "
   INSERT INTO gtfw_user_group(UserId,GroupId) VALUES ((select UserId from gtfw_user order by UserId desc limit 0,1),'%s')
";

$sql['update_user_group'] = "
   UPDATE gtfw_user_group set GroupId = '%s' WHERE UserId = '%s'
";

$sql['get_data_user'] = "
   SELECT 
      u.UserId AS user_id,
      u.UserName AS user_name,
      u.RealName AS real_name,
      u.Description AS description,
      u.Active AS is_active,
      dg.GroupId AS group_id,
      g.GroupName AS group_name,
      ut.UnitName AS unit_name
   FROM
      gtfw_user u
      JOIN gtfw_user_def_group dg ON u.UserId = dg.UserId
      JOIN gtfw_group g ON dg.GroupId = g.GroupId
      JOIN gtfw_unit ut ON ut.UnitId = g.UnitId
   WHERE
      u.userId > 2 AND
      UserName like '%s' %s
      RealName like '%s'
   ORDER BY 
      UserName, RealName
   LIMIT %s, %s
";
   
$sql['get_data_user_by_instansi'] = 
   "SELECT 
      UserId AS user_id,
      UserName AS user_name,
      RealName AS real_name,
      UserRefInstansiId AS instansi_id,
      r.refInstansiNama AS instansi_nama
   FROM 
      gtfw_user u
      JOIN ref_instansi r ON u.UserRefInstansiId=r.refInstansiId
   WHERE
      RealName like '%%%s%%' and UserRefInstansiId like '%s'
   LIMIT %d, %d";   

$sql['get_data_user_by_id'] = "
   SELECT 
      u.UserId AS user_id,
      u.UserName AS user_name,
      u.RealName AS real_name,
      u.Description AS description,
      u.Active AS is_active,
      dg.GroupId AS group_id,
      g.GroupName AS group_name,
      ut.UnitName AS unit_name,
      ut.UnitId AS unit_kerja_id
   FROM
      gtfw_user u
      JOIN gtfw_user_def_group dg ON u.UserId = dg.UserId
      JOIN gtfw_group g ON dg.GroupId = g.GroupId
      JOIN gtfw_unit ut ON ut.UnitId = g.UnitId
   WHERE
      u.UserId = '%s'
";

$sql['get_count_duplicate_username'] = "
   SELECT 
      COUNT(*) AS COUNT
   FROM
      gtfw_user
   WHERE
      UserName = '%s'
      AND UserId != '%s'
";
   
/*$sql['get_data_user_by_id'] = 
   "SELECT 
      UserId AS user_id,
      UserName AS user_name,
      RealName AS real_name,
      u.Description AS description,
      Active AS is_active,
      u.GroupId AS group_id,
      GroupName AS group_name,
      password,
      userunitkerjaUnitkerjaId AS unit_kerja_id,
      userunitkerjaRoleId AS role_id
   FROM 
      gtfw_user u
      JOIN gtfw_group g ON u.GroupId=g.GroupId
      LEFT JOIN user_unit_kerja ON(UserId=userunitkerjaUserId)
   WHERE
      UserName='%s'";   
   */

$sql['get_combo_unit_kerja'] = "
   SELECT 
      UnitId AS id,
      UnitName AS name
   FROM gtfw_unit
";

$sql['get_data_group_with_privilege'] = 
   "SELECT 
      g.GroupId AS group_id,
      g.GroupName AS group_name,
      g.Description AS group_description,
      GROUP_CONCAT(`MenuId` ORDER BY `MenuName` SEPARATOR '|') AS `menu_id`,
      GROUP_CONCAT(`MenuName` ORDER BY `MenuName` SEPARATOR '|') AS `menu_name`,
      GROUP_CONCAT(`ParentMenuId` ORDER BY `MenuName` SEPARATOR '|') AS `parent_menu`
   FROM 
      gtfw_group g
      LEFT JOIN gtfw_group_menu gm ON g.GroupId=gm.GroupId
   WHERE
      GroupName like '%s' /*AND g.GroupId>2*/
   GROUP BY g.GroupId
   ORDER BY GroupName";  

$sql['get_data_group'] = 
   "SELECT 
      GroupId AS id,
      GroupName AS name,
      Description AS group_description
   FROM 
      gtfw_group
   WHERE
      GroupName like '%s' 
   /*AND 
      GroupId != '2'*/
   ORDER BY 
      GroupName";

$sql['get_data_group_by_unit_id'] = 
   "SELECT 
      GroupId AS id,
      GroupName AS name,
      Description AS group_description
   FROM 
      gtfw_group
   WHERE
      GroupName like '%s' and UnitId = '%s'
   /*AND 
      GroupId != '2'*/
   ORDER BY 
      GroupName";

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
   
$sql['do_add_user'] = "
   INSERT INTO gtfw_user
      (UserName, Password, RealName, Description, Active)
   VALUES 
      ('%s', md5('%s'), '%s', '%s', '%s')
";
      
$sql['do_update_user'] = 
   "UPDATE gtfw_user
   SET 
      UserName='%s',
      RealName = '%s', 
      Active='%s', 
      Description = '%s'
   WHERE 
      UserId=%s";
   
$sql['do_delete_user_by_id'] = 
   "DELETE from gtfw_user 
   WHERE 
      UserId=%s";

$sql['do_delete_user_by_array_id'] = 
   "DELETE from gtfw_user
   WHERE 
      UserId IN ('%s')";
      
$sql['do_update_password_user'] = 
   "UPDATE gtfw_user
   SET 
      Password=md5('%s')
   WHERE 
      UserId='%s'";

$sql['do_update_profile'] = 
   "UPDATE gtfw_user
   SET 
      RealName='%s',
      Description ='%s'
   WHERE 
      UserId='%s'";

$sql['do_add_no_peg'] = "
   INSERT INTO 
      user_petugas_mst(userpetugas_user_id,userpetugas_no_pegawai)
   VALUES
      ((select UserId from gtfw_user order by UserId desc limit 0,1),'%s')
   ";

$sql['do_update_add_no_peg'] = "
   INSERT INTO 
      user_petugas_mst(userpetugas_user_id,userpetugas_no_pegawai)
   VALUES
      ('%s','%s')
   ";

$sql['do_update_no_peg'] = "
   UPDATE user_petugas_mst SET 
      userpetugas_no_pegawai = '%s'
   WHERE
      userpetugas_user_id = '%s'
";

   $sql['get_no_pegawai']="
      SELECT userpetugas_user_id FROM user_petugas_mst WHERE userpetugas_user_id = '%s'
   ";

   $sql['get_max_id'] = "
      SELECT MAX(UserId) AS id
      FROM gtfw_user
   ";
      
?>
