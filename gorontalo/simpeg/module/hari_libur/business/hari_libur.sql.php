<?php
//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(hariliburId) AS total
FROM 
   sdm_ref_hari_libur
WHERE 
   hariliburNama LIKE %s
LIMIT 1
";   

$sql['get_data']="
SELECT 
   hariliburId,
   hariliburTgl,
   hariliburNama,
   hariliburKeterangan
FROM 
   sdm_ref_hari_libur
WHERE 
   hariliburNama LIKE %s
ORDER BY 
   hariliburId
LIMIT %s,%s
";

$sql['get_data_by_id']="
SELECT 
   hariliburId,
   hariliburTgl,
   hariliburNama,
   hariliburKeterangan
FROM 
   sdm_ref_hari_libur
WHERE 
   hariliburId = %s
";

//===DO===
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_hari_libur
   (hariliburTgl, hariliburNama, hariliburKeterangan)
VALUES
   ('%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE 
   sdm_ref_hari_libur
SET 
   hariliburTgl = '%s',
   hariliburNama = '%s',
   hariliburKeterangan = '%s'
WHERE 
   hariliburId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_hari_libur
WHERE 
   hariliburId = %s   
";
?>
