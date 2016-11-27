<?php

//===GET===
$sql['get_count_data_pphrp'] = 
   "SELECT 
      count(*) AS total
   FROM 
      pph_range_potongan_ref
	WHERE 
		pphrpNama LIKE '%s'";

$sql['get_data_pphrp'] = 
   "SELECT 
      pphrpId 			as pphrp_id,
	  pphrpNama			as pphrp_nama,
	  pphrpNominalMax	as pphrp_nominal,
	  pphrpOrder 		as pphrp_order
	  
   FROM 
      pph_range_potongan_ref
	WHERE 
		pphrpNama LIKE '%s'
   ORDER BY 
	  pphrpOrder ASC
   LIMIT %s, %s";

$sql['get_data_pphrp_by_id'] = 
   "SELECT 
      pphrpId 			as pphrp_id,
	  pphrpNominalMax	as pphrp_nominal,
	  pphrpNama			as pphrp_nama,
	  pphrpOrder 		as pphrp_order
   FROM 
      pph_range_potongan_ref
   WHERE
      pphrpId ='%s'";

$sql['get_data_pphrp_by_array_id'] = 
   "SELECT 
      pphrpId 			as pphrp_id,
	  pphrpNominalMax	as pphrp_nominal,
	  pphrpNama			as pphrp_nama,
	  pphrpOrder 		as pphrp_order
   FROM 
      pph_range_potongan_ref
   WHERE
      pphrpId  IN ('%s')";

//===DO===

$sql['do_add_pphrp'] = 
   "INSERT INTO pph_range_potongan_ref
      (pphrpNama, pphrpNominalMax, pphrpOrder , pphrpTanggalUbah, pphrpUserId)
   VALUES 
      ('%s', '%s', '%s', NOW() , '%s')";

$sql['do_update_pphrp'] = 
   "UPDATE pph_range_potongan_ref
   SET
	  pphrpNama = '%s',
	  pphrpNominalMax = '%s', 
	  pphrpOrder  = '%s',
	  pphrpTanggalUbah  = NOW(),
	  pphrpUserId ='%s'
		
   WHERE 
      pphrpId  = '%s'";

$sql['do_delete_pphrp_by_id'] = 
   "DELETE from pph_range_potongan_ref
   WHERE 
      pphrpId ='%s'";

$sql['do_delete_pphrp_by_array_id'] = 
   "DELETE from pph_range_potongan_ref
   WHERE 
      pphrpId  IN ('%s')";
	  
$sql['get_data_excel'] = "
	SELECT 
      pphrpId 			as pphrp_id,
	  pphrpNama			as pphrp_nama,
	  pphrpNominalMax	as pphrp_nominal,
	  pphrpOrder 		as pphrp_order,
	  pphrpUserId		as id_user
	  
	FROM 
      pph_range_potongan_ref
	WHERE 
		pphrpNama LIKE '%s'
	ORDER BY 
	  pphrpOrder ASC";

$sql['do_set_order'] = 
   "UPDATE pph_range_potongan_ref
   SET
	  pphrpOrder  = '%s'
   WHERE 
      pphrpId  = '%s'";
?>
