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
	a.pegId AS id,
	a.pegNama AS NAME,
	a.pegKodeResmi AS kode,
	a.pegAlamat AS alamat,
	a.pegNoTelp AS telp,
	a.pegSatwilId AS wil,
	a.pegFoto AS foto,
	SUBSTRING(pegTglMasukInstitusi,1,4) AS masuk,
	b.pktgolPktgolrId AS pgkode,
	c.pktgolrNama AS pgnama,
	c.pktgolrUrut AS pgtgkt
	
FROM
	pub_pegawai a
	JOIN sdm_pangkat_golongan b ON a.pegId=b.pktgolPegKode AND b.pktgolStatus = 'Aktif'
	JOIN sdm_ref_pangkat_golongan c ON c.pktgolrId = b.pktgolPktgolrId
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
	pg.satkerNama AS satkernama,
	jk.nama_ref_jns_peg AS `jnspeg`,
	p.satkerpegTugas as tugas
FROM
	sdm_satuan_kerja_pegawai p
	LEFT JOIN pub_satuan_kerja pg ON (p.satkerpegSatkerId=pg.satkerId)
	LEFT JOIN sdm_ref_jenis_kepegawaian jk ON jk.id_ref_jns_peg = p.satkerpegJenPegId 
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
	p.satkerpegSatkerJabId as ref_jab,
	p.satkerpegPjbSk as pejabat,
	p.satkerpegNoSk as nosk,
	p.satkerpegTglSk as tgl_sk,
	p.satkerpegAktif as status,
	p.satkerpegSkUpload as upload,
	pg.satkerNama AS satkernama,
	p.satkerpegTugas as tugas
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
   (satkerpegPegId,satkerpegSatkerId,satkerpegSatkerJabId,satkerpegJenPegId,satkerpegTmt,
   satkerpegPjbSk,satkerpegNoSk,satkerpegTglSk,
   satkerpegAktif,satkerpegSkUpload,satkerpegTugas)
VALUES('%s','%s','%s',
	   '%s','%s','%s','%s',
       '%s','%s','%s','%s')   
";

$sql['do_update'] = "
UPDATE sdm_satuan_kerja_pegawai
SET 
	satkerpegPegId = '%s',
	satkerpegSatkerId = '%s',
	satkerpegSatkerJabId = '%s',
	satkerpegJenPegId = '%s',
	satkerpegTmt = '%s',
	satkerpegPjbSk = '%s',
	satkerpegNoSk = '%s',
	satkerpegTglSk = '%s',
	satkerpegAktif = '%s',
	satkerpegSkUpload = '%s',
	satkerpegTugas = '%s'
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
	a.jabstrukrId AS id,
	a.jabstrukrNama AS `name`,
	a.jabstrukrSatker AS `satkerid`
FROM
	sdm_ref_jabatan_struktural a

ORDER BY
  a.jabstrukrTingkat,a.jabstrukrNama ASC
";

$sql['get_jab_by_satker']="
SELECT
	a.jabstrukrId AS id,
	a.jabstrukrNama AS `name`,
	a.jabstrukrSatker AS `satkerid`
FROM
	sdm_ref_jabatan_struktural a
WHERE 
a.jabstrukrSatker = '%s'
	

ORDER BY
  a.jabstrukrTingkat,a.jabstrukrNama ASC
";

$sql['get_kepala_satker']="
SELECT
/*a.pegNama,
b.pktgolPktgolrId,
e.jabstrukrTingkat,
e.jabstrukrSatker*/
a.pegId AS `id`,
c.pktgolrUrut  AS `level`
FROM pub_pegawai a
JOIN sdm_pangkat_golongan b ON a.pegId = b.pktgolPegKode
JOIN sdm_ref_pangkat_golongan c ON c.pktgolrId = b.pktgolPktgolrId
JOIN sdm_jabatan_struktural d ON d.jbtnPegKode = a.pegId
JOIN sdm_ref_jabatan_struktural e ON e.jabstrukrId = d.jbtnJabstrukrId
WHERE e.jabstrukrSatker = '%s' AND b.`pktgolStatus` = 'Aktif' AND e.jabstrukrTingkat = 1
GROUP BY e.jabstrukrId
";
?>
