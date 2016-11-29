<?php
//===GET===
$sql['count'] = "
SELECT 
	COUNT(idDafGaji) AS total
FROM sdm_ref_daftar_gaji
WHERE 1=1
	--where--
";   

$sql['get']="
SELECT 
	idDafGaji AS id,
	mkDafGaji AS masa_kerja,
	pktGolGaji AS gol_ruang,
	nominalDafGaji AS nominal,
	pktgolrNama AS pktgol_nama,
	thnDafGaji AS period
FROM sdm_ref_daftar_gaji
LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktGolGaji
WHERE 1=1
	--where--
ORDER BY pktgolrUrut ASC, mkDafGaji ASC
--limit--
";

$sql['getById']="
SELECT 
	idDafGaji AS id,
	mkDafGaji AS masa_kerja,
	pktGolGaji AS gol_ruang,
	nominalDafGaji AS nominal,
	pktgolrNama AS pktgol_nama,
	thnDafGaji AS period
FROM sdm_ref_daftar_gaji
LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktGolGaji
WHERE idDafGaji = '%s'
";

$sql['get_combo_golongan_ruang'] = "
SELECT
	pktgolrId as id,
	CONCAT(pktgolrId, ' - ', pktgolrNama) as name
FROM sdm_ref_pangkat_golongan
WHERE
	LOCATE('/', pktgolrId) <> 0
ORDER BY pktgolrTingkat ASC, pktgolrUrut ASC
";

$sql['get_combo_golongan'] = "
SELECT
	SUBSTRING_INDEX(pktgolrId, '/', 1) as id,
	SUBSTRING_INDEX(pktgolrId, '/', 1) as name
FROM sdm_ref_pangkat_golongan
WHERE
	LOCATE('/', pktgolrId) <> 0
GROUP BY id
ORDER BY pktgolrTingkat ASC, pktgolrUrut ASC
";

$sql['get_combo_ruang'] = "
SELECT
	SUBSTRING_INDEX(pktgolrId, '/', -1) as id,
	SUBSTRING_INDEX(pktgolrId, '/', -1) as name
FROM sdm_ref_pangkat_golongan
WHERE
	LOCATE('/', pktgolrId) <> 0
GROUP BY id
ORDER BY pktgolrTingkat ASC, pktgolrUrut ASC
";

$sql['check_exists'] = "
SELECT 
	COUNT(idDafGaji) AS total
FROM sdm_ref_daftar_gaji
WHERE 1=1
	AND pktGolGaji = '%s'
	AND mkDafGaji = '%s'
	AND idDafGaji <> '%s'
	AND thnDafGaji <> '%s'
";

//===SET===
$sql['add'] = "
INSERT INTO sdm_ref_daftar_gaji
	(mkDafGaji, pktGolGaji, nominalDafGaji,thnDafGaji)
VALUES ('%s', '%s', '%s','%s')
";

$sql['update'] = "
UPDATE sdm_ref_daftar_gaji
SET
	mkDafGaji = '%s',
	pktGolGaji = '%s',
	nominalDafGaji = '%s',
	thnDafGaji = '%s'
WHERE idDafGaji = '%s'
";  

$sql['delete'] = "
DELETE FROM sdm_ref_daftar_gaji
WHERE idDafGaji = '%s'
";
?>