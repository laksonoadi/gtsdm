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
   COUNT(dosenMengajarId) AS total
FROM 
   sdm_dosen_pmengajar
WHERE 
   dosenMengajarPegKode='%s'
";

$sql['get_count_mutasi_integrasi']="
SELECT
	COUNT(klsId) AS total
FROM
	s_dosen_kelas
	INNER JOIN s_kelas ON dsnkKlsId=klsId
WHERE 
    dsnkDsnPegNip='%s' AND NOT (klsIsBatal IS NULL)
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

$sql['get_list_mutasi_mengajar']="
SELECT DISTINCT 
	p.dosenMengajarId as id,
	p.dosenMengajarPegKode as nip,
	p.dosenMengajarSemester as semester,
	p.dosenMengajarKodeMataKuliah as kode_mk,
	p.dosenMengajarNamaMataKuliah as nama_mk,
	p.dosenMengajarSks as sks,
	p.dosenMengajarKelas as kelas,
	p.dosenMengajarStatus as status,
	p.dosenMengajarUpload as upload
FROM
	sdm_dosen_mengajar p
WHERE 
   p.dosenMengajarPegKode='%s'
";

$sql['get_list_mutasi_mengajar_integrasi']="
SELECT
	dsnkDsnPegNip as nip,
	CONCAT(nmsemrNama,' ',semTahun) as semester,
	mkkurKode as kode_mk,
	mkkurNamaResmi as nama_mk,
	mkkurJumlahSks as sks,
	klsNama as kelas
FROM
	s_dosen_kelas
	INNER JOIN s_kelas ON dsnkKlsId=klsId
	INNER JOIN s_semester ON semId=klsSemId
	INNER JOIN s_nama_semester_ref ON nmsemrId=semNmsemrId
	INNER JOIN s_matakuliah_kurikulum ON klsMkkurId=mkkurId
WHERE 
    dsnkDsnPegNip='%s' AND NOT (klsIsBatal IS NULL)
ORDER BY semTahun, nmsemrId
"; 

$sql['get_data_mutasi_mengajar_by_id']="
SELECT 
	p.dosenMengajarId as id,
	p.dosenMengajarPegKode as nip,
	p.dosenMengajarSemester as semester,
	p.dosenMengajarKodeMataKuliah as kode_mk,
	p.dosenMengajarNamaMataKuliah as nama_mk,
	p.dosenMengajarSks as sks,
	p.dosenMengajarKelas as kelas,
	p.dosenMengajarStatus as status,
	p.dosenMengajarUpload as upload
FROM
	sdm_dosen_mengajar p
WHERE 
   p.dosenMengajarPegKode='%s' AND
   p.dosenMengajarId='%s' 
"; 

// DO-----------
$sql['do_add'] = "
INSERT INTO sdm_dosen_mengajar(
			dosenMengajarPegKode,
			dosenMengajarSemester,
			dosenMengajarKodeMataKuliah,
			dosenMengajarNamaMataKuliah,
			dosenMengajarSks,
			dosenMengajarKelas,
			dosenMengajarStatus,
			dosenMengajarUpload
		)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s')   
";

$sql['do_update'] = "
UPDATE sdm_dosen_mengajar
SET 
    dosenMengajarPegKode = '%s',
	dosenMengajarSemester = '%s',
	dosenMengajarKodeMataKuliah = '%s',
	dosenMengajarNamaMataKuliah = '%s',
	dosenMengajarSks = '%s',
	dosenMengajarKelas = '%s',
	dosenMengajarStatus = '%s',
	dosenMengajarUpload = '%s'
WHERE 
	dosenMengajarId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_dosen_mengajar
WHERE 
   dosenMengajarId = %s  
";

?>
