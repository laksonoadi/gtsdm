<?php


//===GET===
$sql['get_combo_jenis_organisasi'] = "
SELECT 
   jnsorgId as id,
   jnsorgNama as name
FROM 
   sdm_ref_jenis_organisasi
ORDER BY jnsorgNama
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

$sql['get_list_mutasi_organisasi_pegawai']="
SELECT DISTINCT 
	p.orgId as id,
	p.orgPegKode as nip,
	jnsorgNama as jenis_label,
	p.orgJenis as jenis,
	p.orgNama as nama,
	p.orgJabatan as jabatan,
	p.orgTahunMulai as mulai,
	p.orgTahunSelesai as selesai,
	p.orgUpload as upload
FROM
	sdm_organisasi p
	LEFT JOIN sdm_ref_jenis_organisasi ON p.orgJenis=jnsorgId
WHERE 
   p.orgPegKode='%s'
"; 

$sql['get_data_mutasi_organisasi_pegawai_by_id']="
SELECT 
	p.orgId as id,
	p.orgPegKode as nip,
	p.orgJenis as jenis,
	p.orgNama as nama,
	p.orgJabatan as jabatan,
	p.orgTahunMulai as mulai,
	p.orgTahunSelesai as selesai,
	p.orgUpload as upload
FROM
	sdm_organisasi p
WHERE 
   p.orgPegKode='%s' AND
   p.orgId='%s' 
"; 

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_organisasi
   (orgPegKode,orgJenis,orgNama,orgJabatan,orgTahunMulai,orgTahunSelesai,orgUpload)
VALUES('%s','%s','%s','%s','%s','%s','%s')   
";

$sql['do_update'] = "
UPDATE sdm_organisasi
SET 
	orgPegKode = '%s',
	orgJenis = '%s',
	orgNama = '%s',
	orgJabatan = '%s',
	orgTahunMulai = '%s',
	orgTahunSelesai = '%s',
	orgUpload ='%s'
WHERE 
	orgId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_organisasi
WHERE 
   orgId = %s  
";

?>
