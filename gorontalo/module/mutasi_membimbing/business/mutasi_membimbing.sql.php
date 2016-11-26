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
   COUNT(dosenMembimbingId) AS total
FROM 
   sdm_dosen_pmembimbing
WHERE 
   dosenMembimbingPegKode='%s'
";

$sql['get_count_mutasi_integrasi']="
SELECT 
	COUNT(dsntaPegNip) as total
FROM
	s_dosen_tugas_akhir
WHERE 
	dsntaPegNip='%s'
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

$sql['get_list_mutasi_membimbing']="
SELECT DISTINCT 
	p.dosenMembimbingId as id,
	p.dosenMembimbingPegKode as nip,
	p.dosenMembimbingJenis as jenis,
	p.dosenMembimbingSemester as semester,
	p.dosenMembimbingNimMahasiswa as nim_mhs,
	p.dosenMembimbingNamaMahasiswa as nama_mhs,
	p.dosenMembimbingJudulTa as judul_ta,
	p.dosenMembimbingStatus as status,
	p.dosenMembimbingUpload as upload
FROM
	sdm_dosen_membimbing p
WHERE 
   p.dosenMembimbingPegKode='%s'
";

$sql['get_list_mutasi_membimbing_integrasi']="
SELECT DISTINCT 
	dsntaPegNip AS nip,
	dsnprntaNama AS jenis,
	mhsNiu AS nim_mhs,
	mhsNama AS nama_mhs,
	taJudul AS judul_ta
FROM
	s_dosen_tugas_akhir
	LEFT JOIN s_tugas_akhir ON dsntaTaId=taId
	LEFT JOIN mahasiswa ON mhsNiu=taMhsNiu
	LEFT JOIN s_dosen_peran_ta_ref ON dsnprntaId=dsntaDsnprntaId
WHERE 
	dsntaPegNip='%s'
"; 

$sql['get_data_mutasi_membimbing_by_id']="
SELECT 
	p.dosenMembimbingId as id,
	p.dosenMembimbingPegKode as nip,
	p.dosenMembimbingJenis as jenis,
	p.dosenMembimbingSemester as semester,
	p.dosenMembimbingNimMahasiswa as nim_mhs,
	p.dosenMembimbingNamaMahasiswa as nama_mhs,
	p.dosenMembimbingJudulTa as judul_ta,
	p.dosenMembimbingStatus as status,
	p.dosenMembimbingUpload as upload
FROM
	sdm_dosen_membimbing p
WHERE 
   p.dosenMembimbingPegKode='%s' AND
   p.dosenMembimbingId='%s' 
"; 

// DO-----------
$sql['do_add'] = "
INSERT INTO sdm_dosen_membimbing(
			dosenMembimbingPegKode,
			dosenMembimbingSemester,
			dosenMembimbingNimMahasiswa,
			dosenMembimbingNamaMahasiswa,
			dosenMembimbingJenis,
			dosenMembimbingJudulTa,
			dosenMembimbingStatus,
			dosenMembimbingUpload
		)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s')   
";

$sql['do_update'] = "
UPDATE sdm_dosen_membimbing
SET 
    dosenMembimbingPegKode = '%s',
	dosenMembimbingSemester = '%s',
	dosenMembimbingNimMahasiswa = '%s',
	dosenMembimbingNamaMahasiswa = '%s',
	dosenMembimbingJenis = '%s',
	dosenMembimbingJudulTa = '%s',
	dosenMembimbingStatus = '%s',
	dosenMembimbingUpload = '%s'
WHERE 
	dosenMembimbingId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_dosen_membimbing
WHERE 
   dosenMembimbingId = %s  
";

?>
