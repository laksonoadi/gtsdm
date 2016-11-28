<?php


//===GET===
$sql['get_count_pegawai'] = "
SELECT 
   COUNT(pegId) AS total
FROM 
   pub_pegawai
JOIN sdm_ref_jenis_pegawai ON jnspegrId=pegJnspegrId
WHERE
   pegKodeResmi like '%s' %s
   pegNama like '%s'
   AND (jnspegrTipeKontrak='CONTRACT' OR jnspegrTipeKontrak='PROBATION')
";

$sql['get_count_history_kontrak_pegawai'] = "
SELECT 
   COUNT(kontrakpegId) AS total
FROM 
   pub_pegawai_kontrak
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
	IF(b.jbtnStatus='Aktif',jabfungrNama,(select jabfungrNama from pub_ref_jabatan_fungsional where jabfungrId=(select jbtnJabfungrId from sdm_jabatan_fungsional where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabfung,
	pegTglMasukInstitusi as tgl_masuk,
	pegTglKeluarInstitusi as tgl_keluar,
	kontrakpegTglAwal as tgl_awal,
	kontrakpegTglAkhir as tgl_akhir,
	jnspegrNama as jenis_kontrak
FROM 
   pub_pegawai
   LEFT JOIN pub_pegawai_kontrak ON kontrakpegPegId=pegId
   LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId
   LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
   LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId
   LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
   LEFT JOIN sdm_jabatan_struktural a ON a.jbtnPegKode=pegId
   LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=a.jbtnJabstrukrId
   LEFT JOIN sdm_jabatan_fungsional b ON b.jbtnPegKode=pegId
   LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=b.jbtnJabfungrId
   JOIN sdm_ref_jenis_pegawai ON jnspegrId=pegJnspegrId
WHERE
   pegKodeResmi like '%s' %s
   pegNama like '%s'
   AND (jnspegrTipeKontrak='CONTRACT' OR jnspegrTipeKontrak='PROBATION') 
GROUP BY 
	 pegId
ORDER BY
	 pegKodeResmi ASC, jnspegrNama ASC
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

$sql['get_list_history_kontrak_pegawai']="
SELECT DISTINCT 
	p.`kontrakpegId` as id,            
  p.`kontrakpegPegId` as nip,                    
  p.`kontrakpegTglAwal` as tgl_awal,                
  p.`kontrakpegTglAkhir` tgl_akhir,               
  p.`kontrakpegSkPjb` as pejabat,                  
  p.`kontrakpegSkNmr` as nosk,
  p.`kontrakpegSkTgl` as tgl_sk,                   
  p.`kontrakpegStatus` status,
  p.`kontrakpegTglStatus` tgl_status,   
  p.`kontrakpegUpload` as upload,                  
  p.`kontrakpegUserId` as userId
FROM
	pub_pegawai_kontrak p
WHERE 
  p.kontrakpegPegId='%s'
"; 

$sql['get_data_kontrak_pegawai_by_id']="
SELECT 
	p.`kontrakpegId` as id,            
  p.`kontrakpegPegId` as nip,                    
  p.`kontrakpegTglAwal` as tgl_awal,                
  p.`kontrakpegTglAkhir` tgl_akhir,               
  p.`kontrakpegSkPjb` as pejabat,                  
  p.`kontrakpegSkNmr` as nosk,
  p.`kontrakpegSkTgl` as tgl_sk,                   
  p.`kontrakpegStatus` status,
  p.`kontrakpegTglStatus` tgl_status,   
  p.`kontrakpegUpload` as upload,                  
  p.`kontrakpegUserId` as userId
FROM
	pub_pegawai_kontrak p
WHERE 
   p.kontrakpegPegId='%s' AND
   p.kontrakpegId='%s' 
"; 

$sql['get_tanggal_awal_kontrak_pegawai_by_id']="
SELECT                     
  p.`kontrakpegTglAwal` as tgl_awal
FROM
	pub_pegawai_kontrak p
WHERE 
   p.kontrakpegPegId='%s'
GROUP BY `kontrakpegTglAwal` ASC
LIMIT 0,1 
"; 

$sql['get_tanggal_akhir_kontrak_pegawai_by_id']="
SELECT                     
  p.`kontrakpegTglAkhir` as tgl_awal
FROM
	pub_pegawai_kontrak p
WHERE 
   p.kontrakpegPegId='%s'
GROUP BY `kontrakpegTglAkhir` DESC
LIMIT 0,1 
"; 


/*
`kontrakpegId` bigint(20) NOT NULL auto_increment,            
`kontrakpegPegId` bigint(20) default NULL,                    
`kontrakpegTglAwal` date default '0000-00-00',                
`kontrakpegTglAkhir` date default '0000-00-00',               
`kontrakpegSkPjb` varchar(100) default NULL,                  
`kontrakpegSkNmr` varchar(10) default NULL,
`kontrakpegSkTgl` date default '0000-00-00',                  
`kontrakpegStatus` enum('aktif','tidak aktif') default NULL,  
`kontrakpegTglStatus` date default '0000-00-00', 
`kontrakpegUpload` varchar(50) default NULL,                  
`kontrakpegUserId` bigint(20) default NULL,
*/

// DO-----------
$sql['do_add'] = "
INSERT INTO 
  pub_pegawai_kontrak
(kontrakpegPegId,kontrakpegTglAwal,kontrakpegTglAkhir,
kontrakpegSkPjb,kontrakpegSkNmr,kontrakpegSkTgl,
kontrakpegStatus,kontrakpegTglStatus,kontrakpegUpload,
kontrakpegUserId)
VALUES
('%s','%s','%s',
'%s','%s','%s',
'%s','%s','%s',
'%s')  
";

$sql['do_update'] = "
UPDATE 
  pub_pegawai_kontrak
SET 
	kontrakpegPegId = '%s',
	kontrakpegTglAwal = '%s',
	kontrakpegTglAkhir = '%s',
	kontrakpegSkPjb = '%s',
	kontrakpegSkNmr = '%s',
	kontrakpegSkTgl = '%s',
	kontrakpegStatus = '%s',
	kontrakpegTglStatus = '%s',
	kontrakpegUpload = '%s',
	kontrakpegUserId = '%s'
WHERE 
	kontrakpegId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   pub_pegawai_kontrak
WHERE 
   kontrakpegId = %s  
";


$sql['update_status']="
update 
	pub_pegawai_kontrak
set kontrakpegStatus = '%s'
where kontrakpegId != %s AND kontrakpegPegId = '%s' 
";

$sql['do_update_tgl_keluar_institusi'] = "
UPDATE
  pub_pegawai
SET
  pegTglKeluarInstitusi = '%s'
WHERE
  pegId=%s
";

$sql['do_update_tgl_masuk_institusi'] = "
UPDATE
  pub_pegawai
SET
  pegTglMasukInstitusi = '%s'
WHERE
  pegId=%s
";

$sql['get_max_status']="
select 
	max(kontrakpegId) as MAXID,
	kontrakpegStatus as STAT
FROM pub_pegawai_kontrak
WHERE kontrakpegId=(select max(kontrakpegId) FROM pub_pegawai_kontrak)
group by kontrakpegId
";

$sql['get_max_id']="
select 
	max(jbtnId) as MAXID
FROM pub_pegawai_kontrak
";

?>
