<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(
pkrjnId) AS total
FROM 
   pub_ref_pekerjaan
WHERE 
   pkrjnNama LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   pkrjnId,
   pkrjnNama,
   pkrjnCreationDate,
   pkrjnLastUpdate,
   pkrjnUserId
FROM 
   pub_ref_pekerjaan
WHERE 
   pkrjnNama LIKE %s
ORDER BY 
   pkrjnId

";

$sql['get_data_by_id']="
SELECT 
   pkrjnNama
FROM 
   pub_ref_pekerjaan
WHERE 
   pkrjnId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   pub_ref_pekerjaan
   (pkrjnNama, pkrjnCreationDate, pkrjnUserId)
VALUES('%s',now(),'%s')  
";

$sql['do_update'] = "
UPDATE pub_ref_pekerjaan
SET pkrjnNama = '%s',
	pkrjnLastUpdate = now(),
	pkrjnUserId = '%s'
WHERE 
	pkrjnId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   pub_ref_pekerjaan
WHERE 
   pkrjnId = %s   
";
?>
