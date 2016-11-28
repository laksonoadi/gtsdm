<?php 
//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(bidangilmurId) AS total
FROM 
   sdm_ref_kepakaran_bidang_ilmu
WHERE 
   bidangilmurNama LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   bidangilmurId,
   bidangilmurNama
FROM 
   sdm_ref_kepakaran_bidang_ilmu
WHERE 
   bidangilmurNama LIKE %s
ORDER BY 
   bidangilmurNama

";

$sql['get_data_by_id']="
SELECT 
   bidangilmurNama
FROM 
   sdm_ref_kepakaran_bidang_ilmu
WHERE 
   bidangilmurId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_kepakaran_bidang_ilmu
   (bidangilmurNama)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_kepakaran_bidang_ilmu
SET bidangilmurNama = '%s'
WHERE 
	bidangilmurId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_kepakaran_bidang_ilmu
WHERE 
   bidangilmurId = %s   
";
?>
