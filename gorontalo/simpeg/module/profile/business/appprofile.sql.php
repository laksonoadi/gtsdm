<?php
$sql['get_data_user_by_username'] = 
   "SELECT 
      u.UserId AS user_id,
      UserName AS user_name,
      RealName AS real_name,
      u.Description AS description,
      Active AS is_active,
      g.GroupId AS group_id,
      GroupName AS group_name,
      password
   FROM 
      gtfw_user u,
      gtfw_group g,
      gtfw_user_def_group gd
   WHERE 
      u.UserId=gd.UserId
      and
      g.GroupId=gd.GroupId
	and
	UserName='%s'"; 

$sql['get_data_user_by_id'] = 
   "SELECT 
      u.UserId AS user_id,
      UserName AS user_name,
      RealName AS real_name,
      u.Description AS description,
      Active AS is_active,
      g.GroupId AS group_id,
      GroupName AS group_name,
      password
   FROM 
      gtfw_user u,
      gtfw_group g,
      gtfw_user_def_group gd
   WHERE 
      u.UserId=gd.UserId
      and
      g.GroupId=gd.GroupId
	and
	u.UserId='%s'"; 

$sql['get_data_group_by_id']= 
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
	    g.GroupId = '%s'
   GROUP BY g.GroupId";

$sql['do_max_group_concat']=
   "
   SET SESSION group_concat_max_len = 1000000;
   ";

$sql['do_update_profile'] = 
   "UPDATE gtfw_user
   SET 
      RealName='%s',
      Description ='%s'
   WHERE 
      UserId='%s'";
$sql['do_update_password']=
   "UPDATE 
      gtfw_user 
   SET Password='%s' 
   WHERE UserId='%s'";

$sql['get_profile_pegawai']="
SELECT 
	pegId as id,
	pegNama as name,
	pegKodeResmi as kode,
	pegAlamat as alamat,
	pegNoTelp as telp,
	pegSatwilId as wil,
	pegFoto as foto,
	substring(pegTglMasukInstitusi,1,4) as masuk
FROM
	pub_pegawai
	LEFT JOIN sdm_pegawai_detail ON pegdtPegId = pegId
WHERE
	pegId='%s'
";
?>
