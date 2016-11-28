<?php

//===GET===
$sql['get_komponen_gaji'] = "
   SELECT 
      CONCAT('[',kompgajiKode,'] = ',kompgajiNama) as komponenGaji,
      CONCAT('[',kompgajiKode,']') as buttonGaji 
   FROM 
      sdm_ref_komponen_gaji 
   ORDER BY 
      kompgajiKode
";

$sql['get_count_data_komponen'] = 
   "SELECT 
      count(*) AS total
   FROM 
      sdm_komponen_formula
	WHERE 
		kompformNama LIKE '%s'";

$sql['get_data_komponen'] = 
   "SELECT 
      kompformId AS komponen_id, 
      kompformNama AS nama, 
      kompformFormula AS formula 
   FROM 
      sdm_komponen_formula
   WHERE 
      kompformNama LIKE '%s'
   ORDER BY 
      kompformNama
   LIMIT %s, %s";

$sql['get_data_komponen_by_id'] = 
   "SELECT 
      kompformId AS komponen_id, 
      kompformNama AS nama, 
      kompformFormula AS formula
   FROM 
      sdm_komponen_formula
   WHERE
      kompformId = '%s'";

$sql['get_data_komponen_by_array_id'] = 
   "SELECT 
      kompformId AS komponen_id, 
      kompformNama AS nama, 
      kompformFormula AS formula
   FROM 
      sdm_komponen_formula
   WHERE
      kompformId IN ('%s')";
	  
$sql['get_jenis_pegawai_join_komponen_edit'] = "
SELECT
	kompjenisKompformId AS komponenid,
	jnspegrId AS jnspegid,
	jnspegrNama AS jenis,
	IFNULL(ROUND(kompjenisNilai),100) AS nilai
FROM
	sdm_ref_jenis_pegawai
	LEFT JOIN sdm_komponen_jenis_pegawai ON jnspegrId=kompjenisJnspegId AND kompjenisKompformId='%s'
	
";

$sql['get_jenis_pegawai_join_komponen_tambah'] = "
SELECT
	'' as komponenid,
	jnspegrid as jnspegid,
	jnspegrNama as jenis,
	0 as nilai
FROM
	sdm_ref_jenis_pegawai
";

//===DO===
$sql['insert_jenis_pegawai_join_komponen'] = "
INSERT IGNORE INTO 
	sdm_komponen_jenis_pegawai 
SET
	kompjenisKompformId='%s',
	kompjenisJnspegId='%s',
	kompjenisNilai='%s'
ON DUPLICATE KEY UPDATE
	kompjenisNilai='%s'
";
	  
$sql['do_add_komponen'] = 
   "INSERT INTO sdm_komponen_formula
      (kompformNama, kompformFormula,kompformTanggal,kompformUserId)
   VALUES 
      ('%s','%s',now(),(SELECT UserId FROM gtfw_user WHERE UserName = '%s'))";

$sql['do_update_komponen'] = 
   "UPDATE sdm_komponen_formula
   SET
      kompformNama = '%s',
      kompformFormula = '%s',
      kompformTanggal = now(),
      kompformUserId = (SELECT UserId FROM gtfw_user WHERE UserName = '%s')
   WHERE 
      kompformId = '%s'";

$sql['do_delete_komponen_by_id'] = 
   "DELETE from sdm_komponen_formula
   WHERE 
      kompformId='%s'";

$sql['do_delete_komponen_by_array_id'] = 
   "DELETE from sdm_komponen_formula
   WHERE 
      kompformId IN ('%s')";
?>
