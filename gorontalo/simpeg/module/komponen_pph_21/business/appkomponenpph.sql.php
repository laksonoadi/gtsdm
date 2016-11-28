<?php

//===GET===
$sql['get_komponen_pph'] = "
   SELECT 
      CONCAT('[',pphkomKode,'] = ',pphkomNama) as komponenPph,
      CONCAT('[',pphkomKode,']') as buttonPph 
   FROM 
      pph_komponen_ref 
   ORDER BY 
      pphkomKode
";

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
      pph_komponen_formula
	WHERE 
		pphkompformNama LIKE '%s'";

$sql['get_data_komponen'] = 
   "SELECT 
      pphkompformId 		AS komponen_id, 
      pphkompformNama 		AS nama, 
      pphkompformFormula 	AS formula,
	  pphkompformMaxNominal AS max_value,
	  pphkompformJenis AS jenis
   FROM 
      pph_komponen_formula
   WHERE 
      pphkompformNama LIKE '%s'
   ORDER BY 
      pphkompformNama
   LIMIT %s, %s";

$sql['get_data_komponen_by_id'] = 
   "SELECT 
      pphkompformId AS komponen_id, 
      pphkompformNama AS nama, 
      pphkompformFormula AS formula,
	  pphkompformMaxNominal AS max_value,
	  pphkompformJenis AS jenis
   FROM 
      pph_komponen_formula
   WHERE
      pphkompformId = '%s'";

$sql['get_data_komponen_by_array_id'] = 
   "SELECT 
      pphkompformId AS komponen_id, 
      pphkompformNama AS nama, 
      pphkompformFormula AS formula,
	  pphkompformMaxNominal AS max_value,
	  pphkompformJenis AS jenis
   FROM 
      pph_komponen_formula
   WHERE
      pphkompformId IN ('%s')";

//===DO===

$sql['do_add_komponen'] = 
   "INSERT INTO pph_komponen_formula
      (pphkompformNama, pphkompformFormula,pphkompformTanggal,pphkompformUserId,pphkompformMaxNominal, pphkompformJenis)
   VALUES 
      ('%s','%s',now(),(SELECT UserId FROM gtfw_user WHERE UserName = '%s'), '%s', '%s')";

$sql['do_update_komponen'] = 
   "UPDATE pph_komponen_formula
   SET
      pphkompformNama = '%s',
      pphkompformFormula = '%s',
      pphkompformTanggal = now(),
      pphkompformUserId = (SELECT UserId FROM gtfw_user WHERE UserName = '%s'),
	  pphkompformMaxNominal= '%s',
	  pphkompformJenis = '%s'
   WHERE 
      pphkompformId = '%s'";

$sql['do_delete_komponen_by_id'] = 
   "DELETE from pph_komponen_formula
   WHERE 
      pphkompformId='%s'";

$sql['do_delete_komponen_by_array_id'] = 
   "DELETE from pph_komponen_formula
   WHERE 
      pphkompformId IN ('%s')";
?>
