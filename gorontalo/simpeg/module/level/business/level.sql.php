<?php
//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(levelId) AS total
FROM 
   sdm_ref_level
WHERE 
   levelNama LIKE %s
LIMIT 1
";   

$sql['get_data']="
SELECT 
   levelId,
   levelNama
FROM 
   sdm_ref_level
WHERE 
   levelNama LIKE %s
ORDER BY 
   levelId
LIMIT %s,%s
";

$sql['get_data_by_id']="
SELECT 
   levelId,
   levelNama
FROM 
   sdm_ref_level
WHERE 
   levelId = %s
";

//===DO===
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_level
   (levelNama)
VALUES
   ('%s')  
";

$sql['do_update'] = "
UPDATE 
   sdm_ref_level
SET 
   levelNama = '%s'
WHERE 
   levelId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_level
WHERE 
   levelId = %s   
";
?>
