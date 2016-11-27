<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(jabfungjenisrId) AS total
FROM 
   pub_ref_jabatan_fungsional_jenis
WHERE 
   jabfungJenis LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   jabfungjenisrId,
   jabfungJenis
   
FROM 
   pub_ref_jabatan_fungsional_jenis
WHERE 
   jabfungJenis LIKE %s
ORDER BY 
   jabfungjenisrId

";

$sql['get_data_by_id']="
SELECT 
   jabfungJenis
FROM 
   pub_ref_jabatan_fungsional_jenis
WHERE 
   jabfungjenisrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   pub_ref_jabatan_fungsional_jenis
   (jabfungJenis)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE pub_ref_jabatan_fungsional_jenis
SET jabfungJenis = '%s'
WHERE 
	jabfungjenisrId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   pub_ref_jabatan_fungsional_jenis
WHERE 
   jabfungjenisrId = %s   
";
?>
