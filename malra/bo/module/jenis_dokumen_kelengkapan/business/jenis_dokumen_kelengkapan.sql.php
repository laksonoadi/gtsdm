<?php
//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(jnsdoklngkpId) AS total
FROM 
   sdm_ref_jenis_dokumen_kelengkapan
WHERE 
   jnsdoklngkpNama LIKE %s
LIMIT 1
";   

$sql['get_data']="
SELECT 
   jnsdoklngkpId,
   jnsdoklngkpNama,
   jnsdoklngkpKeterangan
FROM 
   sdm_ref_jenis_dokumen_kelengkapan
WHERE 
   jnsdoklngkpNama LIKE %s
ORDER BY 
   jnsdoklngkpId
LIMIT %s,%s
";

$sql['get_data_by_id']="
SELECT 
   jnsdoklngkpId,
   jnsdoklngkpNama,
   jnsdoklngkpKeterangan
FROM 
   sdm_ref_jenis_dokumen_kelengkapan
WHERE 
   jnsdoklngkpId = %s
";

//===DO===
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_jenis_dokumen_kelengkapan
   (jnsdoklngkpNama, jnsdoklngkpKeterangan)
VALUES
   ('%s','%s')  
";

$sql['do_update'] = "
UPDATE 
   sdm_ref_jenis_dokumen_kelengkapan
SET 
   jnsdoklngkpNama = '%s',
   jnsdoklngkpKeterangan = '%s'
WHERE 
   jnsdoklngkpId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_jenis_dokumen_kelengkapan
WHERE 
   jnsdoklngkpId = %s   
";
?>
