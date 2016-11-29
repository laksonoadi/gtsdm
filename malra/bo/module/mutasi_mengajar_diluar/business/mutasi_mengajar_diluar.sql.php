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

/*
dosenDiampuId
dosenDiampuPegKode
dosenDiampuUniv
dosenDiampuMataKuliah
dosenDiampuStatus
*/
$sql['get_list_mutasi_mengajar_diluar']="
SELECT DISTINCT 
	p.dosenDiampuId as id,
	p.dosenDiampuPegKode as nip,
	p.dosenDiampuUniv as univ,
	p.dosenDiampuMataKuliah as mk,
	p.dosenDiampuStatus as status,
	p.dosenDiampuUpload as upload
FROM
	sdm_dosen_diampu p
WHERE 
   p.dosenDiampuPegKode='%s'
ORDER BY p.dosenDiampuId DESC
"; 

$sql['get_data_mutasi_hukuman_by_id']="
SELECT 
	p.dosenDiampuId as id,
	p.dosenDiampuPegKode as nip,
	p.dosenDiampuUniv as univ,
	p.dosenDiampuMataKuliah as mk,
	p.dosenDiampuStatus as status,
	p.dosenDiampuUpload as upload
FROM
	sdm_dosen_diampu p
WHERE 
   p.dosenDiampuPegKode='%s' AND
   p.dosenDiampuId='%s' 
"; 

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_dosen_diampu
   (dosenDiampuPegKode,dosenDiampuUniv,dosenDiampuMataKuliah,dosenDiampuStatus,dosenDiampuUpload)
VALUES('%s','%s','%s','%s','%s')   
";

$sql['do_update'] = "
UPDATE sdm_dosen_diampu
SET 
	dosenDiampuPegKode = '%s',
	dosenDiampuUniv = '%s',
	dosenDiampuMataKuliah = '%s',
	dosenDiampuStatus = '%s',
	dosenDiampuUpload = '%s'
WHERE 
	dosenDiampuId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_dosen_diampu
WHERE 
   dosenDiampuId = %s  
";

?>
