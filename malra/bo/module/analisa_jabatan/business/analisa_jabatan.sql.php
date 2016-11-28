<?php

$sql['count_all_jabatan_pegawai']="
SELECT
COUNT(a.jabstrukrId) as total
FROM
sdm_ref_jabatan_struktural a
LEFT JOIN sdm_jabatan_struktural b ON a.jabstrukrId = b.jbtnJabstrukrId
LEFT JOIN pub_pegawai c ON c.pegId = b.jbtnPegKode
LEFT JOIN sdm_satuan_kerja_pegawai d ON c.pegId = d.satkerpegPegId
LEFT JOIN pub_satuan_kerja e ON d.satkerpegSatkerId = e.satkerId
WHERE --jenjab--
1=1 
--search--
--group--
";

$sql['get_all_jabatan_pegawai']="
SELECT
a.jabstrukrId AS `id`, 
a.jabstrukrNama AS `jabatan`,
a.jabstrukrBatasUsiaPensiun AS `batas_pensiun`,
b.jbtnPegKode AS `id_pegawai`,
b.jbtnId AS `dataId`,
c.pegNama AS `nama_pegawai`,
c.pegKodeResmi AS `nip`,
b.jbtnSkNmr AS `nosk`,
b.jbtnTglMulai AS `mulai`,
b.jbtnTglSelesai AS `sampai`,
e.satkerNama AS `satuan_kerja`,
b.jbtnStatus AS `status`
FROM
sdm_ref_jabatan_struktural a
LEFT JOIN sdm_jabatan_struktural b ON a.jabstrukrId = b.jbtnJabstrukrId
LEFT JOIN pub_pegawai c ON c.pegId = b.jbtnPegKode
LEFT JOIN sdm_satuan_kerja_pegawai d ON c.pegId = d.satkerpegPegId
LEFT JOIN pub_satuan_kerja e ON d.satkerpegSatkerId = e.satkerId
WHERE --jenjab--

1=1
--search--
--group--
ORDER BY a.jabstrukrId DESC
--limit--	
";

$sql['get_count_empty_jabatan']="
SELECT
COUNT(a.jabstrukrId) AS `total` 
FROM
sdm_ref_jabatan_struktural a
LEFT JOIN pub_satuan_kerja b ON a.jabstrukrSatker = b.satkerId 
WHERE 
1=1
 AND a.jabstrukrId NOT IN ( SELECT b.jbtnJabstrukrId FROM sdm_jabatan_struktural b WHERE b.jbtnStatus = 'Aktif')
--search--
--group--

";

$sql['get_empty_jabatan']="
SELECT
a.jabstrukrId AS `id`, 
a.jabstrukrNama AS `jabatan`,
a.jabstrukrBatasUsiaPensiun AS `batas_pensiun`
FROM
sdm_ref_jabatan_struktural a
LEFT JOIN pub_satuan_kerja b ON a.jabstrukrSatker = b.satkerId 
WHERE 
1=1
 AND a.jabstrukrId NOT IN ( SELECT b.jbtnJabstrukrId FROM sdm_jabatan_struktural b WHERE b.jbtnStatus = 'Aktif')
--search--
GROUP BY a.jabstrukrId
ORDER BY a.jabstrukrId DESC
LIMIT 0,50  
";

$sql['get_satker_and_level']="
SELECT 
a.satkerId,
a.satkerLevel
FROM 
pub_satuan_kerja AS a
INNER JOIN gtfw_user_satuan_kerja ON 
userunitSatuanKerjaId=satkerId 
WHERE userunitUserId='%s' 
";

$sql['get_combo_jabstruk_empty']="
SELECT
	jabstrukrId AS `id`,
	CONCAT(jabstrukrNama,' - ',satkerNama) AS name
FROM
	sdm_ref_jabatan_struktural a
   LEFT JOIN pub_satuan_kerja on satkerId=jabstrukrSatker
WHERE a.jabstrukrId NOT IN (SELECT b.jbtnJabstrukrId FROM sdm_jabatan_struktural b WHERE b.jbtnStatus = 'Aktif') OR a.jabstrukrId = '%s'
ORDER BY
  jabstrukrTingkat,jabstrukrNama ASC
";


$sql['get_list_satker']="


SELECT * FROM (SELECT
   satkerId,
   satkerLevel,
   satkerUnitId,
   satkerNama
FROM 
   pub_satuan_kerja a
   UNION
SELECT
   CONCAT('100',c.jabstrukrId) AS satkerId,
   CONCAT(d.satkerLevel,'.',c.jabstrukrId) AS satkerLevel,
   c.jabstrukTpstrId AS satkerUnitId,
   c.jabstrukrNama AS satkerNama
FROM 
sdm_ref_jabatan_struktural c
   LEFT JOIN `pub_satuan_kerja` AS d ON c.`jabstrukrSatker`=d.satkerId
   WHERE satkerLevel IS NOT NULL
   UNION 
   SELECT
   CONCAT('100',sk.satkerId) AS `satkerId`,
   CONCAT(sk.satkerLevel,'.',a.jabstrukrId,'.',CONCAT('100',a.jabstrukrId)) AS satkerLevel,
   sk.satkerUnitId AS satkerUnitId,
   CONCAT('Nama : ',c.pegNama,' NIP : ',c.pegKodeResmi)
   FROM
      sdm_ref_jabatan_struktural a
   LEFT JOIN sdm_jabatan_struktural b ON b.`jbtnJabstrukrId` = a.`jabstrukrId`
   LEFT JOIN `pub_pegawai` c ON b.`jbtnPegKode` = c.`pegId`
   LEFT JOIN `pub_satuan_kerja` sk ON a.`jabstrukrSatker`=sk.satkerId
   WHERE satkerLevel IS NOT NULL AND b.`jbtnStatus` = 'Aktif'
   
   ) AS temp
";

$sql['get_satker_detail']="
SELECT
   a.satkerId,
   a.satkerLevel,
   a.satkerUnitId,
   b.UnitName,
   a.satkerNama,
   a.satkerParentId,
   a.satkerStruktural
FROM 
   pub_satuan_kerja a
LEFT JOIN gtfw_unit b ON a.satkerUnitId = b.UnitId
WHERE
   a.satkerId = '%s'
";


$sql['get_parent_level']="
SELECT * FROM (SELECT
   satkerId,
   satkerLevel,
   satkerUnitId,
   satkerNama
FROM 
   pub_satuan_kerja a
   UNION
SELECT
   CONCAT('100',c.jabstrukrId) AS satkerId,
   CONCAT(d.satkerLevel,'.',c.jabstrukrId) AS satkerLevel,
   c.jabstrukTpstrId AS satkerUnitId,
   c.jabstrukrNama AS satkerNama
FROM 
sdm_ref_jabatan_struktural c
   LEFT JOIN `pub_satuan_kerja` AS d ON c.`jabstrukrSatker`=d.satkerId
   WHERE satkerLevel IS NOT NULL
   UNION 
   SELECT
   CONCAT('100',sk.satkerId) AS `satkerId`,
   CONCAT(sk.satkerLevel,'.',a.jabstrukrId,'.',CONCAT('100',a.jabstrukrId)) AS satkerLevel,
   sk.satkerUnitId AS satkerUnitId,
   c.pegNama
   FROM
      sdm_ref_jabatan_struktural a
   LEFT JOIN sdm_jabatan_struktural b ON b.`jbtnJabstrukrId` = a.`jabstrukrId`
   LEFT JOIN `pub_pegawai` c ON b.`jbtnPegKode` = c.`pegId`
   LEFT JOIN `pub_satuan_kerja` sk ON a.`jabstrukrSatker`=sk.satkerId
   WHERE satkerLevel IS NOT NULL AND b.`jbtnStatus` = 'Aktif'
   
   ) AS temp
WHERE satkerLevel = '%s'
";

$sql['get_parent_level_pegawai']="
SELECT
   CONCAT('100',sk.satkerId) AS `satkerId`,
   CONCAT(sk.satkerLevel,'.',a.jabstrukrId,'.',CONCAT('100',a.jabstrukrId)) AS satkerLevel,
   sk.satkerUnitId AS satkerUnitId,
   c.pegNama
   FROM
      sdm_ref_jabatan_struktural a
   LEFT JOIN sdm_jabatan_struktural b ON b.`jbtnJabstrukrId` = a.`jabstrukrId`
   LEFT JOIN `pub_pegawai` c ON b.`jbtnPegKode` = c.`pegId`
   LEFT JOIN `pub_satuan_kerja` sk ON a.`jabstrukrSatker`=sk.satkerId
   WHERE satkerLevel IS NOT NULL AND b.`jbtnStatus` = 'Aktif'
   
WHERE satkerLevel = '%s'
";


$sql['get_list_staf']="
SELECT peg.pegKodeResmi AS nip,
peg.`pegNama` AS nama
 FROM `sdm_satuan_kerja_pegawai` ac 
LEFT JOIN `pub_pegawai` peg ON peg.pegId=ac.satkerpegPegId
LEFT JOIN `sdm_ref_jabatan_struktural` jab ON jab.`jabstrukrId`=ac.`satkerpegSatkerJabId`
WHERE jab.`jabstrukrSatker`='%s' AND ac.`satkerpegAktif` = 'Aktif'
";

$sql['get_count_staf']="
SELECT  COUNT(*) as total
 FROM `sdm_satuan_kerja_pegawai` ac 
LEFT JOIN `pub_pegawai` peg ON peg.pegId=ac.satkerpegPegId
LEFT JOIN `sdm_ref_jabatan_struktural` jab ON jab.`jabstrukrId`=ac.`satkerpegSatkerJabId`
WHERE jab.`jabstrukrSatker`='%s' AND ac.`satkerpegAktif` = 'Aktif'
";

$sql['get_kepala_staf']="
SELECT
   CONCAT('100',sk.satkerId) AS `satkerId`,
   CONCAT(sk.satkerLevel,'.',a.jabstrukrId,'.',CONCAT('100',a.jabstrukrId)) AS satkerLevel,
   sk.satkerUnitId AS satkerUnitId,
   c.pegNama AS nama,
   a.jabstrukrNama AS jabatan,
   c.`pegKodeResmi` AS nip,
   a.jabstrukrKomp as kompetensi
   
   FROM
      sdm_ref_jabatan_struktural a
   LEFT JOIN sdm_jabatan_struktural b ON b.`jbtnJabstrukrId` = a.`jabstrukrId`
   LEFT JOIN `pub_pegawai` c ON b.`jbtnPegKode` = c.`pegId`
   LEFT JOIN `pub_satuan_kerja` sk ON a.`jabstrukrSatker`=sk.satkerId
   WHERE satkerLevel IS NOT NULL AND b.`jbtnStatus` = 'Aktif'
   AND a.`jabstrukrSatker` = '%s' 
";

$sql['get_title']="
SELECT satkerNama AS nama FROM `pub_satuan_kerja` WHERE `satkerId` = '%s'
";
?>