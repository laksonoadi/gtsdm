<?php


//===GET===
$sql['get_count_pegawai'] = "
SELECT 
   COUNT(pegId) AS total
FROM 
   pub_pegawai
WHERE
   pegKodeResmi like '%s' OR pegNama like '%s'
";

$sql['get_count_mutasi'] = "
SELECT 
   COUNT(pktgolId) AS total
FROM 
   sdm_pangkat_golongan
WHERE 
   pegId='%s'
";

$sql['get_list_pegawai'] = "
SELECT 
    pegId as id,
	 pegNama as nama,
	 pegKodeResmi as nip
FROM 
   pub_pegawai
WHERE
   pegKodeResmi like '%s' OR pegNama like '%s'
ORDER BY
	 pegKodeResmi
   LIMIT %s, %s
";
  
$sql['get_data_pegawai']="
SELECT 
	pegId as id,
	pegNama as name,
	pegKodeResmi as kode,
	pegAlamat as alamat,
	pegNoTelp as telp,
	pegSatwilId as wil,
	pegFoto as foto,
	substring(pegTglMasukInstitusi,1,4) as masuk
FROM
	pub_pegawai
WHERE pegId='%s' 
"; 

$sql['get_list_mutasi_pelatihan']="
SELECT DISTINCT 
   p.pelId as id,
   p.pelPegKode as nip, 
   p.pelTppelrId as tipeid,
   p.pelJnspelrId as jenisid,
   p.pelNama as nama,
   p.pelTglMulai as mulai,
   p.pelTglSelesai as selesai,
   p.pelJmlJam as jmljam,
   p.pelThnIjazah as tahun,
   p.pelTempat as tempat,
   p.pelKeterangan as ket,
   p.pelAsldnrId as asdanid,
   t.tppelrNama as tipelabel,
   j.jnspelrNama as jenislabel,
   a.asldnrNama as asdanlabel,
   p.pelUpload as upload
FROM
	sdm_pelatihan p
	LEFT JOIN sdm_ref_asal_dana a ON (a.asldnrId=p.pelAsldnrId)
	LEFT JOIN sdm_ref_tipe_pelatihan t ON (t.tppelrId=p.pelTppelrId)
	LEFT JOIN sdm_ref_jenis_pelatihan j ON (j.jnspelrId=p.pelJnspelrId)
WHERE 
   p.pelPegKode='%s'
ORDER BY p.pelThnIjazah DESC
"; 

$sql['get_list_mutasi_pelatihan_verifikasi']="
SELECT DISTINCT 
   p.pelId AS id,
   p.pelPegKode AS nip, 
   p.pelTppelrId AS tipeid,
   p.pelJnspelrId AS jenisid,
   p.pelNama AS nama,
   p.pelTglMulai AS mulai,
   p.pelTglSelesai AS selesai,
   p.pelJmlJam AS jmljam,
   p.pelThnIjazah AS tahun,
   p.pelTempat AS tempat,
   p.pelKeterangan AS ket,
   p.pelAsldnrId AS asdanid,
   t.tppelrNama AS tipelabel,
   j.jnspelrNama AS jenislabel,
   a.asldnrNama AS asdanlabel,
   p.pelUpload AS upload
FROM
   sdm_pelatihan p
   LEFT JOIN sdm_ref_asal_dana a ON (a.asldnrId=p.pelAsldnrId)
   LEFT JOIN sdm_ref_tipe_pelatihan t ON (t.tppelrId=p.pelTppelrId)
   LEFT JOIN sdm_ref_jenis_pelatihan j ON (j.jnspelrId=p.pelJnspelrId)
   INNER JOIN sdm_verifikasi_data vd ON vd.`verdataValue`=p.`pelId` AND vd.`verdataStatus`='3' AND vd.`verdataVerifikasiId`='6'
WHERE 
   p.pelPegKode='%s'
ORDER BY p.pelThnIjazah DESC
"; 

$sql['get_data_mutasi_pelatihan_by_id']="
SELECT 
	p.pelId as id,
   p.pelPegKode as nip, 
   p.pelTppelrId as tipeid,
   p.pelJnspelrId as jenisid,
   p.pelNama as nama,
   p.pelTglMulai as mulai,
   p.pelTglSelesai as selesai,
   p.pelJmlJam as jmljam,
   p.pelThnIjazah as tahun,
   p.pelTempat as tempat,
   p.pelKeterangan as ket,
   p.pelAsldnrId as asdanid,
   t.tppelrNama as tipelabel,
   j.jnspelrNama as jenislabel,
   a.asldnrNama as asdanlabel,
   p.pelUpload as upload
FROM
	sdm_pelatihan p
	LEFT JOIN sdm_ref_asal_dana a ON (a.asldnrId=p.pelAsldnrId)
	LEFT JOIN sdm_ref_tipe_pelatihan t ON (t.tppelrId=p.pelTppelrId)
	LEFT JOIN sdm_ref_jenis_pelatihan j ON (j.jnspelrId=p.pelJnspelrId)
WHERE 
   p.pelPegKode='%s' AND
   p.pelId='%s' 
"; 

$sql['get_combo_jenis_pelatihan']="
SELECT
	jnspelrId as id,
	jnspelrNama as name
FROM
	sdm_ref_jenis_pelatihan 
";

$sql['get_combo_asal_dana']="
SELECT
	asldnrId as id,
	asldnrNama as name
FROM
	sdm_ref_asal_dana 
";


$sql['get_combo_tipe_pelatihan']="
SELECT
	tppelrId as id,
	tppelrNama as name
FROM
	sdm_ref_tipe_pelatihan 
";


// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_pelatihan
   (pelPegKode,pelTppelrId,pelJnspelrId,pelNama,pelTglMulai,pelTglSelesai,pelJmlJam,pelThnIjazah,pelTempat,pelKeterangan,pelAsldnrId,pelUpload)
VALUES
   ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')   
";
/*
pelId
pelPegKode
pelTppelrId
pelJnspelrId
pelNama
pelTglMulai
pelTglSelesai
pelJmlJam
pelThnIjazah
pelTempat
pelKeterangan
pelAsldnrId
*/
$sql['do_update'] = "
UPDATE sdm_pelatihan
SET 
   	pelPegKode = '%s',
      pelTppelrId = '%s',
      pelJnspelrId = '%s',
      pelNama = '%s',
      pelTglMulai = '%s',
      pelTglSelesai = '%s',
      pelJmlJam = '%s',
      pelThnIjazah = '%s',
      pelTempat = '%s',
      pelKeterangan = '%s',
      pelAsldnrId = '%s',
	  pelUpload = '%s'
WHERE 
	pelId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_pelatihan
WHERE 
   pelId = %s  
";

$sql['get_jenis_pelatihan'] = "
SELECT 
    jnspelrId AS id,
    jnspelrNama AS nama
FROM 
   sdm_ref_jenis_pelatihan
LEFT JOIN sdm_ref_tipe_pelatihan ON tppelrId=jnspelrTingkat
WHERE
   jnspelrTingkat = %s
";

?>
