<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(pendkelId) AS total
FROM 
   pub_ref_pendidikan_kelompok
WHERE 
   pendkelNama LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   pendkelId,
   pendkelNama,
   pendkelCreationDate,
   pendkelLastUpdate,
   pendkelUserId
FROM 
   pub_ref_pendidikan_kelompok
WHERE 
   pendkelNama LIKE %s
ORDER BY 
   pendkelId

";

$sql['get_data_by_id']="
SELECT 
   pendkelNama
FROM 
   pub_ref_pendidikan_kelompok
WHERE 
   pendkelId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   pub_ref_pendidikan_kelompok
   (pendkelNama, pendkelCreationDate, pendkelUserId)
VALUES('%s',now(),'%s')  
";

$sql['do_update'] = "
UPDATE pub_ref_pendidikan_kelompok
SET pendkelNama = '%s',
	pendkelLastUpdate = now(),
	pendkelUserId = '%s'
WHERE 
	pendkelId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   pub_ref_pendidikan_kelompok
WHERE 
   pendkelId = %s   
";
?>
