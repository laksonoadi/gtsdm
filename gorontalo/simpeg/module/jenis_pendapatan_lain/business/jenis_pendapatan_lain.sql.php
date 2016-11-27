<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(jnspndptnlainrId) AS total
FROM 
   sdm_ref_jenis_pendapatan_lain
WHERE 
   jnspndptnlainrId LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   a.jnspndptnlainrId,
   a.jnspndptnlainrNama
FROM 
   sdm_ref_jenis_pendapatan_lain a
WHERE 
   a.jnspndptnlainrNama LIKE %s
ORDER BY 
   a.jnspndptnlainrId

";

$sql['get_data_by_id']="
SELECT 
   a.jnspndptnlainrNama
FROM 
   sdm_ref_jenis_pendapatan_lain a
WHERE 
   a.jnspndptnlainrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_jenis_pendapatan_lain
   (jnspndptnlainrNama)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_jenis_pendapatan_lain
SET jnspndptnlainrNama = '%s'
WHERE 
	jnspndptnlainrId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_jenis_pendapatan_lain
WHERE 
   jnspndptnlainrId = %s   
";
?>
