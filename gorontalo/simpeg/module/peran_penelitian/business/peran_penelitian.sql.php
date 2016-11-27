<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(ppnltnrId) AS total
FROM 
   sdm_ref_peranan_penelitian
WHERE 
   ppnltnrPeranan LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   ppnltnrId,
   ppnltnrPeranan
FROM 
   sdm_ref_peranan_penelitian
WHERE 
   ppnltnrPeranan LIKE %s
ORDER BY 
   ppnltnrPeranan

";

$sql['get_data_by_id']="
SELECT 
   ppnltnrPeranan
FROM 
   sdm_ref_peranan_penelitian
WHERE 
   ppnltnrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_peranan_penelitian
   (ppnltnrPeranan)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_peranan_penelitian
SET ppnltnrPeranan = '%s'
WHERE 
	ppnltnrId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_peranan_penelitian
WHERE 
   ppnltnrId = %s   
";
?>
