<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(wnId) AS total
FROM 
   pub_ref_warga_negara
WHERE 
   wnNama LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   wnId,
   wnKode,
   wnNama,
   wnCreationDate,
   wnLastUpdate,
   wnUserId
FROM 
   pub_ref_warga_negara
WHERE 
   wnNama LIKE %s
ORDER BY 
   wnId

";

$sql['get_data_by_id']="
SELECT 
   wnNama,
   wnKode
FROM 
   pub_ref_warga_negara
WHERE 
   wnId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   pub_ref_warga_negara
   (wnKode, wnNama, wnCreationDate, wnUserId)
VALUES('%s','%s',now(),'%s')  
";

$sql['do_update'] = "
UPDATE pub_ref_warga_negara
SET wnKode = '%s',
	wnNama = '%s',
	wnLastUpdate = now(),
	wnUserId = '%s'
WHERE 
	wnId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   pub_ref_warga_negara
WHERE 
   wnId = %s   
";


?>
