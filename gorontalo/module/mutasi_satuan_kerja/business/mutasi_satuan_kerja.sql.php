<?php


//===GET===
$sql['get_count_pegawai'] = "
SELECT 
   COUNT(pegId) AS total
FROM 
   pub_pegawai
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
	IF(a.jbtnStatus='Aktif',jabstrukrNama,(select jabstrukrNama from sdm_ref_satuan_kerja where jabstrukrId=(select jbtnJabstrukrId from sdm_satuan_kerja where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabstruk,
	IF(b.jbtnStatus='Aktif',jabfungrNama,(select jabfungrNama from pub_ref_jabatan_fungsional where jabfungrId=(select jbtnJabfungrId from sdm_jabatan_fungsional where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabfung
FROM 
   pub_pegawai
   LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId
   LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
   LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId
   LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
   LEFT JOIN sdm_satuan_kerja a ON a.jbtnPegKode=pegId
   LEFT JOIN sdm_ref_satuan_kerja ON jabstrukrId=a.jbtnJabstrukrId
   LEFT JOIN sdm_jabatan_fungsional b ON b.jbtnPegKode=pegId
   LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=b.jbtnJabfungrId
WHERE
   pegKodeResmi like '%s' %s
   pegNama like '%s'
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

$sql['get_list_mutasi_satuan_kerja']="
SELECT DISTINCT 
	  p.satkerpegId as id,
	  pp.pegKodeResmi as nip,
	  j.satkerId as satkerId,
	  p.satkerpegTMT as tmt,
	  p.satkerpegPjbSk as pejabat,
	  p.satkerpegNoSk as nosk,
	  p.satkerpegTglSk as tgl_sk,
	  p.satkerpegAktif as status,
	  p.satkerpegSkUpload as upload,
    j.satkerNama AS satker
FROM
	sdm_satuan_kerja_pegawai p
	INNER JOIN pub_pegawai pp ON (pp.pegId=p.satkerpegPegId)
	LEFT JOIN pub_satuan_kerja j ON (p.satkerpegSatkerId=j.satkerId)
WHERE 
   pp.pegId='%s'
"; 

$sql['get_data_mutasi_satuan_kerja_by_id']="
SELECT DISTINCT 
	  p.satkerpegId as id,
	  pp.pegKodeResmi as nip,
	  j.satkerId as satkerId,
	  p.satkerpegTMT as tmt,
	  p.satkerpegPjbSk as pejabat,
	  p.satkerpegNoSk as nosk,
	  p.satkerpegTglSk as tgl_sk,
	  p.satkerpegAktif as status,
	  p.satkerpegSkUpload as upload,
    j.satkerNama AS satker
FROM
	sdm_satuan_kerja_pegawai p
	INNER JOIN pub_pegawai pp ON (pp.pegId=p.satkerpegPegId)
	LEFT JOIN pub_satuan_kerja j ON (p.satkerpegSatkerId=j.satkerId)
WHERE 
   pp.pegId='%s' AND
   p.satkerpegId='%s' 
"; 

$sql['get_combo_golongan']="
SELECT
	pktgolrId as id,
	CONCAT(pktgolrId,' ',pktgolrNama) as name
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
	CONCAT(pktgolrId,' ',pktgolrNama) as name
FROM
	sdm_ref_pangkat_golongan 
";

$sql['get_combo_jabstruk']="
SELECT
	jabstrukrId as id,
	jabstrukrNama as name
FROM
	sdm_ref_satuan_kerja
ORDER BY
  jabstrukrTingkat,jabstrukrNama ASC
";

$sql['get_id_struk']="
SELECT 
   jabstrukKompgajidtId
FROM 
   sdm_ref_satuan_kerja
WHERE 
   jabstrukrId = %s
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
   sdm_satuan_kerja
   (jbtnPegKode,jbtnJabstrukrId,jbtnEselon,jbtnPktGolId,jbtnTglMulai,
   jbtnTglSelesai,jbtnSkPjb,jbtnSkNmr,jbtnSkTgl,jbtnStatus,jbtnSkUpload)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_satuan_kerja
SET 
	jbtnPegKode = '%s',
	jbtnJabstrukrId = '%s',
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
   sdm_satuan_kerja
WHERE 
   jbtnId = %s  
";


$sql['update_status']="
update 
	sdm_satuan_kerja
set jbtnStatus = '%s'
where jbtnId != %s AND jbtnPegKode = '%s' 
";

$sql['get_max_status']="
select 
	max(jbtnId) as MAXID,
	jbtnStatus as STAT
FROM sdm_satuan_kerja
WHERE jbtnId=(select max(jbtnId) FROM sdm_satuan_kerja)
group by jbtnId
";

$sql['get_max_id']="
select 
	max(jbtnId) as MAXID
FROM sdm_satuan_kerja
";

$sql['get_id_lain']="
select 
	a.jbtnId as 'id',
	b.jabstrukKompgajidtId as 'komp'
FROM 
  sdm_satuan_kerja a
  LEFT JOIN sdm_ref_satuan_kerja b ON (b.jabstrukrId=a.jbtnJabstrukrId)
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
?>
