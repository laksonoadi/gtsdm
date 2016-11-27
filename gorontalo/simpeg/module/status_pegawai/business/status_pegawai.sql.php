<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(statrId) AS total
FROM 
   sdm_ref_status_pegawai
WHERE 
   statrPegawai LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   statrId,
   statrPegawai
FROM 
   sdm_ref_status_pegawai
WHERE 
   statrPegawai LIKE %s
ORDER BY 
   statrPegawai

";

$sql['get_data_by_id']="
SELECT 
   statrPegawai
FROM 
   sdm_ref_status_pegawai
WHERE 
   statrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_status_pegawai
   (statrPegawai)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_status_pegawai
SET statrPegawai = '%s'
WHERE 
	statrId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_status_pegawai
WHERE 
   statrId = %s   
";
?>
