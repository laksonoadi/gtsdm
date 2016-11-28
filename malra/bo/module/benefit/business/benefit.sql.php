<?php
//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(benefitId) AS total
FROM 
   sdm_ref_benefit
WHERE 
   benefitNama LIKE %s
LIMIT 1
";   

$sql['get_data']="
SELECT 
   benefitId,
   benefitNama,
   benefitUraian,
   benefitPengecualian,
   benefitTgl
FROM 
   sdm_ref_benefit
WHERE 
   benefitNama LIKE %s
ORDER BY 
   benefitId
LIMIT %s,%s
";

$sql['get_data_by_id']="
SELECT 
   benefitNama,
   benefitNama,
   benefitUraian,
   benefitPengecualian,
   benefitTgl
FROM 
   sdm_ref_benefit
WHERE 
   benefitId = %s
";

//===DO===
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_benefit
   (benefitNama, benefitUraian, benefitPengecualian, benefitTgl)
VALUES
   ('%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE 
   sdm_ref_benefit
SET 
   benefitNama = '%s',
   benefitUraian = '%s',
   benefitPengecualian = '%s',
   benefitTgl = '%s'
WHERE 
   benefitId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_benefit
WHERE 
   benefitId = %s   
";
?>
