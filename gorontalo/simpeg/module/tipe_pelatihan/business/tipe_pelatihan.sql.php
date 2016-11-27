<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(tppelrId) AS total
FROM 
   sdm_ref_tipe_pelatihan
WHERE 
   tppelrNama LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   tppelrId,
   tppelrNama
FROM 
   sdm_ref_tipe_pelatihan
WHERE 
   tppelrNama LIKE %s
ORDER BY 
   tppelrNama

";

$sql['get_data_by_id']="
SELECT 
   tppelrNama
FROM 
   sdm_ref_tipe_pelatihan
WHERE 
   tppelrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_tipe_pelatihan
   (tppelrNama)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_tipe_pelatihan
SET tppelrNama = '%s'
WHERE 
	tppelrId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_tipe_pelatihan
WHERE 
   tppelrId = %s   
";
?>
