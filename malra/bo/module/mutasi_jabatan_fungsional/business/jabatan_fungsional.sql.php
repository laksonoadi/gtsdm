<?php


//===GET===
$sql['get_count_pegawai'] = "
SELECT 
   COUNT(pegId) AS total
FROM 
   pub_pegawai
   LEFT JOIN sdm_pegawai_detail ON pegdtPegId = pegId
WHERE
	pegId IS NOT NULL
	AND pegKodeResmi like '%s' OR pegNama like '%s'
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
   IF(pktgolStatus='Aktif',CONCAT(pktgolPktgolrId,' - ',pktgolrNama),(select CONCAT(pktgolrId,' - ',pktgolrNama) from sdm_ref_pangkat_golongan where pktgolrId=(select pktgolPktgolrId from sdm_pangkat_golongan where pktgolPegKode=pegId and pktgolStatus='Aktif'))) as pktgol,
	IF(satkerpegAktif='Aktif',satkerNama,(select satkerNama from pub_satuan_kerja where satkerId=(select satkerpegSatkerId from sdm_satuan_kerja_pegawai where satkerpegPegId=pegId and satkerpegAktif='Aktif'))) as satker,
	IF(a.jbtnStatus='Aktif',jabstrukrNama,(select jabstrukrNama from sdm_ref_jabatan_struktural where jabstrukrId=(select jbtnJabstrukrId from sdm_jabatan_struktural where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabstruk,
	IF(b.jbtnStatus='Aktif',jabfungrNama,(select jabfungrNama from pub_ref_jabatan_fungsional where jabfungrId=(select jbtnJabfungrId from sdm_jabatan_fungsional where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabfung
FROM
	pub_pegawai
	LEFT JOIN sdm_pegawai_detail ON pegdtPegId = pegId
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId
   LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
   LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId
   LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
   LEFT JOIN sdm_jabatan_struktural a ON a.jbtnPegKode=pegId
   LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=a.jbtnJabstrukrId
   LEFT JOIN sdm_jabatan_fungsional b ON b.jbtnPegKode=pegId
   LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=b.jbtnJabfungrId
WHERE
	pegId IS NOT NULL
	AND pegKodeResmi like '%s' OR pegNama like '%s'
GROUP BY 
	 pegId
ORDER BY
	 pegKodeResmi ASC 
LIMIT %s,%s
"; 

$sql['get_data_pegawai_detail']="
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
	LEFT JOIN sdm_pegawai_detail ON pegdtPegId = pegId
WHERE
	pegId IS NOT NULL
"; 


$sql['get_jabatan_detail']="
SELECT DISTINCT
	a.jbtnId as id,
	a.jbtnPegKode as kode,
	a.jbtnJnsJabfungid as ref_jenis_jab,
	a.jbtnJabfungrId as ref_jab,
	a.jbtnPktGolId as gol,
	a.jbtnAhli as keahlian,
	a.jbtnKompGajiDetSksId as skskode,
	 d.kompgajidtNama as sksnama,
	a.jbtnMaxSks as sksmaks,
	a.jbtnAngkaKredit as ak,
	a.jbtnTglMulai as mulai,
	a.jbtnTglSelesai as selesai,
	a.jbtnOldFjab as old_fjab,
	a.jbtnSkPjb as sk,
	a.jbtnSkNmr as sk_no,
	a.jbtnSkTgl as sk_tgl,
	a.jbtnStatus as status,
	a.jbtnSkUpload as upload,
	b.jabfungrNama as ref_nama,
	c.pktgolrNama as pkt_nama,
	IF(a.jbtnJabfungrId <> 0, b.jabfungrNama, a.jbtnOldFjab) as real_jab
FROM
	sdm_jabatan_fungsional a
LEFT JOIN pub_ref_jabatan_fungsional b ON a.jbtnJabfungrId = b.jabfungrId
LEFT JOIN sdm_ref_pangkat_golongan c ON a.jbtnPktGolId = c.pktgolrId
LEFT JOIN sdm_ref_komponen_gaji_detail d ON a.jbtnKompGajiDetSksId = d.kompgajidtId
WHERE
	a.jbtnId IS NOT NULL
";

$sql['get_count_jabatan'] = "
SELECT 
   COUNT(jbtnId) AS TOTAL
FROM 
   sdm_jabatan_fungsional
WHERE
	jbtnId IS NOT NULL
";  

$sql['get_ref_golongan']="
SELECT
	pktgolrId as id,
	CONCAT(pktgolrId,' - ',pktgolrNama) as name,
	pktgolrTingkat as tingkat,
	pktgolrMasaKerja as kerja
FROM
	sdm_ref_pangkat_golongan
ORDER BY
   pktgolrUrut ASC
";

$sql['get_combo_golongan']="
SELECT
	pktgolrId as id,
	CONCAT(pktgolrId,' - ',pktgolrNama) as name
FROM
	sdm_ref_pangkat_golongan
ORDER BY
  pktgolrUrut ASC
  
/* SELECT
	pktgolrId as id,
	CONCAT(pktgolrId,' - ',pktgolrNama) as name
FROM
	sdm_ref_pangkat_golongan p,
	sdm_pangkat_golongan pg
WHERE 
   p.pktgolrId=pg.pktgolPktgolrId AND 
   pg.pktgolPegKode='%s' AND
   pg.pktgolStatus='Aktif'
*/
";

$sql['get_ref_jabatan']="
SELECT
	jabfungrId as id,
	jabfungrNama as name,
	jabfungrJenisrId as jenis,
	jabfungrTingkat as tingkat
FROM
	pub_ref_jabatan_fungsional
ORDER BY
   jabfungrTingkat ASC
";

$sql['get_id_fung']="
SELECT 
   jabfungrKompGajiDetId as 'komp1'
FROM 
   pub_ref_jabatan_fungsional
WHERE 
   jabfungrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_jabatan_fungsional
   (jbtnPegKode,jbtnJnsJabfungid,jbtnJabfungrId,jbtnPktGolId,jbtnTglMulai,
   jbtnTglSelesai,jbtnOldFjab,jbtnSkPjb,jbtnSkNmr,jbtnSkTgl,jbtnStatus,jbtnSkUpload,
   jbtnKompGajiDetSksId,jbtnMaxSks,jbtnAngkaKredit,jbtnAhli)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
       '%s','%s','%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_jabatan_fungsional
SET 
	jbtnPegKode = '%s',
	jbtnJnsJabfungid = '%s',
	jbtnJabfungrId = '%s',
	jbtnPktGolId = '%s',
	jbtnTglMulai = '%s',
	jbtnTglSelesai = '%s',
	jbtnOldFjab = '%s',
	jbtnSkPjb = '%s',
	jbtnSkNmr = '%s',
	jbtnSkTgl = '%s',
	jbtnStatus = '%s',
	jbtnSkUpload = '%s',
	jbtnKompGajiDetSksId = '%s',
  jbtnMaxSks = '%s',
  jbtnAngkaKredit = '%s',
  jbtnAhli = '%s'
WHERE 
	jbtnId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_jabatan_fungsional
WHERE 
   jbtnId = %s  
";

$sql['update_status']="
update 
	sdm_jabatan_fungsional
set jbtnStatus = '%s'
where jbtnId != %s AND jbtnPegKode=%s
";

$sql['get_max_status']="
select 
	max(jbtnId) as MAXID,
	jbtnStatus as STAT
FROM sdm_jabatan_fungsional
WHERE jbtnId=(select max(jbtnId) FROM sdm_jabatan_fungsional)
group by jbtnId
";

$sql['get_max_id']="
select 
	max(jbtnId) as MAXID
FROM sdm_jabatan_fungsional
";

$sql['get_id_lain']="
select 
	a.jbtnId as 'id',
	b.jabfungrKompGajiDetId as 'komp1',
	a.jbtnKompGajiDetSksId as 'komp2'
FROM 
  sdm_jabatan_fungsional a
  LEFT JOIN pub_ref_jabatan_fungsional b ON (b.jabfungrId=a.jbtnJabfungrId)
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


$sql['get_list_fungsional_by_id'] = "
SELECT   a.jabfungrId AS 'id',
   a.jabfungrNama AS 'name'
   FROM `pub_ref_jabatan_fungsional` a
   LEFT JOIN `pub_ref_jabatan_fungsional_jenis` AS b ON a.`jabfungrJenisrId`=b.`jabfungjenisrId`
   WHERE b.`jabfungjenisrId` = '%s'
";

$sql['get_last_mutasi_jabfung_pegawai']="
SELECT
a.`jbtnId` AS `id`,
a.`jbtnPegKode` AS `pegKode`,
IF(a.`jbtnJabfungrId` = 0, a.`jbtnOldFJab`, b.`jabfungrNama`) AS `jabFungName`
FROM sdm_jabatan_fungsional a
LEFT JOIN pub_ref_jabatan_fungsional b ON a.`jbtnJabfungrId` = b.`jabfungrId`
WHERE 
a.jbtnPegKode = '%s'
ORDER BY a.jbtnTglMulai DESC, a.jbtnId DESC
";


?>
