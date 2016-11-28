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
   COUNT(statpegId) AS total
FROM 
   sdm_status_pegawai
WHERE 
   statpegPegId='%s'
";

$sql['get_list_pegawai'] = "
SELECT 
    pegId as id,
	pegNama as nama,
	pegKodeResmi as nip,
	IF(pktgolStatus='Aktif',CONCAT(pktgolPktgolrId,' - ',pktgolrNama),(select CONCAT(pktgolrId,' - ',pktgolrNama) from sdm_ref_pangkat_golongan where pktgolrId=(select pktgolPktgolrId from sdm_pangkat_golongan where pktgolPegKode=pegId and pktgolStatus='Aktif'))) as pktgol,
	IF(statpegAktif='Aktif',statrPegawai,(select statrPegawai from sdm_ref_status_pegawai where statrId=(select statpegStatrId from sdm_status_pegawai where statpegPegId=pegId and statpegAktif='Aktif'))) as statr,
	IF(a.jbtnStatus='Aktif',jabstrukrNama,(select jabstrukrNama from sdm_ref_jabatan_struktural where jabstrukrId=(select jbtnJabstrukrId from sdm_jabatan_struktural where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabstruk,
	IF(b.jbtnStatus='Aktif',jabfungrNama,(select jabfungrNama from pub_ref_jabatan_fungsional where jabfungrId=(select jbtnJabfungrId from sdm_jabatan_fungsional where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabfung
FROM 
   pub_pegawai
   LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId
   LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
   LEFT JOIN sdm_status_pegawai ON statpegPegId=pegId
   LEFT JOIN sdm_ref_status_pegawai ON statrId=statpegStatrId
   LEFT JOIN sdm_jabatan_struktural a ON a.jbtnPegKode=pegId
   LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=a.jbtnJabstrukrId
   LEFT JOIN sdm_jabatan_fungsional b ON b.jbtnPegKode=pegId
   LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=b.jbtnJabfungrId
WHERE
   pegKodeResmi like '%s' OR pegNama like '%s'
GROUP BY 
	 pegId
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

$sql['get_list_mutasi_status_pegawai']="
SELECT DISTINCT 
	p.statpegId as id,
	p.statpegPegId as nip,
	p.statpegStatrId as statr,
	p.statpegTmt as tmt,
	p.statpegPjbSk as pejabat,
	p.statpegNoSk as nosk,
	p.statpegTglSk as tgl_sk,
	p.statpegAktif as status,
	p.statpegSkUpload as upload,
	pg.statrPegawai AS statrnama
FROM
	sdm_status_pegawai p
	LEFT JOIN sdm_ref_status_pegawai pg ON (p.statpegStatrId=pg.statrId)
WHERE 
   p.statpegPegId='%s'
ORDER BY p.statpegId DESC
"; 

$sql['get_data_mutasi_status_pegawai_by_id']="
SELECT 
	p.statpegId as id,
	p.statpegPegId as nip,
	p.statpegStatrId as statr,
	p.statpegTmt as tmt,
	p.statpegPjbSk as pejabat,
	p.statpegNoSk as nosk,
	p.statpegTglSk as tgl_sk,
	p.statpegAktif as status,
	p.statpegSkUpload as upload,
	pg.statrPegawai AS statrnama
FROM
	sdm_status_pegawai p
	LEFT JOIN sdm_ref_status_pegawai pg ON (p.statpegStatrId=pg.statrId)
WHERE 
   p.statpegPegId='%s' AND
   p.statpegId='%s' 
"; 

$sql['get_combo_status']="
SELECT
	statrId as id,
	statrPegawai as name
FROM
	sdm_ref_status_pegawai
ORDER BY
  statrId ASC
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_status_pegawai
   (statpegPegId,statpegStatrId,statpegTmt,
   statpegPjbSk,statpegNoSk,statpegTglSk,
   statpegAktif,statpegSkUpload)
VALUES('%s','%s','%s',
       '%s','%s','%s',
       '%s','%s')   
";

$sql['do_update'] = "
UPDATE sdm_status_pegawai
SET 
	statpegPegId = '%s',
	statpegStatrId = '%s',
	statpegTmt = '%s',
	statpegPjbSk = '%s',
	statpegNoSk = '%s',
	statpegTglSk = '%s',
	statpegAktif = '%s',
	statpegSkUpload = '%s'
WHERE 
	statpegId = %s
"; 

$sql['do_update_status_pegawai'] = "
UPDATE pub_pegawai
SET 
	pegStatrId='%s'
WHERE 
	pegId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_status_pegawai
WHERE 
   statpegId = %s  
";


$sql['update_status']="
update 
	sdm_status_pegawai
set statpegAktif = '%s'
where statpegId != %s AND statpegPegId = '%s'
";

$sql['get_max_status']="
select 
	max(statpegId) as MAXID,
	statpegAktif as STAT
FROM sdm_status_pegawai
WHERE statpegId=(select max(statpegId) FROM sdm_status_pegawai)
group by statpegId
";

$sql['get_max_id']="
select 
	max(statpegId) as MAXID
FROM sdm_status_pegawai
";
?>
