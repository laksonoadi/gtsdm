<?php
//===GET===
$sql['get_data_by_id']="
SELECT 
   asldnrId as kode,
   asldnrNama as nama
FROM 
   sdm_ref_asal_dana
WHERE 
   asldnrId = '%s'
";

$sql['get_count'] = "
SELECT 
   COUNT(asldnrId) AS total
FROM 
   sdm_ref_asal_dana
WHERE 
   asldnrNama LIKE %s
LIMIT 1
   ";   

$sql['get_data']="
SELECT 
   asldnrId as kode,
   asldnrNama as nama
FROM 
   sdm_ref_asal_dana
WHERE 
   asldnrNama LIKE '%s'
ORDER BY 
   asldnrId
LIMIT %s,%s
";



// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_asal_dana
   (asldnrId,asldnrNama)
VALUES('%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_asal_dana
SET 
  asldnrId = '%s',
  asldnrNama = '%s'
WHERE 
	asldnrId = '%d'
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_asal_dana
WHERE 
   asldnrId = %s   
";
?>
