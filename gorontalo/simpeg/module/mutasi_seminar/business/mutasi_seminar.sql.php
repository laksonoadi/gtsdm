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

$sql['get_list_mutasi_seminar']="
SELECT DISTINCT 
   p.smnrId as id,
   p.smnrPegKode as nip, 
   p.smnrNama as nama,
   p.smnrTksmnrId as tingkatid,
   p.smnrPeranan as peranan,
   p.smnrTgl as mulai,
   p.smnrPenyelenggara as penyelenggara,
   p.smnrTempat as tempat,
   t.tksmnrNama as tingkatlabel,
   p.smnrUpload as upload
FROM
	sdm_seminar p
	LEFT JOIN sdm_ref_tingkat_seminar t ON (t.tksmnrId=p.smnrTksmnrId)
WHERE 
   p.smnrPegKode='%s'
ORDER BY p.smnrTgl DESC
"; 

$sql['get_data_mutasi_seminar_by_id']="
SELECT 
	p.smnrId as id,
   p.smnrPegKode as nip, 
   p.smnrNama as nama,
   p.smnrTksmnrId as tingkatid,
   p.smnrPeranan as peranan,
   p.smnrTgl as mulai,
   p.smnrPenyelenggara as penyelenggara,
   p.smnrTempat as tempat,
   t.tksmnrNama as tingkatlabel,
   p.smnrUpload as upload
FROM
	sdm_seminar p
	LEFT JOIN sdm_ref_tingkat_seminar t ON (t.tksmnrId=p.smnrTksmnrId)
WHERE 
   p.smnrPegKode='%s' AND
   p.smnrId='%s' 
"; 

$sql['get_combo_tingkat_seminar']="
SELECT
	tksmnrId as id,
	tksmnrNama as name
FROM
	sdm_ref_tingkat_seminar 
";


// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_seminar
   (smnrPegKode,smnrNama,smnrTksmnrId,smnrPeranan,smnrTgl,smnrPenyelenggara,smnrTempat,smnrUpload)
VALUES
   ('%s','%s','%s','%s','%s','%s','%s','%s')   
";
/*smnrId
smnrPegKode
smnrNama
smnrTksmnrId
smnrPeranan
smnrTgl
smnrPenyelenggara
smnrTempat*/


$sql['do_update'] = "
UPDATE sdm_seminar
SET 
   	smnrPegKode = '%s',
      smnrNama = '%s',
      smnrTksmnrId = '%s',
      smnrPeranan = '%s',
      smnrTgl = '%s',
      smnrPenyelenggara = '%s',
      smnrTempat = '%s',
	  smnrUpload = '%s'
WHERE 
	smnrId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_seminar
WHERE 
   smnrId = %s  
";

?>
