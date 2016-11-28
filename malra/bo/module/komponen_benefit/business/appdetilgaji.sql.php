<?php

//===GET===
$sql['get_data'] = "
   SELECT 
      kompgajidtId as id,
      kompgajidtKode as kode,
      kompgajidtNama as nama,
      kompgajidtStatusSeting as setting,
      kompgajidtNominal as nominal,
      kompgajidtPersen as persen,
      kompgajidtTanggalBerlaku as tanggal_berlaku
   FROM 
      sdm_ref_komponen_gaji_detail
	WHERE 
		kompgajidtKompgajiId=%s
	AND 
		kompgajidtKode like '%s'
	AND
		kompgajidtNama like '%s'
   ORDER BY 
	  kompgajidtKode, kompgajidtNama ASC
   LIMIT %s, %s";

$sql['get_count_data'] = "
SELECT 
      count(*) AS total
   FROM 
      sdm_ref_komponen_gaji_detail
	WHERE 
		kompgajidtKompgajiId=%s
	AND 
		kompgajidtKode like '%s'
	AND
		kompgajidtNama like '%s'
";

$sql['get_data_by_id'] ="
   SELECT 
      a.kompgajidtId as id_detil,
      a.kompgajidtKode as kode_detil,
      a.kompgajidtNama as nama_detil,
      a.kompgajidtStatusSeting as setting_detil,
      a.kompgajidtNominal as nominal_detil,
      a.kompgajidtPersen as persen_detil,
      a.kompgajidtTanggalBerlaku as tanggal_berlaku,
      b.kompgajiNama
   FROM 
      sdm_ref_komponen_gaji_detail a
      JOIN sdm_ref_komponen_gaji b ON b.kompgajiId = a.kompgajidtKompgajiId
   WHERE
      a.kompgajidtId='%s'";

$sql['get_info'] ="
   SELECT 
      kompgajiId as id,
      kompgajiKode as kode,
      kompgajiNama as nama,
      kompgajiKeterangan as keterangan,
      kompgajiJenis as jenis
   FROM 
      sdm_ref_komponen_gaji
   WHERE
      kompgajiId='%s'";
      
$sql['get_kode_by_id'] = "
SELECT
   kompgajidtKode
FROM
   sdm_ref_komponen_gaji_detail
WHERE
   kompgajidtId IN (%s)
";


//===DO===
$sql['do_add_data'] = 
   "INSERT INTO sdm_ref_komponen_gaji_detail
      (kompgajidtKompgajiId, kompgajidtKode, kompgajidtNama, kompgajidtStatusSeting, kompgajidtNominal, kompgajidtPersen, kompgajidtTanggalBerlaku)
   VALUES 
      ('%s', '%s', '%s', '%s', '%s', '%s', '%s')";

$sql['do_update_data'] = "
   UPDATE 
      sdm_ref_komponen_gaji_detail
   SET
      kompgajidtKode = '%s',
      kompgajidtNama = '%s',
      kompgajidtStatusSeting = '%s',
      kompgajidtNominal = '%s',
      kompgajidtPersen = '%s',
      kompgajidtTanggalBerlaku = '%s'
   WHERE 
      kompgajidtId = '%s'";

$sql['do_delete_data'] = 
   "DELETE from 
   sdm_ref_komponen_gaji_detail
   WHERE 
      kompgajidtId='%s'";

$sql['do_delete_data_by_array_id'] = 
   "DELETE from sdm_ref_komponen_gaji_detail
   WHERE 
      kompgajidtId IN ('%s')";
      /**/

?>
