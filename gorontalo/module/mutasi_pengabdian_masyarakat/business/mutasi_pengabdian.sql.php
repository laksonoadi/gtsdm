<?php


//===GET===
$sql['get_combo_jenis_pengabdian'] = "
SELECT 
   jnspengabId as id,
   jnspengabNama as name
FROM 
   sdm_ref_jenis_pengabdian
ORDER BY jnspengabNama
";

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

$sql['get_list_mutasi_pengabdian']="
SELECT DISTINCT 
   p.pemasyId AS id,
   p.pemasyPegId AS nip, 
   jnspengabNama AS jenis,
   p.pemasyJenis AS jenis2,
   p.pemasyNama AS nama,
   p.pemasyMulai AS mulai,
   p.pemasySelesai AS selesai,
   CONCAT(p.pemasyLamaWaktu,' bulan') AS lama,
   p.pemasyTempat AS tempat,
   p.pemasyKet AS ket,
   p.pemasyAslDnrId AS asdanid,
   p.pemasyBesarDana AS besar_dana,
   a.asldnrNama AS asdanlabel,
   p.pemasyUpload AS upload
FROM
	sdm_pengabdian_masyarakat p
	LEFT JOIN sdm_ref_asal_dana a ON (a.asldnrId=p.pemasyAslDnrId)
	LEFT JOIN sdm_ref_jenis_pengabdian ON pemasyJenis=jnspengabId
WHERE 
  p.pemasyPegId='%s'
";  

$sql['get_data_mutasi_pelatihan_by_id']="
SELECT DISTINCT 
   p.pemasyId AS id,
   p.pemasyPegId AS nip, 
   p.pemasyJenis AS jenis,
   p.pemasyNama AS nama,
   p.pemasyMulai AS mulai,
   p.pemasySelesai AS selesai,
   p.pemasyLamaWaktu AS lama,
   p.pemasyTempat AS tempat,
   p.pemasyKet AS ket,
   p.pemasyAslDnrId AS asdanid,
   p.pemasyBesarDana AS besar_dana,
   a.asldnrNama AS asdanlabel,
   p.pemasyUpload AS upload
FROM
	sdm_pengabdian_masyarakat p
	LEFT JOIN sdm_ref_asal_dana a ON (a.asldnrId=p.pemasyAslDnrId)
WHERE 
  p.pemasyPegId='%s' AND
   p.pemasyId='%s' 
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
   sdm_pengabdian_masyarakat
   (pemasyPegId,pemasyNama,pemasyJenis,pemasyTempat,pemasyLamaWaktu,pemasyMulai,pemasySelesai,pemasyAslDnrId,pemasyBesarDana,pemasyKet,pemasyUpload)
VALUES
   ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')    
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
UPDATE sdm_pengabdian_masyarakat
SET 
      pemasyPegId = '%s',
      pemasyNama = '%s',
      pemasyJenis = '%s',
      pemasyTempat = '%s',
      pemasyLamaWaktu = '%s',
      pemasyMulai = '%s',
      pemasySelesai = '%s',
      pemasyAslDnrId = '%s',
      pemasyBesarDana = '%s',
      pemasyKet = '%s',
      pemasyUpload = '%s'
WHERE 
	pemasyId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_pengabdian_masyarakat
WHERE 
   pemasyId = %s  
";

?>
