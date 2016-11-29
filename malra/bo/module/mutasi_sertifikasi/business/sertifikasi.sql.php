<?php

$sql['get_data_pegawai']="
SELECT 
	pegId as id,
	CONCAT(IFNULL(pegGelarDepan,''),' ',pegNama,' ',IFNULL(pegGelarBelakang,'')) as name,
	pegKodeResmi as kode,
	pegAlamat as alamat,
	pegNoTelp as telp,
	pegSatwilId as wil,
	pegFoto as foto,
	substring(pegTglMasukInstitusi,1,4) as masuk,
	pegdtKategori as kategori
FROM
	pub_pegawai
	LEFT JOIN sdm_pegawai_detail ON pegdtPegId = pegId
WHERE pegId='%s' 
";

$sql['get_last_usulan_sertifikasi_id'] = "
SELECT 
   MAX(srtfkId) AS id
FROM 
   sdm_sertifikasi
";   

$sql['get_count_usulan_sertifikasi'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   sdm_sertifikasi
";   

$sql['get_usulan_sertifikasi']="
SELECT 
   srtfkId AS 'id',
   srtfkTahun AS 'tahun',
   srtfkPeriodeAwal AS 'mulai',
   srtfkPeriodeAkhir AS 'selesai',
   COUNT(srtfkdetPegId) AS 'jumlahpeserta',
   SUM(IF(srtfkdetNoPeserta IS NULL,0,1)) AS 'verifypeserta',
   SUM(IF(srtfkdetHasilAkhir='LULUS',1,0)) AS 'luluspeserta',
   SUM(IF(srtfkdetHasilAkhir='BELUM LULUS',1,0)) AS 'tidakluluspeserta',
   srtfkModifiedDate AS 'lastupdate'
FROM 
   sdm_sertifikasi
   LEFT JOIN sdm_sertifikasi_detail ON srtfkId=srtfkdetSrtfkId
GROUP BY srtfkId
ORDER BY srtfkTahun
LIMIT %s,%s
";

$sql['get_combo_tahun_usulan']="
SELECT 
   srtfkId AS 'id',
   srtfkTahun AS 'name'
FROM 
   sdm_sertifikasi
";

$sql['get_usulan_sertifikasi_by_id']="
SELECT 
	srtfkId,
	srtfkTahun,
	srtfkPeriodeAwal,
	srtfkPeriodeAkhir
FROM 
	sdm_sertifikasi
WHERE
	srtfkId='%s'
";

$sql['get_list_peserta_sertifikasi_by_id']="
SELECT 
	sdm_sertifikasi.*,
	sdm_sertifikasi_detail.*,
	CONCAT(IFNULL(pegGelarDepan,''),' ',pegNama,' ',IFNULL(pegGelarBelakang,'')) AS srtfkdetNama,
	IF((srtfkdetNoPeserta IS NULL) OR (srtfkdetNoPeserta=''),0,1) AS srtfkdetIsVerifikasi,
	IF((srtfkdetNoPeserta IS NULL) OR (srtfkdetNoPeserta=''),'','none') AS srtfkdetVerifikasi,
	
	pegKodeResmi AS srtfkdetNip,
	pegGelarDepan AS srtfkdetGelarDepan,
	pegGelarBelakang AS srtfkdetGelarBelakang,
	pegTmpLahir AS srtfkdetTempatLahir,
	pegTglLahir AS srtfkdetTanggalLahir,
	pegKelamin AS srtfkdetJenisKelamin,
	pegAlamat AS srtfkdetAlamat,
	CONCAT(pegNoHp,' ', pegEmail) AS srtfkdetKontak,
	jabfungrId AS srtfkdetJabfungrId,
	jabfungrNama AS srtfkdetJabfungrNama,
	pktgolrId AS srtfkdetPktgolrId,
	pktgolrNama AS srtfkdetPktgolrNama,
	(SELECT setValue FROM sdm_setting WHERE setNama='InstitusiKode') AS srtfkdetInstitusiKode,
	(SELECT setValue FROM sdm_setting WHERE setNama='InstitusiNama') AS srtfkdetInstitusiNama,
	kepakaranrKode AS srtfkdetBidangKode,
	kepakaranrNama AS srtfkdetBidangNama,
	IFNULL(CONCAT(s1.pddkJurusan,', ',s1.pddkInstitusi),'') AS srtfkdetS1,
	IFNULL(CONCAT(s2.pddkJurusan,', ',s2.pddkInstitusi),'') AS srtfkdetS2,
	IFNULL(CONCAT(s3.pddkJurusan,', ',s3.pddkInstitusi),'') AS srtfkdetS3
FROM 
	sdm_sertifikasi_detail
	LEFT JOIN sdm_sertifikasi ON srtfkdetSrtfkId=srtfkId
	LEFT JOIN pub_pegawai ON srtfkdetPegId=pegId
	INNER JOIN sdm_jabatan_fungsional ON jbtnPegKode=pegId AND jbtnStatus='Aktif'
	INNER JOIN pub_ref_jabatan_fungsional ON jbtnJabfungrId=jabfungrId AND jabfungrJenisrId=7
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan ON pktgolPktgolrId=pktgolrId
   
	LEFT JOIN sdm_pendidikan s1 ON s1.pddkPegKode=pegId AND s1.pddkStatusTamat='Selesai' AND s1.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S1')
	LEFT JOIN sdm_pendidikan s2 ON s2.pddkPegKode=pegId AND s2.pddkStatusTamat='Selesai' AND s2.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S2')
	LEFT JOIN sdm_pendidikan s3 ON s3.pddkPegKode=pegId AND s3.pddkStatusTamat='Selesai' AND s3.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S3')
   
	LEFT JOIN sdm_dosen_kepakaran ON dosenPakarPegKode=pegId
	LEFT JOIN sdm_ref_kepakaran ON dosenKepakaranId=kepakaranrId
WHERE
	srtfkdetSrtfkId='%s' AND srtfkdetIsLock=0
	AND (pegKodeResmi LIKE '%s' OR pegNama LIKE '%s' OR srtfkdetNoPeserta LIKE '%s')
ORDER BY IFNULL(RIGHT(srtfkdetNoPeserta,4),9999)
";

$sql['get_list_peserta_sertifikasi_by_id_verify']="
SELECT 
	sdm_sertifikasi.*,
	sdm_sertifikasi_detail.*,
	CONCAT(IFNULL(pegGelarDepan,''),' ',pegNama,' ',IFNULL(pegGelarBelakang,'')) AS srtfkdetNama,
	IF((srtfkdetNoPeserta IS NULL) OR (srtfkdetNoPeserta=''),0,1) AS srtfkdetIsVerifikasi,
	IF((srtfkdetNoPeserta IS NULL) OR (srtfkdetNoPeserta=''),'','none') AS srtfkdetVerifikasi,
	
	IF(srtfkdetPersep='LULUS','SELECTED','') AS srtfkdetPersep_lulus_selected,
	IF(srtfkdetPersep='BELUM LULUS','SELECTED','') AS srtfkdetPersep_belum_selected,
	IF(srtfkdetPersep IS NULL,'SELECTED','') AS srtfkdetPersep_null_selected,
	
	IF(srtfkdetPerson='LULUS','SELECTED','') AS srtfkdetPerson_lulus_selected,
	IF(srtfkdetPerson='BELUM LULUS','SELECTED','') AS srtfkdetPerson_belum_selected,
	IF(srtfkdetPerson IS NULL,'SELECTED','') AS srtfkdetPerson_null_selected,
	
	IF(srtfkdetGabPAK='LULUS','SELECTED','') AS srtfkdetGabPAK_lulus_selected,
	IF(srtfkdetGabPAK='BELUM LULUS','SELECTED','') AS srtfkdetGabPAK_belum_selected,
	IF(srtfkdetGabPAK IS NULL,'SELECTED','') AS srtfkdetGabPAK_null_selected,
	
	IF(srtfkdetKonst='LULUS','SELECTED','') AS srtfkdetKonst_lulus_selected,
	IF(srtfkdetKonst='BELUM LULUS','SELECTED','') AS srtfkdetKonst_belum_selected,
	IF(srtfkdetKonst IS NULL,'SELECTED','') AS srtfkdetKonst_null_selected,
	
	IF(srtfkdetHasilAkhir='LULUS','SELECTED','') AS srtfkdetHasilAkhir_lulus_selected,
	IF(srtfkdetHasilAkhir='BELUM LULUS','SELECTED','') AS srtfkdetHasilAkhir_belum_selected,
	IF(srtfkdetHasilAkhir IS NULL,'SELECTED','') AS srtfkdetHasilAkhir_null_selected,
	
	pegKodeResmi AS srtfkdetNip,
	pegGelarDepan AS srtfkdetGelarDepan,
	pegGelarBelakang AS srtfkdetGelarBelakang,
	pegTmpLahir AS srtfkdetTempatLahir,
	pegTglLahir AS srtfkdetTanggalLahir,
	pegKelamin AS srtfkdetJenisKelamin,
	pegAlamat AS srtfkdetAlamat,
	CONCAT(pegNoHp,' ', pegEmail) AS srtfkdetKontak,
	jabfungrId AS srtfkdetJabfungrId,
	jabfungrNama AS srtfkdetJabfungrNama,
	pktgolrId AS srtfkdetPktgolrId,
	pktgolrNama AS srtfkdetPktgolrNama,
	(SELECT setValue FROM sdm_setting WHERE setNama='InstitusiKode') AS srtfkdetInstitusiKode,
	(SELECT setValue FROM sdm_setting WHERE setNama='InstitusiNama') AS srtfkdetInstitusiNama,
	kepakaranrKode AS srtfkdetBidangKode,
	kepakaranrNama AS srtfkdetBidangNama,
	IFNULL(CONCAT(s1.pddkJurusan,', ',s1.pddkInstitusi),'') AS srtfkdetS1,
	IFNULL(CONCAT(s2.pddkJurusan,', ',s2.pddkInstitusi),'') AS srtfkdetS2,
	IFNULL(CONCAT(s3.pddkJurusan,', ',s3.pddkInstitusi),'') AS srtfkdetS3
FROM 
	sdm_sertifikasi_detail
	LEFT JOIN sdm_sertifikasi ON srtfkdetSrtfkId=srtfkId
	LEFT JOIN pub_pegawai ON srtfkdetPegId=pegId
	INNER JOIN sdm_jabatan_fungsional ON jbtnPegKode=pegId AND jbtnStatus='Aktif'
	INNER JOIN pub_ref_jabatan_fungsional ON jbtnJabfungrId=jabfungrId AND jabfungrJenisrId=7
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan ON pktgolPktgolrId=pktgolrId
   
	LEFT JOIN sdm_pendidikan s1 ON s1.pddkPegKode=pegId AND s1.pddkStatusTamat='Selesai' AND s1.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S1')
	LEFT JOIN sdm_pendidikan s2 ON s2.pddkPegKode=pegId AND s2.pddkStatusTamat='Selesai' AND s2.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S2')
	LEFT JOIN sdm_pendidikan s3 ON s3.pddkPegKode=pegId AND s3.pddkStatusTamat='Selesai' AND s3.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S3')
   
	LEFT JOIN sdm_dosen_kepakaran ON dosenPakarPegKode=pegId
	LEFT JOIN sdm_ref_kepakaran ON dosenKepakaranId=kepakaranrId
WHERE
	srtfkdetSrtfkId='%s' AND srtfkdetIsLock=0 AND NOT (srtfkdetNoPeserta IS NULL OR srtfkdetNoPeserta='')
	AND (pegKodeResmi LIKE '%s' OR pegNama LIKE '%s' OR srtfkdetNoPeserta LIKE '%s')
ORDER BY IFNULL(RIGHT(srtfkdetNoPeserta,4),9999)
";

$sql['get_detail_peserta_sertifikasi_by_id']="
SELECT 
	sdm_sertifikasi.*,
	sdm_sertifikasi_detail.*,
	CONCAT(IFNULL(pegGelarDepan,''),' ',pegNama,' ',IFNULL(pegGelarBelakang,'')) AS srtfkdetNama,
	IF((srtfkdetNoPeserta IS NULL) OR (srtfkdetNoPeserta=''),0,1) AS srtfkdetIsVerifikasi,
	IF((srtfkdetNoPeserta IS NULL) OR (srtfkdetNoPeserta=''),'','none') AS srtfkdetVerifikasi,
	
	IF(srtfkdetPersep='LULUS','SELECTED','') AS srtfkdetPersep_lulus_selected,
	IF(srtfkdetPersep='BELUM LULUS','SELECTED','') AS srtfkdetPersep_belum_selected,
	IF(srtfkdetPersep IS NULL,'SELECTED','') AS srtfkdetPersep_null_selected,
	
	IF(srtfkdetPerson='LULUS','SELECTED','') AS srtfkdetPerson_lulus_selected,
	IF(srtfkdetPerson='BELUM LULUS','SELECTED','') AS srtfkdetPerson_belum_selected,
	IF(srtfkdetPerson IS NULL,'SELECTED','') AS srtfkdetPerson_null_selected,
	
	IF(srtfkdetGabPAK='LULUS','SELECTED','') AS srtfkdetGabPAK_lulus_selected,
	IF(srtfkdetGabPAK='BELUM LULUS','SELECTED','') AS srtfkdetGabPAK_belum_selected,
	IF(srtfkdetGabPAK IS NULL,'SELECTED','') AS srtfkdetGabPAK_null_selected,
	
	IF(srtfkdetKonst='LULUS','SELECTED','') AS srtfkdetKonst_lulus_selected,
	IF(srtfkdetKonst='BELUM LULUS','SELECTED','') AS srtfkdetKonst_belum_selected,
	IF(srtfkdetKonst IS NULL,'SELECTED','') AS srtfkdetKonst_null_selected,
	
	IF(srtfkdetHasilAkhir='LULUS','SELECTED','') AS srtfkdetHasilAkhir_lulus_selected,
	IF(srtfkdetHasilAkhir='BELUM LULUS','SELECTED','') AS srtfkdetHasilAkhir_belum_selected,
	IF(srtfkdetHasilAkhir IS NULL,'SELECTED','') AS srtfkdetHasilAkhir_null_selected,
	
	pegKodeResmi AS srtfkdetNip,
	pegGelarDepan AS srtfkdetGelarDepan,
	pegGelarBelakang AS srtfkdetGelarBelakang,
	pegTmpLahir AS srtfkdetTempatLahir,
	pegTglLahir AS srtfkdetTanggalLahir,
	CONCAT(pegTmpLahir,',',pegTglLahir) as srtfkdetTTL,
	pegKelamin AS srtfkdetJenisKelamin,
	pegAlamat AS srtfkdetAlamat,
	CONCAT(pegNoHp,' ', pegEmail) AS srtfkdetKontak,
	jabfungrId AS srtfkdetJabfungrId,
	jabfungrNama AS srtfkdetJabfungrNama,
	pktgolrId AS srtfkdetPktgolrId,
	pktgolrNama AS srtfkdetPktgolrNama,
	(SELECT setValue FROM sdm_setting WHERE setNama='InstitusiKode') AS srtfkdetInstitusiKode,
	(SELECT setValue FROM sdm_setting WHERE setNama='InstitusiNama') AS srtfkdetInstitusiNama,
	kepakaranrKode AS srtfkdetBidangKode,
	kepakaranrNama AS srtfkdetBidangNama,
	IFNULL(CONCAT(s1.pddkJurusan,', ',s1.pddkInstitusi),'') AS srtfkdetS1,
	IFNULL(CONCAT(s2.pddkJurusan,', ',s2.pddkInstitusi),'') AS srtfkdetS2,
	IFNULL(CONCAT(s3.pddkJurusan,', ',s3.pddkInstitusi),'') AS srtfkdetS3
FROM 
	sdm_sertifikasi_detail
	LEFT JOIN sdm_sertifikasi ON srtfkdetSrtfkId=srtfkId
	LEFT JOIN pub_pegawai ON srtfkdetPegId=pegId
	INNER JOIN sdm_jabatan_fungsional ON jbtnPegKode=pegId AND jbtnStatus='Aktif'
	INNER JOIN pub_ref_jabatan_fungsional ON jbtnJabfungrId=jabfungrId AND jabfungrJenisrId=7
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan ON pktgolPktgolrId=pktgolrId
   
	LEFT JOIN sdm_pendidikan s1 ON s1.pddkPegKode=pegId AND s1.pddkStatusTamat='Selesai' AND s1.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S1')
	LEFT JOIN sdm_pendidikan s2 ON s2.pddkPegKode=pegId AND s2.pddkStatusTamat='Selesai' AND s2.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S2')
	LEFT JOIN sdm_pendidikan s3 ON s3.pddkPegKode=pegId AND s3.pddkStatusTamat='Selesai' AND s3.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S3')
   
	LEFT JOIN sdm_dosen_kepakaran ON dosenPakarPegKode=pegId
	LEFT JOIN sdm_ref_kepakaran ON dosenKepakaranId=kepakaranrId
WHERE
	srtfkdetPegId='%s' AND srtfkdetSrtfkId=(SELECT srtfkId FROM sdm_sertifikasi WHERE srtfkTahun='%s')
";

$sql['get_list_peserta_sertifikasi_by_id_detail']="
SELECT 
	sdm_sertifikasi.*,
	sdm_sertifikasi_detail.*,
	srtfkTahun as srtfkdetTahun,
	CONCAT(IFNULL(pegGelarDepan,''),' ',pegNama,' ',IFNULL(pegGelarBelakang,'')) AS srtfkdetNama,
	IF((srtfkdetNoPeserta IS NULL) OR (srtfkdetNoPeserta=''),0,1) AS srtfkdetIsVerifikasi,
	IF((srtfkdetNoPeserta IS NULL) OR (srtfkdetNoPeserta=''),'','none') AS srtfkdetVerifikasi,
	
	IF(srtfkdetPersep='LULUS','SELECTED','') AS srtfkdetPersep_lulus_selected,
	IF(srtfkdetPersep='BELUM LULUS','SELECTED','') AS srtfkdetPersep_belum_selected,
	IF(srtfkdetPersep IS NULL,'SELECTED','') AS srtfkdetPersep_null_selected,
	
	IF(srtfkdetPerson='LULUS','SELECTED','') AS srtfkdetPerson_lulus_selected,
	IF(srtfkdetPerson='BELUM LULUS','SELECTED','') AS srtfkdetPerson_belum_selected,
	IF(srtfkdetPerson IS NULL,'SELECTED','') AS srtfkdetPerson_null_selected,
	
	IF(srtfkdetGabPAK='LULUS','SELECTED','') AS srtfkdetGabPAK_lulus_selected,
	IF(srtfkdetGabPAK='BELUM LULUS','SELECTED','') AS srtfkdetGabPAK_belum_selected,
	IF(srtfkdetGabPAK IS NULL,'SELECTED','') AS srtfkdetGabPAK_null_selected,
	
	IF(srtfkdetKonst='LULUS','SELECTED','') AS srtfkdetKonst_lulus_selected,
	IF(srtfkdetKonst='BELUM LULUS','SELECTED','') AS srtfkdetKonst_belum_selected,
	IF(srtfkdetKonst IS NULL,'SELECTED','') AS srtfkdetKonst_null_selected,
	
	IF(srtfkdetHasilAkhir='LULUS','SELECTED','') AS srtfkdetHasilAkhir_lulus_selected,
	IF(srtfkdetHasilAkhir='BELUM LULUS','SELECTED','') AS srtfkdetHasilAkhir_belum_selected,
	IF(srtfkdetHasilAkhir IS NULL,'SELECTED','') AS srtfkdetHasilAkhir_null_selected,
	
	pegKodeResmi AS srtfkdetNip,
	pegGelarDepan AS srtfkdetGelarDepan,
	pegGelarBelakang AS srtfkdetGelarBelakang,
	pegTmpLahir AS srtfkdetTempatLahir,
	pegTglLahir AS srtfkdetTanggalLahir,
	CONCAT(pegTmpLahir,',',pegTglLahir) as srtfkdetTTL,
	pegKelamin AS srtfkdetJenisKelamin,
	pegAlamat AS srtfkdetAlamat,
	CONCAT(pegNoHp,' ', pegEmail) AS srtfkdetKontak,
	jabfungrId AS srtfkdetJabfungrId,
	jabfungrNama AS srtfkdetJabfungrNama,
	pktgolrId AS srtfkdetPktgolrId,
	pktgolrNama AS srtfkdetPktgolrNama,
	(SELECT setValue FROM sdm_setting WHERE setNama='InstitusiKode') AS srtfkdetInstitusiKode,
	(SELECT setValue FROM sdm_setting WHERE setNama='InstitusiNama') AS srtfkdetInstitusiNama,
	kepakaranrKode AS srtfkdetBidangKode,
	kepakaranrNama AS srtfkdetBidangNama,
	IFNULL(CONCAT(s1.pddkJurusan,', ',s1.pddkInstitusi),'') AS srtfkdetS1,
	IFNULL(CONCAT(s2.pddkJurusan,', ',s2.pddkInstitusi),'') AS srtfkdetS2,
	IFNULL(CONCAT(s3.pddkJurusan,', ',s3.pddkInstitusi),'') AS srtfkdetS3
FROM 
	sdm_sertifikasi_detail
	LEFT JOIN sdm_sertifikasi ON srtfkdetSrtfkId=srtfkId
	LEFT JOIN pub_pegawai ON srtfkdetPegId=pegId
	INNER JOIN sdm_jabatan_fungsional ON jbtnPegKode=pegId AND jbtnStatus='Aktif'
	INNER JOIN pub_ref_jabatan_fungsional ON jbtnJabfungrId=jabfungrId AND jabfungrJenisrId=7
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan ON pktgolPktgolrId=pktgolrId
   
	LEFT JOIN sdm_pendidikan s1 ON s1.pddkPegKode=pegId AND s1.pddkStatusTamat='Selesai' AND s1.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S1')
	LEFT JOIN sdm_pendidikan s2 ON s2.pddkPegKode=pegId AND s2.pddkStatusTamat='Selesai' AND s2.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S2')
	LEFT JOIN sdm_pendidikan s3 ON s3.pddkPegKode=pegId AND s3.pddkStatusTamat='Selesai' AND s3.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S3')
   
	LEFT JOIN sdm_dosen_kepakaran ON dosenPakarPegKode=pegId
	LEFT JOIN sdm_ref_kepakaran ON dosenKepakaranId=kepakaranrId
WHERE 1=1
	%srtfkid% %hasilakhir%
ORDER BY IFNULL(LEFT(srtfkdetNoPeserta,8),999999999), IFNULL(RIGHT(srtfkdetNoPeserta,4),9999)
";

$sql['cek_peserta_sertifikasi'] = "
SELECT
	COUNT(*) as total
FROM
	sdm_sertifikasi_detail 
WHERE
	srtfkdetSrtfkId='%s' AND srtfkdetPegId='%s'
";


// DO-----------
$sql['do_modified_sertifikasi'] = "
UPDATE sdm_sertifikasi
SET
	srtfkModifiedDate=now()
WHERE
	srtfkId='%s'
";

$sql['do_add_usulan_sertifikasi'] = "
	INSERT INTO sdm_sertifikasi
	SET
		srtfkTahun='%s',
		srtfkPeriodeAwal='%s',
		srtfkPeriodeAkhir='%s',
		srtfkCreatedUserId='%s',
		srtfkCreatedDate=now(),
		srtfkModifiedDate=now()
";

$sql['do_update_usulan_sertifikasi'] = "
	UPDATE sdm_sertifikasi
	SET
		srtfkTahun='%s',
		srtfkPeriodeAwal='%s',
		srtfkPeriodeAkhir='%s',
		srtfkModifiedUserId='%s',
		srtfkModifiedDate=now()
	WHERE
		srtfkId='%s'
";

$sql['do_delete_peserta_sertifikasi'] = "
	DELETE FROM sdm_sertifikasi_detail
	WHERE
		srtfkdetSrtfkId=%s
		AND NOT ( %filter% )
";

$sql['do_add_peserta_sertifikasi'] = "
INSERT INTO sdm_sertifikasi_detail 
SET
	srtfkdetSrtfkId='%s',
	srtfkdetPegId='%s'
";

$sql['do_update_no_peserta_sertifikasi_manual'] = "
	UPDATE sdm_sertifikasi_detail
	SET
		srtfkdetNoPeserta='%s'
	WHERE
		srtfkdetSrtfkId=(SELECT srtfkId FROM sdm_sertifikasi WHERE srtfkTahun='%s') AND srtfkdetPegId='%s'
";

$sql['get_nomor_peserta'] = "
SELECT
		  CONCAT(
			RIGHT(%s,2),
			'1',
			IFNULL((SELECT setValue FROM sdm_setting WHERE setNama='InstitusiKode'),'0000'),
			'1',
			IFNULL((SELECT kepakaranrKode FROM sdm_dosen_kepakaran INNER JOIN sdm_ref_kepakaran ON  dosenKepakaranId=kepakaranrId WHERE dosenPakarPegKode='%s' LIMIT 1),'000'),
			( SELECT 
				LPAD(IFNULL(MAX(RIGHT(a.srtfkdetNoPeserta,4))+1,1),4,0)
			  FROM 
				sdm_sertifikasi_detail a
			  WHERE 
				a.srtfkdetNoPeserta LIKE CONCAT(RIGHT(%s,2),'1',IFNULL((SELECT setValue FROM sdm_setting WHERE setNama='InstitusiKode'),'0000'),'1%%')
			)) AS number
";

$sql['do_update_penilaian'] = "
	UPDATE sdm_sertifikasi_detail
	SET
		srtfkdetNo='%s',
		srtfkdetPersep='%s',
		srtfkdetPerson='%s',
		srtfkdetGabPAK='%s',
		srtfkdetKonst='%s',
		srtfkdetHasilAkhir='%s',
		srtfkdetATDL='%s',
		srtfkdetAsesorI='%s',
		srtfkdetAsesorII='%s',
		srtfkdetIsLock='%s'
	WHERE
		srtfkdetSrtfkId=(SELECT srtfkId FROM sdm_sertifikasi WHERE srtfkTahun='%s') AND srtfkdetPegId='%s'
";

?>
