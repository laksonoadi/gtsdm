<?php
//===ADDITIONAL===
$sql['comboKategori'] = "
SELECT 
	sptkategoriId AS id,
	sptkategoriNama AS name
FROM sdm_spt_kategori
ORDER BY sptkategoriNama
";   

//===GET===
$sql['count'] = "
SELECT 
	COUNT(sptformatId) AS total
FROM sdm_spt_format
WHERE 
	sptformatSptKategoriId LIKE '%s'
";   

$sql['get']="
SELECT 
	sptformatId AS id,
	sptformatSptKategoriId AS kategori,
	sptkategoriNama AS kategori_nama,
	sptformatKeterangan AS keterangan,
	sptformatIsAktif AS aktif
FROM sdm_spt_format
	JOIN sdm_spt_kategori ON sptformatSptKategoriId = sptkategoriId
WHERE 
	sptformatSptKategoriId LIKE '%s'
ORDER BY sptformatFile
LIMIT %s,%s
";

$sql['getById']="
SELECT 
	sptformatId AS id,
	sptformatSptKategoriId AS kategori,
	sptkategoriNama AS kategori_nama,
	sptformatFile AS file,
	sptformatExt AS ext,
	sptformatKeterangan AS keterangan,
	sptformatIsAktif AS aktif
FROM sdm_spt_format
	JOIN sdm_spt_kategori ON sptformatSptKategoriId = sptkategoriId
WHERE sptformatId = '%s'
";

$sql['getKategoriIdAktif']="
SELECT 
	IF(sptformatIsAktif = '1', sptformatSptKategoriId, NULL) AS id
FROM sdm_spt_format
WHERE sptformatId = '%s'
";

//===SET===
$sql['add'] = "
INSERT INTO sdm_spt_format SET
	sptformatSptKategoriId = '%s',
	sptformatExt = '%s',
	sptformatFile = '%s',
	sptformatKeterangan = '%s',
	sptformatIsAktif = '%s'
";

$sql['fullUpdate'] = "
UPDATE sdm_spt_format SET
	sptformatSptKategoriId = '%s',
	sptformatExt = '%s',
	sptformatFile = '%s',
	sptformatKeterangan = '%s',
	sptformatIsAktif = '%s'
WHERE sptformatId = '%s'
";  

$sql['update'] = "
UPDATE sdm_spt_format SET
	sptformatSptKategoriId = '%s',
	sptformatKeterangan = '%s',
	sptformatIsAktif = '%s'
WHERE sptformatId = '%s'
";  

$sql['nonaktif'] = "
UPDATE sdm_spt_format SET
	sptformatIsAktif = '0'
WHERE sptformatSptKategoriId = '%s'
";  

$sql['aktivasi'] = "
UPDATE sdm_spt_format SET
	sptformatIsAktif = '1'
WHERE sptformatSptKategoriId = '%s'
LIMIT 1
";  

$sql['delete'] = "
DELETE FROM sdm_spt_format
WHERE sptformatId = '%s'
";
?>