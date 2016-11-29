<?php
//===GET===
$sql['count'] = "
SELECT 
	COUNT(UnitId) AS total
FROM gtfw_unit
WHERE 
	UnitName LIKE '%s'
";   

$sql['get'] = "
SELECT 
	UnitId AS id,
	UnitName AS nama,
	Description AS deskripsi
FROM gtfw_unit
WHERE 
	UnitName LIKE '%s'
ORDER BY UnitName ASC
LIMIT %s,%s
";

$sql['getById']="
SELECT 
	UnitId AS id,
	UnitName AS nama,
	Description AS deskripsi
FROM gtfw_unit
WHERE UnitId = '%s'
";

//===SET===
$sql['add'] = "
INSERT INTO gtfw_unit SET
	UnitName = '%s',
	Description = '%s'
";

$sql['update'] = "
UPDATE gtfw_unit SET
	UnitName = '%s',
	Description = '%s'
WHERE UnitId = '%s'
";  

$sql['delete'] = "
DELETE FROM gtfw_unit
WHERE UnitId = '%s'
";
?>