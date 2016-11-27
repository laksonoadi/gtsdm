<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(jnsklnrId) AS total
FROM 
   sdm_ref_jenis_kunjungan_ln
WHERE 
   jnsklnrNama LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   jnsklnrId,
   jnsklnrNama
FROM 
   sdm_ref_jenis_kunjungan_ln
WHERE 
   jnsklnrNama LIKE %s
ORDER BY 
   jnsklnrNama

";

$sql['get_data_by_id']="
SELECT 
   jnsklnrNama
FROM 
   sdm_ref_jenis_kunjungan_ln
WHERE 
   jnsklnrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_jenis_kunjungan_ln
   (jnsklnrNama)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_jenis_kunjungan_ln
SET jnsklnrNama = '%s'
WHERE 
	jnsklnrId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_jenis_kunjungan_ln
WHERE 
   jnsklnrId = %s   
";
?>
