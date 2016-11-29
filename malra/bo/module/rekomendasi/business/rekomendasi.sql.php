<?php
//===GET===
$sql['get_data_by_id']="
SELECT 
   bkdjnsrekomenId as idrek,
   bkdjnsrekomenKode as kode,
   bkdjnsrekomenNama as nama
FROM 
   sdm_bkd_ref_rekomendasi
WHERE 
   bkdjnsrekomenId = '%s'
";

$sql['get_count'] = "
SELECT 
   COUNT(bkdjnsrekomenId) AS total
FROM 
   sdm_bkd_ref_rekomendasi
WHERE 
   bkdjnsrekomenNama LIKE %s
LIMIT 1
   ";   

$sql['get_kode'] = "
SELECT 
   COUNT(bkdjnsrekomenKode) AS cekCode
FROM 
   sdm_bkd_ref_rekomendasi
WHERE 
   bkdjnsrekomenKode = %s
LIMIT 1
   ";   

$sql['get_data']="
SELECT 
   bkdjnsrekomenId as idrek,
   bkdjnsrekomenKode as kode,
   bkdjnsrekomenNama as nama
FROM 
   sdm_bkd_ref_rekomendasi
WHERE 
   bkdjnsrekomenNama LIKE '%s'
AND
   bkdjnsrekomenNama != '-'
ORDER BY 
   bkdjnsrekomenId
LIMIT %s,%s
";



// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_bkd_ref_rekomendasi
   (bkdjnsrekomenKode,bkdjnsrekomenNama,bkdjnsrekomenCreatedDate)
VALUES('%s','%s',NOW())  
";

$sql['do_update'] = "
UPDATE sdm_bkd_ref_rekomendasi
SET 
	bkdjnsrekomenKode = '%s',
	bkdjnsrekomenNama = '%s',
	bkdjnsrekomenModifiedDate = NOW()
WHERE 
	bkdjnsrekomenId = '%d'
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_bkd_ref_rekomendasi
WHERE 
   bkdjnsrekomenId = %s   
";
?>
