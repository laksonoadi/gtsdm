<?php
//===GET===
$sql['count'] = "
SELECT 
	COUNT(sptkategoriId) AS total
FROM sdm_spt_kategori
WHERE 
	sptkategoriNama LIKE '%s'
";   

$sql['get']="
SELECT 
	sptkategoriId AS id,
	sptkategoriNama AS nama
FROM sdm_spt_kategori
WHERE 
	sptkategoriNama LIKE '%s'
ORDER BY sptkategoriNama
LIMIT %s,%s
";

$sql['getById']="
SELECT 
	sptkategoriId AS id,
	sptkategoriNama AS nama
FROM sdm_spt_kategori
WHERE 
   sptkategoriId = '%s'
";

//===SET===
$sql['add'] = "
INSERT INTO sdm_spt_kategori SET
	sptkategoriNama = '%s'
";

$sql['update'] = "
UPDATE sdm_spt_kategori SET
	sptkategoriNama = '%s'
WHERE sptkategoriId = '%s'
";  

$sql['delete'] = "
DELETE FROM sdm_spt_kategori
WHERE sptkategoriId = '%s'
";
?>