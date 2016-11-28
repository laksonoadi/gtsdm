<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(jnspegrId) AS total
FROM 
   sdm_ref_jenis_pegawai
   ";   


$sql['get_data']="
SELECT 
   a.jnspegrId,
   a.jnspegrNama,
   a.jnspegrTipeKontrak,
   a.jnspegrUrut
FROM 
   sdm_ref_jenis_pegawai a
WHERE 
   a.jnspegrNama LIKE %s
ORDER BY 
   a.jnspegrUrut
";

$sql['get_data_by_id']="
SELECT 
   a.jnspegrNama,
   a.jnspegrTipeKontrak,
   a.jnspegrUrut
FROM 
   sdm_ref_jenis_pegawai a
WHERE 
   a.jnspegrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_jenis_pegawai
   (jnspegrNama,jnspegrTipeKontrak,jnspegrUrut)
VALUES('%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_jenis_pegawai
SET 
  jnspegrNama = '%s',
  jnspegrTipeKontrak = %s,
  jnspegrUrut = %s
WHERE 
	jnspegrId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_jenis_pegawai
WHERE 
   jnspegrId = %s   
";
?>
