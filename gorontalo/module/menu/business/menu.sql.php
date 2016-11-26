<?php
$sql['nav'] = "
	select distinct 
		dm.DmMenuId,
		gm.MenuName, 
		gmod.Module,
		gmod.SubModule,
		gmod.Action,
		gmod.Type,
		CONCAT('&mid=',gm.MenuId, '&dmmid=',dm.DmMenuId) AS url,
		a.MenuName as subMenu,
		a.Module AS subMenuModule,
		a.SubModule AS subMenuSubModule,
		a.Action AS subMenuAction,
		a.Type AS subMenuType
	from 
		gtfw_group_menu gm 
	left join 
		gtfw_module gmod on (gm.ModuleId = gmod.ModuleId)
	left join 
		gtfw_group_module ggm on (gm.ModuleId = ggm.ModuleId)
	left join 
		gtfw_user_def_group gudg ON gudg.GroupId = gm.GroupId 
	left join
   	gtfw_user gu on (gudg.UserId = gu.UserId)
	inner join
		dummy_menu dm on (dm.DmMenuName = gm.MenuName)
	LEFT JOIN
	(
	select 
		distinct gm.ParentMenuId,
		gm.MenuName, 
		gmod.Module,
		gmod.SubModule,
		gmod.Action,
		gmod.Type,
		DmMenuOrder
	from gtfw_group_menu gm 
	left join gtfw_module gmod on (gm.ModuleId = gmod.ModuleId) 
	left join gtfw_group_module ggm on (gm.ModuleId = ggm.ModuleId) 
   inner join dummy_menu dm on (dm.DmMenuName = gm.MenuName)
	where IsShow='Yes' 
   order by dm.DmMenuOrder ASC
	) a ON a.ParentMenuId = gm.MenuId
	where (gm.ParentMenuId = 0) 
		and gu.userName = '%s'
		AND dm.DmIsShow = '%s'
	order by dm.DmMenuOrder, a.DmMenuOrder ASC
";
$sql['list_available_menu_with_flag_show'] = 
"select distinct 
   gm.MenuId, 
   gm.MenuName, 
   gmod.Module, 
   gmod.SubModule, 
   gmod.Action,
   gmod.Type, 
   gmod.Description, 
   gm.ParentMenuId,
   dm.DmMenuId
from 
   gtfw_group_menu gm 
left join 
   gtfw_module gmod on (gm.ModuleId = gmod.ModuleId)
left join 
   gtfw_group_module ggm on (gm.ModuleId = ggm.ModuleId)
left join 
	gtfw_user_def_group gudg ON gudg.GroupId = gm.GroupId 
left join
   gtfw_user gu on (gudg.UserId = gu.UserId)
inner join
   dummy_menu dm on (dm.DmMenuName = gm.MenuName)
where (gm.ParentMenuId = 0) 
   and gu.userName = '%s'
   AND dm.DmIsShow = '%s'
order by dm.DmMenuOrder
"; 

$sql['list_available_menu'] = 
"select distinct 
   gm.MenuId, 
   gm.MenuName, 
   gmod.Module, 
   gmod.SubModule, 
   gmod.Action,
   gmod.Type, 
   gmod.Description, 
   gm.ParentMenuId
from 
   gtfw_group_menu gm 
left join 
   gtfw_module gmod on (gm.ModuleId = gmod.ModuleId)
left join 
	gtfw_user_def_group gudg ON gudg.GroupId = gm.GroupId 
left join
   gtfw_user gu on (gudg.UserId = gu.UserId)
inner join
   dummy_menu dm on (dm.DmMenuName = gm.MenuName)
where (gm.ParentMenuId = 0) and gu.UserName= '%s'
order by dm.DmMenuOrder
"; 
// where (gm.ParentMenuId = 0) and gm.GroupId= '%s'";

$sql['list_all_available_submenu_for_group'] = 
"select distinct
   gm.MenuId, 
   gm.MenuName, 
   gmod.Module, 
   gmod.SubModule, 
   gmod.Action, 
   gmod.Type, 
   gmod.Description, 
   gm.ParentMenuId,
   dm.DmIconPath,
   dm.DmMenuId
from 
   gtfw_group_menu gm 
left join 
   gtfw_module gmod on (gm.ModuleId = gmod.ModuleId)
left join 
   gtfw_group_module ggm on (gm.ModuleId = ggm.ModuleId)
left join 
	gtfw_user_def_group gudg ON gudg.GroupId = gm.GroupId 
left join
   gtfw_user gu on (gudg.UserId = gu.UserId)left join
   dummy_menu dm on (dm.DmMenuName = gm.MenuName)
where gu.UserName= '%s' and dm.DmMenuParentId = '%s' AND dm.DmIsShow='Yes'
order by dm.DmMenuOrder, MenuName ASC

"; 

$sql['list_available_submenu'] = 
"select distinct 
   gm.MenuId, 
   gm.MenuName, 
   gmod.Module, 
   gmod.SubModule, 
   gmod.Action, 
   gmod.Type, 
   gmod.Description
from 
   gtfw_group_menu gm 
left join 
   gtfw_module gmod on (gm.ModuleId = gmod.ModuleId)
left join 
   gtfw_group_module ggm on (gm.ModuleId = ggm.ModuleId)
where (gm.ParentMenuId = '%s')
order by gm.MenuName ASC
";

$sql['list_available_submenu_with_flag_show'] = 
"select distinct 
   gm.MenuId, 
   gm.MenuName, 
   gmod.Module, 
   gmod.SubModule, 
   gmod.Action, 
   gmod.Type, 
   gmod.Description, 
   gm.ParentMenuId
from 
   gtfw_group_menu gm 
left join 
   gtfw_module gmod on (gm.ModuleId = gmod.ModuleId)
left join 
   gtfw_group_module ggm on (gm.ModuleId = ggm.ModuleId)
where gm.ParentMenuId = '%s'
AND IsShow='%s'
order by gm.MenuOrder ASC
";

?>    
