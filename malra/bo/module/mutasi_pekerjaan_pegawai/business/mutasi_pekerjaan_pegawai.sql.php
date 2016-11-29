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

$sql['get_list_mutasi_organisasi_pegawai']="
SELECT DISTINCT 
	p.pekId AS id,
	p.pekPegKode AS nip,
	p.pekNama AS nama,
	p.pekJabatan AS jabatan,
	p.pekTanggungJawab AS tanggungjawab,
	p.pekTahunMulai AS mulai,
	p.pekTahunSelesai AS selesai,
	p.pekStatus AS status,
	p.pekUpload AS upload
FROM
	sdm_pekerjaan p
WHERE 
   p.pekPegKode='%s'
ORDER BY p.pekTahunMulai DESC
"; 

$sql['get_data_mutasi_organisasi_pegawai_by_id']="
SELECT 
	p.pekId AS id,
	p.pekPegKode AS nip,
	p.pekNama AS nama,
	p.pekJabatan AS jabatan,
	p.pekTanggungJawab AS tanggungjawab,
	p.pekTahunMulai AS mulai,
	p.pekTahunSelesai AS selesai,
	p.pekStatus AS status,
	p.pekUpload AS upload
FROM
	sdm_pekerjaan p
WHERE 
   p.pekPegKode='%s' AND
   p.pekId='%s' 
"; 

/*
orgId
orgPegKode
orgNama
orgJabatan
orgTahunMulai
orgTahunSelesai
*/
// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_pekerjaan
   (pekPegKode,pekNama,pekJabatan,pekTanggungJawab,pekTahunMulai,pekTahunSelesai,pekStatus,pekUpload)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s')   
";

$sql['do_update'] = "
UPDATE sdm_pekerjaan
SET 
	pekPegKode = '%s',
	pekNama = '%s',
	pekJabatan = '%s',
	pekTanggungJawab = '%s',
	pekTahunMulai = '%s',
	pekTahunSelesai = '%s',
	pekStatus = '%s',
	pekUpload ='%s'
WHERE 
	pekId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_pekerjaan
WHERE 
   pekId = %s  
";

?>
