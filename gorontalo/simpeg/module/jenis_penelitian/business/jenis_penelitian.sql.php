<?php
//===GET===
$sql['get_data_by_id']="
SELECT 
   jnsPenelitianNama as nama
FROM 
   sdm_ref_jenis_penelitian
WHERE 
   jnspenelitianId = %s
";

$sql['get_count'] = "
SELECT 
   COUNT(jnspenelitianId) AS total
FROM 
   sdm_ref_jenis_penelitian
WHERE 
   jnsPenelitianNama LIKE %s
LIMIT 1
   ";   

$sql['get_data']="
SELECT 
   jnspenelitianId as id,
   jnsPenelitianNama as nama
FROM 
   sdm_ref_jenis_penelitian
WHERE 
   jnsPenelitianNama LIKE %s
ORDER BY 
   jnspenelitianId
LIMIT %s,%s
";



// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_jenis_penelitian
   (jnsPenelitianNama)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_jenis_penelitian
SET jnsPenelitianNama = '%s'
WHERE 
	jnspenelitianId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_jenis_penelitian
WHERE 
   jnspenelitianId = %s   
";
?>
