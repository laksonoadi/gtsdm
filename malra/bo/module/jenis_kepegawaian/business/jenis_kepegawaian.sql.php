<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(id_ref_jns_peg) AS total
FROM 
   sdm_ref_jenis_kepegawaian
   ";   


$sql['get_data']="
SELECT 
   a.id_ref_jns_peg as id,
   a.nama_ref_jns_peg as nama
FROM 
   sdm_ref_jenis_kepegawaian a
WHERE 
   a.nama_ref_jns_peg LIKE %s
ORDER BY 
   a.id_ref_jns_peg
";

$sql['get_data_by_id']="
SELECT 
   a.id_ref_jns_peg as id,
   a.nama_ref_jns_peg as nama
FROM 
   sdm_ref_jenis_kepegawaian a
WHERE 
   a.id_ref_jns_peg = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_jenis_kepegawaian
   (nama_ref_jns_peg)
VALUES('%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_jenis_kepegawaian
SET 
  nama_ref_jns_peg = '%s'
WHERE 
	id_ref_jns_peg = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_jenis_kepegawaian
WHERE 
   id_ref_jns_peg = %s   
";
?>
