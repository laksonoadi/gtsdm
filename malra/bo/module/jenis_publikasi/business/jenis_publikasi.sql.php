<?php
//===GET===
$sql['get_data_by_id']="
SELECT 
   jnspublikasiNama as nama
FROM 
   sdm_ref_jenis_publikasi
WHERE 
   jnspublikasiId = %s
";

$sql['get_count'] = "
SELECT 
   COUNT(jnspublikasiId) AS total
FROM 
   sdm_ref_jenis_publikasi
WHERE 
   jnspublikasiNama LIKE %s
LIMIT 1
   ";   

$sql['get_data']="
SELECT 
   jnspublikasiId as id,
   jnspublikasiNama as nama
FROM 
   sdm_ref_jenis_publikasi
WHERE 
   jnspublikasiNama LIKE %s
ORDER BY 
   jnspublikasiId
LIMIT %s,%s
";



// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_jenis_publikasi
   (jnspublikasiNama)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_jenis_publikasi
SET jnspublikasiNama = '%s'
WHERE 
	jnspublikasiId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_jenis_publikasi
WHERE 
   jnspublikasiId = %s   
";
?>
