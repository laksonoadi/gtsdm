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


$sql['get_list_mutasi_penghargaan']="
SELECT DISTINCT 
   p.phgId as id,
   p.phgPegKode as nip, 
   p.phgJnsphgrId as jpid,
   p.phgNama as nama,
   p.phgTahun as tahun,
   p.phgPemberi as pemberi,
   j.jnsphgrNama as jplabel,
   p.phgUpload as upload
FROM
	sdm_penghargaan p
	LEFT JOIN sdm_ref_jenis_penghargaan j ON (j.jnsphgrId=p.phgJnsphgrId)
WHERE 
   p.phgPegKode='%s'
"; 

$sql['get_data_mutasi_penghargaan_by_id']="
SELECT 
	p.phgId as id,
   p.phgPegKode as nip, 
   p.phgJnsphgrId as jpid,
   p.phgNama as nama,
   p.phgTahun as tahun,
   p.phgPemberi as pemberi,
   j.jnsphgrNama as jplabel,
   p.phgUpload as upload
FROM
	sdm_penghargaan p
	LEFT JOIN sdm_ref_jenis_penghargaan j ON (j.jnsphgrId=p.phgJnsphgrId)
WHERE 
   p.phgPegKode='%s' AND
   p.phgId='%s' 
"; 

$sql['get_combo_jenis_penghargaan']="
SELECT
	jnsphgrId as id,
	jnsphgrNama as name
FROM
	sdm_ref_jenis_penghargaan 
";

/*
phgId
phgPegKode
phgJnsphgrId
phgNama
phgTahun
phgPemberi
*/
// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_penghargaan(phgPegKode,phgJnsphgrId,phgNama,phgTahun,phgPemberi,phgUpload)
VALUES('%s','%s','%s','%s','%s','%s')   
";

$sql['do_update'] = "
UPDATE sdm_penghargaan
SET 
   	phgPegKode = '%s',
      phgJnsphgrId = '%s',
      phgNama = '%s',
      phgTahun = '%s',
      phgPemberi = '%s',
	  phgUpload = '%s'
WHERE 
	phgId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_penghargaan
WHERE 
   phgId = %s  
";

?>
