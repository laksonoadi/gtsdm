<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(tksmnrId) AS total
FROM 
   sdm_ref_tingkat_seminar
WHERE 
   tksmnrNama LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   tksmnrId,
   tksmnrNama
FROM 
   sdm_ref_tingkat_seminar
WHERE 
   tksmnrNama LIKE %s
ORDER BY 
   tksmnrNama

";

$sql['get_data_by_id']="
SELECT 
   tksmnrNama
FROM 
   sdm_ref_tingkat_seminar
WHERE 
   tksmnrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_tingkat_seminar
   (tksmnrNama)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_tingkat_seminar
SET tksmnrNama = '%s'
WHERE 
	tksmnrId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_tingkat_seminar
WHERE 
   tksmnrId = %s   
";
?>
