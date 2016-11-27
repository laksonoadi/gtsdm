<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(tpstrId) AS total
FROM 
   sdm_ref_tipe_struktural
WHERE 
   tpstrId LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   a.tpstrId,
   a.tpstrNama,
   a.tpstrLevel
FROM 
   sdm_ref_tipe_struktural a
WHERE 
   a.tpstrNama LIKE %s
ORDER BY 
   a.tpstrId

";

$sql['get_data_by_id']="
SELECT 
   a.tpstrNama,
   a.tpstrLevel
FROM 
   sdm_ref_tipe_struktural a
WHERE 
   a.tpstrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_tipe_struktural
   (tpstrNama, tpstrLevel)
VALUES('%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_tipe_struktural
SET tpstrNama = '%s',
	tpstrLevel = '%s'
WHERE 
	tpstrId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_tipe_struktural
WHERE 
   tpstrId = %s   
";
?>
