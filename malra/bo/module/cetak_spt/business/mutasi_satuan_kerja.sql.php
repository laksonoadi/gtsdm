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
   COUNT(satkerpegId) AS total
FROM 
   sdm_satuan_kerja_pegawai
WHERE 
   satkerpegPegId='%s'
";

$sql['get_list_pegawai'] = "
SELECT 
    pegId as id,
	pegNama as nama,
	pegKodeResmi as nip,
	IF(pktgolStatus='Aktif',CONCAT(pktgolPktgolrId,' - ',pktgolrNama),(select CONCAT(pktgolrId,' - ',pktgolrNama) from sdm_ref_pangkat_golongan where pktgolrId=(select pktgolPktgolrId from sdm_pangkat_golongan where pktgolPegKode=pegId and pktgolStatus='Aktif'))) as pktgol,
	IF(satkerpegAktif='Aktif',satkerNama,(select satkerNama from pub_satuan_kerja where satkerId=(select satkerpegSatkerId from sdm_satuan_kerja_pegawai where satkerpegPegId=pegId and satkerpegAktif='Aktif'))) as satker,
	IF(a.jbtnStatus='Aktif',jabstrukrNama,(select jabstrukrNama from sdm_ref_jabatan_struktural where jabstrukrId=(select jbtnJabstrukrId from sdm_jabatan_struktural where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabstruk,
	IF(b.jbtnStatus='Aktif',jabfungrNama,(select jabfungrNama from pub_ref_jabatan_fungsional where jabfungrId=(select jbtnJabfungrId from sdm_jabatan_fungsional where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabfung
FROM 
   pub_pegawai
   LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId
   LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
   LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId
   LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
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

$sql['get_list_mutasi_satuan_kerja_pegawai']="
SELECT DISTINCT 
	p.satkerpegId as id,
	p.satkerpegPegId as nip,
	p.satkerpegSatkerId as satker,
	p.satkerpegTmt as tmt,
	p.satkerpegPjbSk as pejabat,
	p.satkerpegNoSk as nosk,
	p.satkerpegTglSk as tgl_sk,
	p.satkerpegAktif as status,
	p.satkerpegSkUpload as upload,
	pg.satkerNama AS satkernama
FROM
	sdm_satuan_kerja_pegawai p
	LEFT JOIN pub_satuan_kerja pg ON (p.satkerpegSatkerId=pg.satkerId)
WHERE 
   p.satkerpegPegId='%s'
ORDER BY p.satkerpegId DESC
"; 

$sql['get_data_mutasi_satuan_kerja_pegawai_by_id']="
SELECT 
	p.satkerpegId as id,
	p.satkerpegPegId as nip,
	p.satkerpegSatkerId as satker,
	p.satkerpegJenPegId as jenpeg,
	p.satkerpegTmt as tmt,
	p.satkerpegPjbSk as pejabat,
	p.satkerpegNoSk as nosk,
	p.satkerpegTglSk as tgl_sk,
	p.satkerpegAktif as status,
	p.satkerpegSkUpload as upload,
	pg.satkerNama AS satkernama
FROM
	sdm_satuan_kerja_pegawai p
	LEFT JOIN pub_satuan_kerja pg ON (p.satkerpegSatkerId=pg.satkerId)
WHERE 
   p.satkerpegPegId='%s' AND
   p.satkerpegId='%s' 
"; 

$sql['get_combo_satuan_kerja']="
SELECT
	satkerId as id,
	satkerNama as name
FROM
	pub_satuan_kerja
ORDER BY
  satkerId ASC
";

$sql["get_combo_tree_satuan_kerja"] = "
SELECT SQL_CALC_FOUND_ROWS
	satkerId as id,
	satkerNama as nama,
	satkerLevel as level,
	satkerParentId as parentId
FROM pub_satuan_kerja
WHERE
   satkerParentId = %d
   --where--
ORDER BY CAST(SUBSTRING_INDEX(satkerLevel, '.', -1) AS SIGNED INT) ASC
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_satuan_kerja_pegawai
   (satkerpegPegId,satkerpegSatkerId,satkerpegJenPegId,satkerpegTmt,
   satkerpegPjbSk,satkerpegNoSk,satkerpegTglSk,
   satkerpegAktif,satkerpegSkUpload)
VALUES('%s','%s','%s',
       '%s','%s','%s',
       '%s','%s')   
";

$sql['do_update'] = "
UPDATE sdm_satuan_kerja_pegawai
SET 
	satkerpegPegId = '%s',
	satkerpegSatkerId = '%s',
	satkerpegJenPegId = '%s',
	satkerpegTmt = '%s',
	satkerpegPjbSk = '%s',
	satkerpegNoSk = '%s',
	satkerpegTglSk = '%s',
	satkerpegAktif = '%s',
	satkerpegSkUpload = '%s'
WHERE 
	satkerpegId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_satuan_kerja_pegawai
WHERE 
   satkerpegId = %s  
";


$sql['update_status']="
update 
	sdm_satuan_kerja_pegawai
set satkerpegAktif = '%s'
where satkerpegId != %s AND satkerpegPegId = '%s'
";

$sql['get_max_status']="
select 
	max(satkerpegId) as MAXID,
	satkerpegAktif as STAT
FROM sdm_satuan_kerja_pegawai
WHERE satkerpegId=(select max(satkerpegId) FROM sdm_satuan_kerja_pegawai)
group by satkerpegId
";

$sql['get_max_id']="
select 
	max(satkerpegId) as MAXID
FROM sdm_satuan_kerja_pegawai
";



$sql['get_combo_jabstruk']="
SELECT
	jabstrukrId as id,
	jabstrukrNama as name
FROM
	sdm_ref_jabatan_struktural
ORDER BY
  jabstrukrTingkat,jabstrukrNama ASC
";
?>
