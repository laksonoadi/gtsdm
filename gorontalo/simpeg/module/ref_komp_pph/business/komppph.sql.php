<?php

//===GET===
$sql['get_count_data_pph'] = 
   "SELECT 
      count(*) AS total
   FROM 
      pph_komponen_ref
	WHERE 
		pphkomNama LIKE '%s' OR pphkomKode LIKE '%s' ";

$sql['get_data_pph'] = 
   "SELECT 
      pphkompId				as pph_id,
	  pphkomNama			as pph_nama,
	  pphkomKode			as pph_kode,
	  pphkomKeterangan		as pph_keterangan
	  
   FROM 
      pph_komponen_ref
	WHERE 
		pphkomNama LIKE '%s' OR pphkomKode LIKE '%s'
   ORDER BY 
	  pphkomNama
   LIMIT %s, %s";

$sql['get_data_pph_by_id'] = 
   "SELECT 
      pphkompId					as pph_id,
	  pphkomKode				as pph_kode,
	  pphkomNama				as pph_nama,
	  pphkomKeterangan			as pph_keterangan
   FROM 
      pph_komponen_ref
   WHERE
      pphkompId='%s'";

$sql['get_data_pph_by_array_id'] = 
   "SELECT 
      pphkompId					as pph_id,
	  pphkomKode				as pph_kode,
	  pphkomNama				as pph_nama,
	  pphkomKeterangan			as pph_keterangan
   FROM 
      pph_komponen_ref
   WHERE
      pphkompId IN ('%s')";
	  
// untuk pengecekan-------------------------------------
$sql['get_code_gaji'] = 
   "SELECT 		
		kompgajiId 			AS code_gaji_id,
		kompgajiKode 		AS code_gaji,
		count(kompgajiId) 	AS total
	FROM 
		sdm_ref_komponen_gaji
	WHERE 
		kompgajiKode='%s'
	GROUP BY  kompgajiId";
	  
$sql['get_code_pph'] = 
   "SELECT 
		pphkompId  			AS code_pph_id,
		pphkomKode 			AS code_pph,
		count(pphkompId)	AS total
	FROM 
		pph_komponen_ref 
	WHERE 
		pphkomKode='%s'
	GROUP BY pphkompId";
//-------------------------------------------------------------

//===DO===

$sql['do_add_pph'] = 
   "INSERT INTO pph_komponen_ref
      (pphkomKode, pphkomNama, pphkomKeterangan, pphkomTglUpdate)
   VALUES 
      ('%s', '%s', '%s', NOW())";

$sql['do_update_pph'] = 
   "UPDATE pph_komponen_ref
   SET
	  pphkomKode = '%s',
      pphkomNama = '%s',
	  pphkomKeterangan = '%s',
	  pphkomTglUpdate = NOW()
		
   WHERE 
      pphkompId = '%s'";

$sql['do_delete_pph_by_id'] = 
   "DELETE from pph_komponen_ref
   WHERE 
      pphkompId='%s'";

$sql['do_delete_pph_by_array_id'] = 
   "DELETE from pph_komponen_ref
   WHERE 
      pphkompId IN ('%s')";
	  
$sql['get_data_excel'] = "
	SELECT 
      pphkompId				as pph_id,
	  pphkomNama			as pph_nama,
	  pphkomKode			as pph_kode,
	  pphkomKeterangan		as pph_keterangan
	  
	FROM 
      pph_komponen_ref
	WHERE 
		pphkomNama LIKE '%s' OR pphkomKode LIKE '%s'
	ORDER BY 
	  pphkomNama";
?>
