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
	pegKodeResmi as nip,
	IF(pktgolStatus='Aktif',CONCAT(pktgolPktgolrId,' - ',pktgolrNama),(select CONCAT(pktgolrId,' - ',pktgolrNama) from sdm_ref_pangkat_golongan where pktgolrId=(select pktgolPktgolrId from sdm_pangkat_golongan where pktgolPegKode=pegId and pktgolStatus='Aktif'))) as pktgol,
	IF(satkerpegAktif='Aktif',satkerNama,(select satkerNama from pub_satuan_kerja where satkerId=(select satkerpegSatkerId from sdm_satuan_kerja_pegawai where satkerpegPegId=pegId and satkerpegAktif='Aktif'))) as satker,
	IF(a.jbtnJabstrukrId<>0, IF(a.jbtnStatus='Aktif',jabstrukrNama,(select jabstrukrNama from sdm_ref_jabatan_struktural where jabstrukrId=(select jbtnJabstrukrId from sdm_jabatan_struktural where jbtnPegKode=pegId and jbtnStatus='Aktif'))), a.jbtnOldJab) as jabstruk,
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
	substring(pegTglMasukInstitusi,1,4) as masuk,
	pegdtKategori as kategori
FROM
	pub_pegawai
	LEFT JOIN sdm_pegawai_detail ON pegdtPegId = pegId
WHERE pegId='%s' 
"; 

$sql['get_list_mutasi_jabatan_struktural']="
SELECT 
	p.jbtnId as id,
	p.jbtnPegKode as nip,
	p.jbtnJabstrukrId as struktural,
	p.jbtnOldJab as old_jab,
	p.jbtnGajiJab as gaji_jab,
	re.idEselon as eselon,
	re.nameEselon as eselon_name,
	p.jbtnTglMulai as mulai,
	p.jbtnTglSelesai as selesai,
	p.jbtnSkPjb as pejabat,
	p.jbtnSkNmr as nosk,
	p.jbtnSkTgl as tgl_sk,
	p.jbtnStatus as status,
	p.jbtnSkUpload as upload,
    j.jabstrukrNama AS jabstruk,
    IF(p.jbtnJabstrukrId <> 0, j.jabstrukrNama, p.jbtnOldJab) AS real_jab,
    j.jabstrukrAttachAnjab AS jabstruk_template,
    CONCAT(pg.pktgolrId,' - ',pg.pktgolrNama) AS pktgollabel
FROM
	sdm_jabatan_struktural p
	LEFT JOIN sdm_ref_jabatan_struktural j ON (p.jbtnJabstrukrId=j.jabstrukrId)
	LEFT JOIN sdm_ref_pangkat_golongan pg ON (p.jbtnPktGolId=pg.pktgolrId)
	LEFT JOIN sdm_ref_eselon re ON (p.jbtnEselon=re.idEselon)
WHERE 
    p.jbtnPegKode='%s'
ORDER BY p.jbtnTglMulai DESC, p.jbtnId DESC
"; 

$sql['get_data_mutasi_jabatan_struktural_by_id']="
SELECT 
	p.jbtnId as id,
	p.jbtnPegKode as nip,
	p.jbtnJabstrukrId as struktural,
	p.jbtnOldJab as old_jab,
	p.jbtnGajiJab as gaji_jab,
	re.idEselon as eselon,
	re.nameEselon as eselon_name,
	p.jbtnPktGolId as pktgolid,
	p.jbtnTglMulai as mulai,
	p.jbtnTglSelesai as selesai,
	p.jbtnSkPjb as pejabat,
	p.jbtnSkNmr as nosk,
	p.jbtnSkTgl as tgl_sk,
	p.jbtnStatus as status,
	p.jbtnSkUpload as upload,
	j.jabstrukrNama as jsnama,
	j.jabstrukrAttachAnjab AS js_template,
	CONCAT(pg.pktgolrId,' - ',pg.pktgolrNama) AS pktgollabel
FROM
	sdm_jabatan_struktural p
	LEFT JOIN sdm_ref_jabatan_struktural j ON (p.jbtnJabstrukrId=j.jabstrukrId)
	LEFT JOIN sdm_ref_pangkat_golongan pg ON (p.jbtnPktGolId=pg.pktgolrId)
	LEFT JOIN sdm_ref_eselon re ON (p.jbtnEselon=re.idEselon)
WHERE 
   p.jbtnPegKode='%s' AND
   p.jbtnId='%s' 
"; 

$sql['get_data_pegawai_by_mutasi_id']="
SELECT 
    pegId as id,
	pegNama as nama,
	pegKodeResmi as nip,
    jabstrukrNama as jabstruk,
    jabstrukrAttachAnjab as jabstruk_template,
    satkerNama as satker
FROM 
   pub_pegawai
   LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
   LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
   LEFT JOIN sdm_jabatan_struktural a ON a.jbtnPegKode=pegId AND a.jbtnStatus='Aktif'
   LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=a.jbtnJabstrukrId
WHERE
   a.jbtnId = '%s' AND a.jbtnPegKode = '%s'
GROUP BY 
	 pegId
ORDER BY
	 pegKodeResmi
"; 

$sql['get_combo_golongan']="
SELECT
	pktgolrId as id,
	CONCAT(pktgolrId,' - ',pktgolrNama) as name
FROM
	sdm_ref_pangkat_golongan p,
	sdm_pangkat_golongan pg
WHERE 
   p.pktgolrId=pg.pktgolPktgolrId AND 
   pg.pktgolPegKode='%s' AND
   pg.pktgolStatus='Aktif'

";

$sql['get_combo_golongan_all']="
SELECT
	pktgolrId as id,
	CONCAT(pktgolrId,' - ',pktgolrNama) as name
FROM
	sdm_ref_pangkat_golongan 
";

$sql['get_combo_jabstruk']="
SELECT
	jabstrukrId as id,
	CONCAT(jabstrukrNama,' - ',satkerNama) AS name
FROM
	sdm_ref_jabatan_struktural
	LEFT JOIN pub_satuan_kerja on satkerId=jabstrukrSatker
WHERE jabstrukrId NOT IN (SELECT b.jbtnJabstrukrId FROM sdm_jabatan_struktural b WHERE b.jbtnStatus = 'Aktif') OR jabstrukrId = '%s'
ORDER BY
  satkerId ASC
";

$sql['get_combo_jabstruk_by_unit']="
SELECT
	jabstrukrId AS id,
	jabstrukrNama AS name
FROM
	sdm_ref_jabatan_struktural
	LEFT JOIN pub_satuan_kerja ON satkerId=jabstrukrSatker
WHERE jabstrukrSatker = '%s' 
ORDER BY
  satkerId ASC
";

$sql['get_id_struk']="
SELECT 
   jabstrukKompgajidtId
FROM 
   sdm_ref_jabatan_struktural
WHERE 
   jabstrukrId = %s
";


$sql['get_unit_kerja_pegawai_aktif']="
SELECT satkerpegSatkerId AS id
FROM `sdm_satuan_kerja_pegawai` WHERE `satkerpegPegId` = '%s' AND satkerpegAktif = 'Aktif'
";

/*
jbtnId 
jbtnPegKode 
jbtnJabstrukrId
jbtnEselon
jbtnPktGolId
jbtnTglMulai
jbtnTglSelesai
jbtnSkPjb
jbtnSkNmr
jbtnSkTgl date
jbtnStatus
jbtnSkUpload
*/

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_jabatan_struktural
   (jbtnPegKode,jbtnJabstrukrId,jbtnOldJab,jbtnGajiJab,jbtnEselon,jbtnPktGolId,jbtnTglMulai,
   jbtnTglSelesai,jbtnSkPjb,jbtnSkNmr,jbtnSkTgl,jbtnStatus,jbtnSkUpload)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_jabatan_struktural
SET 
	jbtnPegKode = '%s',
	jbtnJabstrukrId = '%s',
	jbtnOldJab = '%s',
	jbtnGajiJab = '%s',
	jbtnEselon = '%s',
	jbtnPktGolId = '%s',
	jbtnTglMulai = '%s',
	jbtnTglSelesai = '%s',
	jbtnSkPjb = '%s',
	jbtnSkNmr = '%s',
	jbtnSkTgl = '%s',
	jbtnStatus = '%s',
	jbtnSkUpload = '%s'
WHERE 
	jbtnId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_jabatan_struktural
WHERE 
   jbtnId = %s  
";


$sql['update_status']="
update 
	sdm_jabatan_struktural
set jbtnStatus = '%s'
where jbtnId != %s AND jbtnPegKode = '%s' 
";

$sql['get_max_status']="
select 
	max(jbtnId) as MAXID,
	jbtnStatus as STAT
FROM sdm_jabatan_struktural
WHERE jbtnId=(select max(jbtnId) FROM sdm_jabatan_struktural)
group by jbtnId
";

$sql['get_max_id']="
select 
	max(jbtnId) as MAXID
FROM sdm_jabatan_struktural
";

$sql['get_id_lain']="
select 
	a.jbtnId as 'id',
	b.jabstrukKompgajidtId as 'komp'
FROM 
  sdm_jabatan_struktural a
  LEFT JOIN sdm_ref_jabatan_struktural b ON (b.jabstrukrId=a.jbtnJabstrukrId)
where jbtnId != %s AND jbtnPegKode = '%s'
";

$sql['do_add_komp_mutasi'] = "
INSERT INTO 
   sdm_komponen_gaji_pegawai_detail
   (kompgajipegdtPegId,kompgajipegdtKompgajidtrId,kompgajipegdtTanggal)
VALUES('%s','%s','%s')
";

$sql['do_update_komp_mutasi'] = "
UPDATE sdm_komponen_gaji_pegawai_detail
SET 
	kompgajipegdtKompgajidtrId = '%s',
	kompgajipegdtTanggal = '%s'
WHERE 
	kompgajipegdtPegId = %s and kompgajipegdtKompgajidtrId = %s
";

$sql['do_delete_komp_mutasi'] = "
DELETE FROM
   sdm_komponen_gaji_pegawai_detail
WHERE 
   kompgajipegdtPegId = %s and kompgajipegdtKompgajidtrId = %s  
";

$sql['pegawai_is_non_job'] = "
	SELECT 
	COUNT(a.jbtnPegKode) AS `found`
	FROM sdm_jabatan_struktural a
	WHERE a.jbtnPegKode ='%s' AND a.jbtnStatus = 'Aktif'
";

$sql['ref_combo_eselon'] = "
SELECT 
a.idEselon AS `id`,
a.nameEselon AS `name`
FROM sdm_ref_eselon a
";

$sql['get_last_mutasi_jabstruk_pegawai']="
SELECT
a.`jbtnId` AS `id`,
a.`jbtnPegKode` AS `pegId`,
IF(a.`jbtnJabstrukrId` = 0, a.`jbtnOldJab`, b.`jabstrukrNama`) AS `jabStrukName`
FROM sdm_jabatan_struktural a
LEFT JOIN sdm_ref_jabatan_struktural b ON a.`jbtnJabstrukrId` = b.`jabstrukrId`
WHERE 
a.jbtnPegKode = '%s'
ORDER BY a.jbtnTglMulai DESC, a.jbtnId DESC
";


?>
