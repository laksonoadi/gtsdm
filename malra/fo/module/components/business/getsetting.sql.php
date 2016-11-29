<?php 
$sql['get_value_by_key'] = '
	SELECT 
		configValue as `value`
	FROM
		gtfw_config
	WHERE
		configKode = %s
';
?>
