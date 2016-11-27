<?php
//===GET===
$sql['count'] = "
SELECT 
	COUNT(bahasaId) AS total
FROM pub_ref_bahasa
WHERE 
	bahasaNama LIKE '%s'
";   

$sql['get']="
SELECT 
	bahasaId AS id,
	bahasaNama AS nama
FROM pub_ref_bahasa
WHERE 
	bahasaNama LIKE '%s'
ORDER BY bahasaNama
LIMIT %s,%s
";

$sql['getById']="
SELECT 
	bahasaId AS id,
	bahasaNama AS nama
FROM pub_ref_bahasa
WHERE bahasaId = '%s'
";

//===SET===
$sql['add'] = "
INSERT INTO pub_ref_bahasa SET
	bahasaNama = '%s'
";

$sql['update'] = "
UPDATE pub_ref_bahasa SET
	bahasaNama = '%s'
WHERE bahasaId = '%s'
";  

$sql['delete'] = "
DELETE FROM pub_ref_bahasa
WHERE bahasaId = '%s'
";
?>