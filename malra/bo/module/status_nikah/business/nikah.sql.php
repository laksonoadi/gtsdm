<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(
statnkhId) AS total
FROM 
   pub_ref_status_nikah
WHERE 
   statnkhNama LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   statnkhId,
   statnkhNama,
   statnkhCreationDate,
   statnkhLastUpdate,
   statnkhUserId
FROM 
   pub_ref_status_nikah
WHERE 
   statnkhNama LIKE %s
ORDER BY 
   statnkhId

";

$sql['get_data_by_id']="
SELECT 
   statnkhNama
FROM 
   pub_ref_status_nikah
WHERE 
   statnkhId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   pub_ref_status_nikah
   (statnkhNama, statnkhCreationDate, statnkhUserId)
VALUES('%s',now(),'%s')  
";

$sql['do_update'] = "
UPDATE pub_ref_status_nikah
SET statnkhNama = '%s',
	statnkhLastUpdate = now(),
	statnkhUserId = '%s'
WHERE 
	statnkhId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   pub_ref_status_nikah
WHERE 
   statnkhId = %s   
";
?>
