<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(goldrhId) AS total
FROM 
   pub_ref_golongan_darah
WHERE 
   goldrhNama LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   goldrhId,
   goldrhNama,
   goldrhCreationDate,
   goldrhLastUpdate,
   goldrhUserId
FROM 
   pub_ref_golongan_darah
WHERE 
   goldrhNama LIKE %s
   ORDER BY goldrhId 
";

$sql['get_data_by_id']="
SELECT 
   goldrhNama
FROM 
   pub_ref_golongan_darah
WHERE 
   goldrhId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   pub_ref_golongan_darah
   (goldrhNama, goldrhCreationDate, goldrhUserId)
VALUES('%s',now(),'%s')  
";

$sql['do_update'] = "
UPDATE pub_ref_golongan_darah
SET goldrhNama = '%s',
	goldrhLastUpdate = now(),
	goldrhUserId = '%s'
WHERE 
	goldrhId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   pub_ref_golongan_darah
WHERE 
   goldrhId = %s   
";
?>
