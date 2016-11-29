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
	substring(pegTglMasukInstitusi,1,4) as masuk
FROM
	pub_pegawai
WHERE pegId='%s' 
"; 

$sql['get_list_mutasi_pangkat_golongan']="
SELECT DISTINCT 
	p.pktgolId as id,
	p.pktgolPegKode as nip,
	p.pktgolPktgolrId as golongan,
	p.pktgolTmt as tmt,
	p.pktgolPejabatSk as pejabat,
	p.pktgolNoSk as nosk,
	p.pktgolTglSk as tgl_sk,
	p.pktgolDsrPeraturan as dasar,
	p.pktgolStatus as status,
	p.pktgolSkUpload as upload,
	CONCAT(pg.pktgolrId,'-',pg.pktgolrNama) AS pktgol
FROM
	sdm_pangkat_golongan p
	LEFT JOIN sdm_ref_pangkat_golongan pg ON (p.pktgolPktgolrId=pg.pktgolrId)
WHERE 
   p.pktgolPegKode='%s'
"; 

$sql['get_data_mutasi_pangkat_golongan_by_id']="
SELECT 
	p.pktgolId as id,
	p.pktgolPegKode as nip,
	p.pktgolPktgolrId as golongan,
	p.pktgolJnspegrId as jenisid,
	p.pktgolTmt as tmt,
	p.pktgolNaikPktYad as tgl_naik,
	p.pktgolPejabatSk as pejabat,
	p.pktgolNoSk as nosk,
	p.pktgolTglSk as tgl_sk,
	p.pktgolDsrPeraturan as dasar,
	p.pktgolStatus as status,
	p.pktgolSkUpload as upload,
	CONCAT(pg.pktgolrId,'-',pg.pktgolrNama) AS pktgol,
	j.jnspegrNama as jpnama
FROM
	sdm_pangkat_golongan p
	LEFT JOIN sdm_ref_pangkat_golongan pg ON (p.pktgolPktgolrId=pg.pktgolrId)
	LEFT JOIN sdm_ref_jenis_pegawai j ON (p.pktgolJnspegrId=j.jnspegrId)
WHERE 
   p.pktgolPegKode='%s' AND
   p.pktgolId='%s' 
"; 

$sql['get_combo_golongan']="
SELECT
	pktgolrId as id,
	CONCAT(pktgolrId,' ',pktgolrNama) as name
FROM
	sdm_ref_pangkat_golongan
ORDER BY
  pktgolrUrut ASC
";

$sql['get_combo_jenis_pegawai']="
SELECT
	jnspegrId as id,
	jnspegrNama as name
FROM
	sdm_ref_jenis_pegawai
ORDER BY
  jnspegrUrut ASC
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_pangkat_golongan
   (pktgolPegKode,pktgolJnspegrId,pktgolPktgolrId,pktgolTmt,pktgolNaikPktYad,
   pktgolPejabatSk,pktgolNoSk,pktgolTglSk,pktgolDsrPeraturan,pktgolStatus,pktgolSkUpload)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')   
";

$sql['do_update'] = "
UPDATE sdm_pangkat_golongan
SET 
	pktgolPegKode = '%s',
	pktgolJnspegrId = '%s',
	pktgolPktgolrId = '%s',
	pktgolTmt = '%s',
	pktgolNaikPktYad = '%s',
	pktgolPejabatSk = '%s',
	pktgolNoSk = '%s',
	pktgolTglSk = '%s',
	pktgolDsrPeraturan = '%s',
	pktgolStatus = '%s',
	pktgolSkUpload = '%s'
WHERE 
	pktgolId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_pangkat_golongan
WHERE 
   pktgolId = %s  
";


$sql['update_status']="
update 
	sdm_pangkat_golongan
set pktgolStatus = '%s'
where pktgolId != %s AND pktgolPegKode = '%s'
";

$sql['get_max_status']="
select 
	max(pktgolId) as MAXID,
	pktgolStatus as STAT
FROM sdm_pangkat_golongan
WHERE pktgolId=(select max(pktgolId) FROM sdm_pangkat_golongan)
group by pktgolId
";

$sql['get_max_id']="
select 
	max(pktgolId) as MAXID
FROM sdm_pangkat_golongan
";
?>
