<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(jnshkmrId) AS total
FROM 
   sdm_ref_jenis_hukuman
WHERE 
   jnshkmrNama LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   jnshkmrId,
   jnshkmrNama
FROM 
   sdm_ref_jenis_hukuman
WHERE 
   jnshkmrNama LIKE %s
ORDER BY 
   jnshkmrNama

";

$sql['get_data_by_id']="
SELECT 
   jnshkmrNama
FROM 
   sdm_ref_jenis_hukuman
WHERE 
   jnshkmrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_jenis_hukuman
   (jnshkmrNama)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_jenis_hukuman
SET jnshkmrNama = '%s'
WHERE 
	jnshkmrId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_jenis_hukuman
WHERE 
   jnshkmrId = %s   
";
?>
