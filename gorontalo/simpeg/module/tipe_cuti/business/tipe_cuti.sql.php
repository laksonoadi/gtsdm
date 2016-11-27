<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(tipecutiId) AS total
FROM 
   sdm_ref_tipe_cuti
WHERE 
   tipecutiNama LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   tipecutiId,
   tipecutiNama
FROM 
   sdm_ref_tipe_cuti
WHERE 
   tipecutiNama LIKE %s
ORDER BY 
   tipecutiNama ASC
LIMIT %s,%s
";

$sql['get_data_by_id']="
SELECT 
   tipecutiNama
FROM 
   sdm_ref_tipe_cuti
WHERE 
   tipecutiId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_tipe_cuti
   (tipecutiNama)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_tipe_cuti
SET tipecutiNama = '%s'
WHERE 
	tipecutiId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_tipe_cuti
WHERE 
   tipecutiId = %s   
";
?>
