<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(jnskegrId) AS total
FROM 
   sdm_ref_jenis_kegiatan
WHERE 
   jnskegrNama LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   jnskegrId,
   jnskegrNama
FROM 
   sdm_ref_jenis_kegiatan
WHERE 
   jnskegrNama LIKE %s
ORDER BY 
   jnskegrNama

";

$sql['get_data_by_id']="
SELECT 
   jnskegrNama
FROM 
   sdm_ref_jenis_kegiatan
WHERE 
   jnskegrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_jenis_kegiatan
   (jnskegrNama)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_jenis_kegiatan
SET jnskegrNama = '%s'
WHERE 
	jnskegrId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_jenis_kegiatan
WHERE 
   jnskegrId = %s   
";
?>
