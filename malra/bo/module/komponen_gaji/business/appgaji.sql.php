<?php

//===GET===
$sql['get_data'] = "
   SELECT 
      kompgajiId as id,
      kompgajiKode as kode,
      kompgajiNama as nama,
      kompgajiKeterangan as keterangan,
      kompgajiJenis as jenis,
	  kompgajiIsAuto as otomatis
   FROM 
      sdm_ref_komponen_gaji
	WHERE 
		(kompgajiNama LIKE '%s'  OR
      kompgajiKode LIKE '%s')
      %s
   ORDER BY kompgajiIsAuto DESC, kompgajiKode
   LIMIT %s, %s";

$sql['get_count_data'] = "
SELECT 
      count(*) AS total
   FROM 
      sdm_ref_komponen_gaji
	WHERE 
		(kompgajiNama LIKE '%s'  OR
      kompgajiKode LIKE '%s')
      %s
";

$sql['get_data_by_id'] ="
   SELECT 
      kompgajiId as id,
      kompgajiKode as kode,
      kompgajiNama as nama,
      kompgajiKeterangan as keterangan,
      kompgajiJenis as jenis,
	  kompgajiIsAuto as otomatis,
	  kompgajiTabelReferensi as arr_table
   FROM 
      sdm_ref_komponen_gaji
   WHERE
      kompgajiId='%s'";


//===EXCEL===
$sql['get_data_sheet1'] = "
   SELECT
      a.pegId as id,
      a.pegKodeResmi as nip,
      a.pegNipLama as nidn,
      a.pegKodeLain as nomor_induk_dosen,
      a.pegNama as nama,
      a.pegAlamat as alamat,
      a.pegNoHp as no_hp,
      a.pegNoTelp as no_telp,
      b.pegrekRekening as no_rekening,
      c3.kompgajiNama as komponen,
      c2.kompgajidtKode as detil_komponen,
      d.mstgajiIsAktif as `status`,
      e.satkerpegSatkerId as unitkerja
   FROM
      pub_pegawai a
      LEFT JOIN pub_pegawai_rekening b ON (b.pegrekPegId = a.pegId)
      LEFT JOIN sdm_komponen_gaji_pegawai_detail c1 ON (c1.kompgajipegdtPegId = a.pegId)
      LEFT JOIN sdm_ref_komponen_gaji_detail c2 ON (c2.kompgajidtId = c1.kompgajipegdtKompgajidtrId)
      LEFT JOIN sdm_ref_komponen_gaji c3 ON (c3.kompgajiId = c2.kompgajidtKompgajiId)
      LEFT JOIN sdm_ref_master_gaji d ON (d.mstgajiPegId = a.pegId)
      LEFT JOIN sdm_satuan_kerja_pegawai e ON (e.satkerpegPegId = a.pegId)
      GROUP BY a.pegId, a.pegKodeResmi
   ORDER BY nama,komponen
   -- LIMIT 0,2
";
   //sheet2 : data komponen gaji
$sql['get_data_sheet2'] = "
   SELECT 
      a.kompgajiId as id,
      a.kompgajiKode as kode,
      a.kompgajiNama as nama,
      a.kompgajiKeterangan as keterangan,
      IF(a.kompgajiJenis = 'tambah', 'Faktor Penambah Gaji', 'Faktor Pengurang Gaji') as jenis,
      b.kompgajidtKode as detil_kode,
      b.kompgajidtNama as detil_nama
   FROM 
      sdm_ref_komponen_gaji a
      LEFT JOIN sdm_ref_komponen_gaji_detail b ON (b.kompgajidtKompgajiId = a.kompgajiId)
	WHERE 
		1=1
   -- GROUP BY a.kompgajiId   
   ORDER BY 
	  a.kompgajiKode,b.kompgajidtKode
";

//sheet3 : data satuankerja
/*$sql['get_data_sheet3'] = "
  SELECT 
		(if(tempUnitId IS NULL,satkerId,satkerId)) AS id,
		(if(tempUnitNama IS NULL,satkerNama,CONCAT_WS('/ ',tempUnitNama, satkerNama))) AS nama
   FROM
      pub_satuan_kerja
		LEFT JOIN 
			(SELECT 
				satkerId AS tempUnitId,
				satkerNama AS tempUnitNama
			FROM pub_satuan_kerja WHERE unitkerjaParentId = 0) tmpUnitKerja ON(unitkerjaParentId=tempUnitId)
   ORDER BY nama
";*/

/*END EXCEL*/


//===DO===
$sql['do_add_data'] = 
   "INSERT INTO sdm_ref_komponen_gaji
      (kompgajiKode, kompgajiNama, kompgajiKeterangan, kompgajiJenis)
   VALUES 
      ('%s', '%s', '%s', '%s')";

$sql['do_update_data'] = "
   UPDATE 
      sdm_ref_komponen_gaji
   SET
      kompgajiKode = '%s',
      kompgajiNama = '%s',
      kompgajiKeterangan = '%s',
      kompgajiJenis = '%s'
   WHERE 
      kompgajiId = '%s'";

$sql['do_delete_data'] = 
   "DELETE from 
   sdm_ref_komponen_gaji
   WHERE 
      kompgajiId='%s'";

$sql['do_delete_data_by_array_id'] = 
   "DELETE from sdm_ref_komponen_gaji
   WHERE 
      kompgajiId IN ('%s')";


?>
