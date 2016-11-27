<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(gelId) AS total
FROM 
   pub_ref_gelombang
WHERE 
   gelNama LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   gelId,
   gelNama,
   gelCreationDate,
   gelLastUpdate,
   gelUserId
FROM 
   pub_ref_gelombang
WHERE 
   gelNama LIKE %s
ORDER BY 
   gelId
LIMIT %s,%s
";

$sql['get_data_by_id']="
SELECT 
   gelNama
FROM 
   pub_ref_gelombang
WHERE 
   gelId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   pub_ref_gelombang
   (gelNama, gelCreationDate, gelUserId)
VALUES('%s',now(),'%s')  
";

$sql['do_update'] = "
UPDATE pub_ref_gelombang
SET gelNama = '%s',
	gelLastUpdate = now(),
	gelUserId = '%s'
WHERE 
	gelId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   pub_ref_gelombang
WHERE 
   gelId = %s   
";
?>
