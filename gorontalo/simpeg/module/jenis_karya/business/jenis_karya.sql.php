<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(jnskryrId) AS total
FROM 
   sdm_ref_jenis_karya
WHERE 
   jnskryrNama LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   jnskryrId,
   jnskryrNama
FROM 
   sdm_ref_jenis_karya
WHERE 
   jnskryrNama LIKE %s
ORDER BY 
   jnskryrNama

";

$sql['get_data_by_id']="
SELECT 
   jnskryrNama
FROM 
   sdm_ref_jenis_karya
WHERE 
   jnskryrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_jenis_karya
   (jnskryrNama)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_jenis_karya
SET jnskryrNama = '%s'
WHERE 
	jnskryrId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_jenis_karya
WHERE 
   jnskryrId = %s   
";
?>
