<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(jnsbukuId) AS total
FROM 
   sdm_ref_jenis_buku
WHERE 
   jnsbukuNama LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   jnsbukuId,
   jnsbukuNama
FROM 
   sdm_ref_jenis_buku
WHERE 
   jnsbukuNama LIKE %s
ORDER BY 
   jnsbukuId

";

$sql['get_data_by_id']="
SELECT 
   jnsbukuNama
FROM 
   sdm_ref_jenis_buku
WHERE 
   jnsbukuId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_jenis_buku
   (jnsbukuNama)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_jenis_buku
SET jnsbukuNama = '%s'
WHERE 
	jnsbukuId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_jenis_buku
WHERE 
   jnsbukuId = %s   
";
?>
