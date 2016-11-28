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


$sql['get_list_mutasi_hukuman']="
SELECT DISTINCT 
	p.hkmId as id,
	p.hkmPegKode as nip,
	p.hkmKategori as kat,
	p.hkmJnshkmrId as jenis,
	p.hkmNama as namahkm,
	p.hkmTglMulai as mulai,
	p.hkmTglSelesai as selesai,
	p.hkmKeterangan as ket,
	j.jnshkmrNama as labelhkm,
	p.hkmUpload as upload
FROM
	sdm_hukuman p
	LEFT JOIN sdm_ref_jenis_hukuman j ON (j.jnshkmrId=p.hkmJnshkmrId)
WHERE 
   p.hkmPegKode='%s'
ORDER BY hkmTglMulai DESC
"; 

$sql['get_data_mutasi_hukuman_by_id']="
SELECT 
	p.hkmId as id,
	p.hkmPegKode as nip,
	p.hkmKategori as kat,
	p.hkmJnshkmrId as jenis,
	p.hkmNama as namahkm,
	p.hkmTglMulai as mulai,
	p.hkmTglSelesai as selesai,
	p.hkmKeterangan as ket,
	j.jnshkmrNama as labelhkm,
	p.hkmUpload as upload
FROM
	sdm_hukuman p
	LEFT JOIN sdm_ref_jenis_hukuman j ON (j.jnshkmrId=p.hkmJnshkmrId)
WHERE 
   p.hkmPegKode='%s' AND
   p.hkmId='%s' 
"; 

$sql['get_combo_jenis_hukuman']="
SELECT
	jnshkmrId as id,
	jnshkmrNama as name
FROM
	sdm_ref_jenis_hukuman 
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_hukuman
   (hkmPegKode,hkmKategori,hkmJnshkmrId,hkmNama,hkmTglMulai,hkmTglSelesai,hkmKeterangan,hkmUpload)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s')   
";

$sql['do_update'] = "
UPDATE sdm_hukuman
SET 
	hkmPegKode = '%s',
	hkmKategori = '%s',
	hkmJnshkmrId = '%s',
	hkmNama = '%s',
	hkmTglMulai = '%s',
	hkmTglSelesai = '%s',
	hkmKeterangan = '%s',
	hkmUpload = '%s'
WHERE 
	hkmId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_hukuman
WHERE 
   hkmId = %s  
";

?>
