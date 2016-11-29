<?php
//===GET===
$sql['count'] = "
SELECT 
	COUNT(tandajasaId) AS total
FROM sdm_ref_tanda_jasa
WHERE 
	tandajasaNama LIKE '%s'
";   

$sql['get']="
SELECT 
	tandajasaId AS id,
	tandajasaNama AS nama,
	tandajasaDeskripsi AS deskripsi
FROM sdm_ref_tanda_jasa
WHERE 
	tandajasaNama LIKE '%s'
ORDER BY tandajasaNama
LIMIT %s,%s
";

$sql['getById']="
SELECT 
	tandajasaId AS id,
	tandajasaNama AS nama,
	tandajasaDeskripsi AS deskripsi
FROM sdm_ref_tanda_jasa
WHERE tandajasaId = '%s'
";

//===SET===
$sql['add'] = "
INSERT INTO sdm_ref_tanda_jasa SET
	tandajasaNama = '%s',
	tandajasaDeskripsi = '%s'
";

$sql['update'] = "
UPDATE sdm_ref_tanda_jasa SET
	tandajasaNama = '%s',
	tandajasaDeskripsi = '%s'
WHERE tandajasaId = '%s'
";  

$sql['delete'] = "
DELETE FROM sdm_ref_tanda_jasa
WHERE tandajasaId = '%s'
";
?>