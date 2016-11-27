<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(
phslId) AS total
FROM 
   pub_ref_penghasilan
WHERE 
   phslNama LIKE %s
LIMIT 1
   ";   

$sql['update_penghasilan_order']="
UPDATE
   pub_ref_penghasilan
SET 
	phslUrutan = '%s'
WHERE
	phslId = %s
";

$sql['mass_update_penghasilan_order']="
UPDATE
   pub_ref_penghasilan
SET 
	phslUrutan = phslUrutan + '%s'
WHERE
	phslUrutan
BETWEEN %s AND %s
";

$sql['update_penghasilan_order_delete']="
UPDATE
   pub_ref_penghasilan
SET 
	phslUrutan = phslUrutan - 1
WHERE
	phslUrutan > %s 
";

$sql['get_data']="
SELECT 
   phslId,
   phslNama,
   phslUrutan,
   phslCreationDate,
   phslLastUpdate,
   phslUserId
FROM 
   pub_ref_penghasilan
WHERE 
   phslNama LIKE %s
ORDER BY 
   phslUrutan

";

$sql['get_data_by_id']="
SELECT 
   phslId,
   phslNama,
   phslUrutan
FROM 
   pub_ref_penghasilan
WHERE 
   phslId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO `pub_ref_penghasilan`(
	`phslNama`,`phslUrutan`,`phslCreationDate`,`phslUserId`
)SELECT '%s', IFNULL(MAX(`phslUrutan`)+1,'1') `as`, now(),'%s'
FROM `pub_ref_penghasilan` 
";

$sql['do_update'] = "
UPDATE pub_ref_penghasilan
SET phslNama = '%s',
	phslLastUpdate = now(),
	phslUserId = '%s'
WHERE 
	phslId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   pub_ref_penghasilan
WHERE 
   phslId = %s   
";
?>
