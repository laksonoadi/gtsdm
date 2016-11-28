<?php
//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(jnsklaimId) AS total
FROM 
   sdm_ref_jenis_klaim
WHERE 
   jnsklaimNama LIKE %s
LIMIT 1
";   

$sql['get_data']="
SELECT 
   jnsklaimId,
   jnsklaimNama,
   jnsklaimKeterangan
FROM 
   sdm_ref_jenis_klaim
WHERE 
   jnsklaimNama LIKE %s
ORDER BY 
   jnsklaimId
LIMIT %s,%s
";

$sql['get_data_by_id']="
SELECT 
   jnsklaimId,
   jnsklaimNama,
   jnsklaimKeterangan
FROM 
   sdm_ref_jenis_klaim
WHERE 
   jnsklaimId = %s
";

//===DO===
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_jenis_klaim
   (jnsklaimNama, jnsklaimKeterangan)
VALUES
   ('%s','%s')  
";

$sql['do_update'] = "
UPDATE 
   sdm_ref_jenis_klaim
SET 
   jnsklaimNama = '%s',
   jnsklaimKeterangan = '%s'
WHERE 
   jnsklaimId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_jenis_klaim
WHERE 
   jnsklaimId = %s   
";
?>
