<?php
//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(bankId) AS total
FROM 
   pub_ref_bank
WHERE 
   bankNama LIKE %s
LIMIT 1
";   


$sql['get_data']="
SELECT 
   bankId,
   bankNama,
   bankCreationDate,
   bankLastUpdate,
   bankUserId
FROM 
   pub_ref_bank
WHERE 
   bankNama LIKE %s
ORDER BY 
   bankId
LIMIT %s,%s
";

$sql['get_data_by_id']="
SELECT 
   bankNama
FROM 
   pub_ref_bank
WHERE 
   bankId = %s
";

//===DO===
$sql['do_add'] = "
INSERT INTO 
   pub_ref_bank
   (bankNama, bankCreationDate, bankUserId)
VALUES
   ('%s',now(),'%s')  
";

$sql['do_update'] = "
UPDATE 
   pub_ref_bank
SET 
   bankNama = '%s',
   bankLastUpdate = now(),
   bankUserId = '%s'
WHERE 
   bankId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   pub_ref_bank
WHERE 
   bankId = %s   
";
?>
