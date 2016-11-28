<?php
//===GET===
$sql['get_data_by_id']="
SELECT 
   jtkNama as nama
FROM 
   sdm_ref_jenis_tunjangan_kesehatan
WHERE 
   jtkId = %s
";

$sql['get_count'] = "
SELECT 
   COUNT(jtkId) AS total
FROM 
   sdm_ref_jenis_tunjangan_kesehatan
WHERE 
   jtkNama LIKE %s
LIMIT 1
   ";   

$sql['get_data']="
SELECT 
   jtkId as id,
   jtkNama as nama
FROM 
   sdm_ref_jenis_tunjangan_kesehatan
WHERE 
   jtkNama LIKE %s
ORDER BY 
   jtkId
LIMIT %s,%s
";



// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_jenis_tunjangan_kesehatan
   (jtkNama)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_jenis_tunjangan_kesehatan
SET jtkNama = '%s'
WHERE 
	jtkId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_jenis_tunjangan_kesehatan
WHERE 
   jtkId = %s   
";
?>
