<?php
//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(agmId) AS total
FROM 
   pub_ref_agama
WHERE 
   agmNama LIKE %s
LIMIT 1
";   


$sql['get_data']="
SELECT 
   agmId,
   agmNama,
   agmCreationDate,
   agmLastUpdate,
   agmUserId
FROM 
   pub_ref_agama
WHERE 
   agmNama LIKE %s
ORDER BY 
   agmId
LIMIT %s,%s
";

$sql['get_data_by_id']="
SELECT 
   agmNama
FROM 
   pub_ref_agama
WHERE 
   agmId = %s
";

//===DO===
$sql['do_add'] = "
INSERT INTO 
   pub_ref_agama
   (agmNama, agmCreationDate, agmUserId)
VALUES
   ('%s',now(),'%s')  
";

$sql['do_update'] = "
UPDATE 
   pub_ref_agama
SET 
   agmNama = '%s',
   agmLastUpdate = now(),
   agmUserId = '%s'
WHERE 
   agmId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   pub_ref_agama
WHERE 
   agmId = %s   
";
?>
