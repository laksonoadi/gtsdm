<?php
$sql['get_module_by_file'] = "
	SELECT
		gtfw_module.*,
		MenuDefaultModuleId
	FROM
		gtfw_module
		LEFT JOIN gtfw_menu ON gtfw_module.MenuId=gtfw_menu.MenuId
	WHERE
		Module='%s' AND SubModule='%s' AND Action='%s' AND Type='%s' AND gtfw_module.ApplicationId=%s
";

$sql['get_parent_menu'] = "
	SELECT
		menuId as id,
		menuName as name
	FROM
		gtfw_menu
	WHERE
		MenuParentId=0 AND ApplicationId='%s'
";

$sql['register_module'] = "
	INSERT INTO gtfw_module (Module,LabelModule,SubModule,Action,Type,Access,ApplicationId)
	VALUES('%s','%s','%s','%s','%s','%s','%s')
";

$sql['last_register_module'] = "
	SELECT MAX(moduleId) as last_id FROM gtfw_module
";

$sql['register_menu'] = "
	INSERT INTO gtfw_menu (MenuParentId,MenuName,MenuDefaultModuleId,IsShow,IconPath,ApplicationId)
	VALUES('%s','%s','%s','%s','%s','%s')
";

$sql['update_register_menu'] = "
	UPDATE 
		gtfw_menu 
	SET
		MenuParentId='%s',
		MenuName='%s',
		MenuDefaultModuleId='%s',
		IsShow='%s',
		IconPath='%s',
		ApplicationId='%s'
	WHERE
		MenuId='%s'
";

$sql['last_register_menu'] = "
	SELECT MAX(menuId) as last_id FROM gtfw_menu
";

$sql['update_module_menu_id'] = "
	UPDATE 
		gtfw_module
	SET
		menuId=%s
	WHERE
		moduleId=%s
";

$sql['get_menu_by_id'] = "
	SELECT
		*
	FROM
		gtfw_menu
	WHERE
		menuId='%s'
";

$sql['update_menu_module_default'] = "
	UPDATE 
		gtfw_menu
	SET
		MenuDefaultModuleId=%s
	WHERE
		menuId=%s
";
 
?>