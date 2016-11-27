<?php
//===GET===
$sql['get_data_by_id']="
SELECT 
   jnsphgrNama as nama
FROM 
   sdm_ref_jenis_penghargaan
WHERE 
   jnsphgrId = %s
";

$sql['get_count'] = "
SELECT 
   COUNT(jnsphgrId) AS total
FROM 
   sdm_ref_jenis_penghargaan
WHERE 
   jnsphgrNama LIKE %s
LIMIT 1
   ";   

$sql['get_data']="
SELECT 
   jnsphgrId as id,
   jnsphgrNama as nama
FROM 
   sdm_ref_jenis_penghargaan
WHERE 
   jnsphgrNama LIKE %s
ORDER BY 
   jnsphgrId
LIMIT %s,%s
";



// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_jenis_penghargaan
   (jnsphgrNama)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_jenis_penghargaan
SET jnsphgrNama = '%s'
WHERE 
	jnsphgrId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_jenis_penghargaan
WHERE 
   jnsphgrId = %s   
";
?>
