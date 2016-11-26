<?php


//===GET===
$sql['get_count_pegawai'] = "
SELECT 
   COUNT(pegId) AS total
FROM 
   pub_pegawai
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
   pegKodeResmi like '%s' %s
   pegNama like '%s'
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

$sql['get_list_mutasi_kepakaran_dosen']="
SELECT DISTINCT 
	p.dosenPakarId as id,
	p.dosenPakarPegKode as nip,
	p.dosenKepakaranId as bidangid,
	b.kepakaranrNama as bidanglabel,
	p.dosenKepakaranUpload as upload
FROM
	sdm_dosen_kepakaran p
	LEFT JOIN sdm_ref_kepakaran b ON (p.dosenKepakaranId=b.kepakaranrId)
WHERE 
   p.dosenPakarPegKode='%s'
"; 

$sql['get_data_mutasi_kepakaran_dosen_by_id']="
SELECT 
	p.dosenPakarId as id,
	p.dosenPakarPegKode as nip,
	p.dosenKepakaranId as bidangid,
	b.kepakaranrNama as bidanglabel,
	p.dosenKepakaranUpload as upload
FROM
	sdm_dosen_kepakaran p
	LEFT JOIN sdm_ref_kepakaran b ON (p.dosenKepakaranId=b.kepakaranrId)
WHERE 
   p.dosenPakarPegKode='%s' AND
   p.dosenPakarId='%s' 
"; 

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_dosen_kepakaran
   (dosenPakarPegKode,dosenKepakaranId,dosenKepakaranUpload)
VALUES('%s','%s','%s')   
";

$sql['do_update'] = "
UPDATE sdm_dosen_kepakaran
SET 
	dosenPakarPegKode = '%s',
	dosenKepakaranId = '%s',
	dosenKepakaranUpload = '%s'
WHERE 
	dosenPakarId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_dosen_kepakaran
WHERE 
   dosenPakarId = %s  
";

?>
