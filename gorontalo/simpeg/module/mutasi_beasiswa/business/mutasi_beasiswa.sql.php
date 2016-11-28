
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

$sql['get_combo_jenis_jenjang']="
SELECT
	pendId AS id,
	pendNama AS name
FROM
	pub_ref_pendidikan
WHERE 
	pendPendkelId=4
ORDER BY
  pendNama ASC
";

$sql['get_combo_jenis_dana']="
SELECT
	asldnrId AS id,
	asldnrNama AS name
FROM
	sdm_ref_asal_dana
ORDER BY
  asldnrId ASC
";

/*
dosenDiampuId
dosenDiampuPegKode
dosenDiampuUniv
dosenDiampuMataKuliah
dosenDiampuStatus
*/
$sql['get_list_mutasi_beasiswa']="
SELECT DISTINCT 
	b.beasiswaId AS id,
	b.beasiswaPegKode AS nip,
	b.beasiswaTahunDiterima AS tahun_terima,
	pendNama AS jenjang,
	b.beasiswaNama AS nama,
	asldnrNama AS dana,
	b.beasiswaTahun AS tahun,
	b.beasiswaBulan AS bulan,
	b.beasiswaKet AS keterangan,
	b.beasiswaUpload AS upload
FROM
	sdm_beasiswa b
	LEFT JOIN pub_ref_pendidikan ON b.beasiswaPendId=pendId
	LEFT JOIN sdm_ref_asal_dana ON b.beasiswaAslDnrId=asldnrId
WHERE 
   b.beasiswaPegKode='%s'
ORDER BY beasiswaTahunDiterima DESC
"; 

$sql['get_data_mutasi_beasiswa_by_id']="
SELECT  
	b.beasiswaId AS id,
	b.beasiswaPegKode AS nip,
	b.beasiswaTahunDiterima AS tahun_terima,
	pendId AS jenjang,
	b.beasiswaNama AS nama,
	asldnrId AS dana,
	b.beasiswaTahun AS tahun,
	b.beasiswaBulan AS bulan,
	b.beasiswaKet AS keterangan,
	b.beasiswaUpload AS upload
FROM
	sdm_beasiswa b
	LEFT JOIN pub_ref_pendidikan ON b.beasiswaPendId=pendId
	LEFT JOIN sdm_ref_asal_dana ON b.beasiswaAslDnrId=asldnrId
WHERE 
   b.beasiswaPegKode='%s' AND
   b.beasiswaId='%s' 
"; 

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_beasiswa
   (beasiswaPegKode,beasiswaTahunDiterima,beasiswaPendId,beasiswaNama,beasiswaAslDnrId,beasiswaTahun,beasiswaBulan,beasiswaKet,beasiswaUpload)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s')
";

$sql['do_update'] = "
UPDATE sdm_beasiswa
SET 
	beasiswaPegKode = '%s',
	beasiswaTahunDiterima = '%s',
	beasiswaPendId = '%s',
	beasiswaNama = '%s',
	beasiswaAslDnrId = '%s',
	beasiswaTahun = '%s',
	beasiswaBulan = '%s',
	beasiswaKet = '%s',
	beasiswaUpload = '%s'
WHERE 
	beasiswaId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_beasiswa
WHERE 
   beasiswaId = %s  
";

?>
