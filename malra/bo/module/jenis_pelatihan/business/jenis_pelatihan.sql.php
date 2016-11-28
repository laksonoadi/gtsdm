<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(jnspelrId) AS total
FROM 
   sdm_ref_jenis_pelatihan
WHERE 
   jnspelrId LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   a.jnspelrId,
   a.jnspelrNama,
   a.jnspelrTingkat
FROM 
   sdm_ref_jenis_pelatihan a
WHERE 
   a.jnspelrNama LIKE %s
ORDER BY 
   a.jnspelrId

";

$sql['get_data_by_id']="
SELECT 
   a.jnspelrNama,
   a.jnspelrTingkat
FROM 
   sdm_ref_jenis_pelatihan a
WHERE 
   a.jnspelrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_jenis_pelatihan
   (jnspelrNama, jnspelrTingkat)
VALUES('%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_jenis_pelatihan
SET jnspelrNama = '%s',
	jnspelrTingkat = '%s'
WHERE 
	jnspelrId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_jenis_pelatihan
WHERE 
   jnspelrId = %s   
";
?>
