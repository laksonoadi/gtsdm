<?php

$sql['get_list_id_anak_unit_by_unit_id'] = "
SELECT 
	satkerId as id,
	satkerNama as name,
	satkerNama as nama,
	satkerLevel as level,
	satkerUnitId as unitId,
	satkerLevel as unit_kerja_kode,
    satkerNama as label
FROM
	pub_satuan_kerja
WHERE
    (satkerLevel LIKE CONCAT((SELECT satkerLevel FROM pub_satuan_kerja WHERE satkerId='%s' LIMIT 0,1),'.%%') OR satkerId='%s' OR satkerLevel IS NULL)
	AND satkerNama LIKE '%s'
";

// $sql['get_satuan_kerja_by_userid'] = "
// SELECT 
// 	satkerId as id,
// 	satkerNama as name,
// 	satkerNama as nama,
// 	satkerLevel as level,
// 	satkerUnitId as unitId,
// 	satkerLevel as unit_kerja_kode,
//     satkerNama as label
// FROM
// 	pub_satuan_kerja
// WHERE
//     (	satkerLevel LIKE CONCAT((SELECT satkerLevel FROM pub_satuan_kerja INNER JOIN gtfw_user_satuan_kerja ON userunitSatuanKerjaId=satkerId WHERE userunitUserId='%s' LIMIT 0,1),'.%%') OR 
// 		satkerId LIKE (SELECT satkerId FROM pub_satuan_kerja INNER JOIN gtfw_user_satuan_kerja ON userunitSatuanKerjaId=satkerId WHERE userunitUserId='%s' LIMIT 0,1) OR
// 		satkerLevel IS NULL)
// 	AND satkerNama LIKE '%s'
// ";

$sql['get_satuan_kerja_by_userid'] = "
SELECT 
   satkerId as id,
   satkerNama as name,
   satkerNama as nama,
   satkerLevel as level,
   satkerUnitId as unitId,
   satkerLevel as unit_kerja_kode,
   satkerNama as label
FROM
   pub_satuan_kerja
WHERE
    satkerLevel LIKE '%s' 
    OR satkerId = '%s' 
   /* OR satkerLevel IS NULL 
    AND satkerNama LIKE '%s' */
";

$sql['get_combo_satker'] = "
SELECT 
   satkerId as id,
   satkerNama as name,
   satkerLevel
FROM 
   pub_satuan_kerja
ORDER BY satkerStruktural
";

$sql["get_combo_satuan_kerja_by_id"] = "
SELECT SQL_CALC_FOUND_ROWS
	satkerId as id,
	satkerNama as nama,
	satkerNama as name,
    satkerNama as label,
	satkerLevel as level,
	satkerParentId as parentId
FROM pub_satuan_kerja
WHERE
   satkerId = %d
";

$sql["get_combo_satker_order_level"] = "
SELECT SQL_CALC_FOUND_ROWS
	a.satkerId as id,
	a.satkerNama as nama,
	a.satkerLevel as level,
	LEFT(a.satkerLevel, LENGTH(a.satkerLevel) - LOCATE('.', REVERSE(a.satkerLevel))) AS parent_level,
	CAST(SUBSTRING_INDEX(a.satkerLevel, '.', -1) AS SIGNED INT) as child_index,
	a.satkerParentId as parentId,
	(LENGTH(a.satkerLevel) - LENGTH(REPLACE(a.satkerLevel, '.', ''))) AS lv,
	a.satkerUnitId as unit_id
FROM pub_satuan_kerja a
    LEFT JOIN (SELECT * FROM pub_satuan_kerja WHERE satkerId = '%s') b ON 1=1
WHERE 1=1
    --where--
ORDER BY lv ASC, parent_level ASC, child_index ASC, id ASC
";

$sql["get_satker_per_unit"] = "
SELECT SQL_CALC_FOUND_ROWS
    z.UnitId,
    z.UnitName,
    a.satkerId,
    a.satkerNama,
    a.lv
FROM gtfw_unit z
LEFT JOIN (
    SELECT *,
      (
        LENGTH(satkerLevel) - LENGTH(REPLACE(satkerLevel, '.', ''))
      ) AS lv
    FROM pub_satuan_kerja
    ) a ON z.UnitId = a.satkerUnitId
LEFT JOIN (
    SELECT *,
      (
        LENGTH(satkerLevel) - LENGTH(REPLACE(satkerLevel, '.', ''))
      ) AS lv
    FROM pub_satuan_kerja
    ) b ON a.satkerUnitId = b.satkerUnitId AND a.lv > b.lv
WHERE b.lv IS NULL
ORDER BY z.UnitId, a.satkerId
";

$sql['get_combo_unit_satker'] = "
SELECT 
   satkerId as id,
   UnitName as name,
   satkerLevel
FROM 
   gtfw_unit
    LEFT JOIN pub_satuan_kerja ON UnitName = satkerNama
GROUP BY UnitId
";

$sql['get_combo_user_unit_kerja'] = "
SELECT 
    b.satkerId AS `id`,
    b.satkerNama AS `name`
FROM 
    pub_satuan_kerja a
INNER JOIN pub_satuan_kerja b ON (a.satkerId = b.satkerId OR b.satkerLevel LIKE CONCAT(a.satkerLevel, '.%%'))
INNER JOIN gtfw_unit ON b.satkerUnitId=UnitId
WHERE a.satkerId='%s' 
GROUP BY UnitId
";

$sql["get_combo_satuan_kerja_like"] = "
SELECT SQL_CALC_FOUND_ROWS
   satkerId as id,
   satkerNama as nama,
   satkerNama as label,
   satkerLevel as level,
   satkerParentId as parentId
FROM pub_satuan_kerja
WHERE
   satkerLevel LIKE '%s'
   --where--
ORDER BY CAST(SUBSTRING_INDEX(satkerLevel, '.', -1) AS SIGNED INT) ASC
";

$sql["get_combo_jenis_kepegawaian"] = "
SELECT SQL_CALC_FOUND_ROWS
   id_ref_jns_peg as id,
   nama_ref_jns_peg as nama,
   nama_ref_jns_peg as label,
   nama_ref_jns_peg as name
FROM sdm_ref_jenis_kepegawaian
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

$sql['get_parent_level_new']="
SELECT * FROM (SELECT
   satkerId,
   satkerLevel,
   satkerUnitId,
   satkerNama
FROM 
   pub_satuan_kerja a
  
   ) AS temp
WHERE satkerLevel = '%s'
";

$sql['get_satker_by_parent_id']="
SELECT
    c.*,
    p.satkerLevel AS parent_level,
    CAST(SUBSTRING_INDEX(c.satkerLevel, '.', -1) AS SIGNED INT) as child_index,
    (LENGTH(c.satkerLevel) - LENGTH(REPLACE(c.satkerLevel, '.', ''))) AS lv
FROM 
    pub_satuan_kerja c
    LEFT JOIN pub_satuan_kerja p ON p.satkerId = %d
WHERE c.satkerParentId = IFNULL(p.satkerId, 0)
    OR (c.satkerLevel LIKE CONCAT(p.satkerLevel, '.%%')
        AND ((LENGTH(c.satkerLevel) - LENGTH(REPLACE(c.satkerLevel, '.', ''))) = (LENGTH(p.satkerLevel) - LENGTH(REPLACE(p.satkerLevel, '.', '')) + 1))
    )
";

$sql['get_satker_by_level']="
SELECT * 
FROM 
   pub_satuan_kerja
WHERE 
   satkerLevel like %s
";

$sql['get_combo_unit']="
SELECT 
   UnitId as id,
   UnitName as name
FROM
   gtfw_unit
ORDER BY UnitId
";

$sql['get_combo_tipe_struktural']="
SELECT 
   tpstrId as id,
   tpstrNama as name
FROM
   sdm_ref_tipe_struktural
ORDER BY tpstrId
";

$sql['get_list_skts']="
SELECT
   sktsSatkerId,
   sktsTpstrId
FROM 
   sdm_satuan_kerja_struktur
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
   
   
   ) AS temp
";

$sql['get_list_satker_nama']="
SELECT
   a.satkerId,
   a.satkerLevel,
   a.satkerUnitId,
   b.UnitName,
   a.satkerNama
FROM 
   pub_satuan_kerja a
LEFT JOIN gtfw_unit b ON a.satkerUnitId = b.UnitId
WHERE
   a.satkerNama LIKE %s
";

$sql['count_list_satker_nama']="
SELECT
   COUNT(satkerId) as TOTAL
FROM 
   pub_satuan_kerja
WHERE
   satkerNama LIKE %s
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

$sql['insert_satker']="
INSERT INTO
   pub_satuan_kerja
(satkerLevel, satkerParentId, satkerUnitId, satkerNama, satkerStruktural, satkerCreationDate, satkerUserId)
 VALUES('%s','%s','%s','%s','%s',now(),'%s')
";

$sql['update_satker']="
UPDATE 
   pub_satuan_kerja
SET 
   satkerLevel = '%s',
   satkerParentId = '%s',
   satkerUnitId = '%s',
   satkerNama = '%s',
   satkerStruktural = '%s',
   satkerLastUpdate = now(),
   satkerUserId = '%s'
WHERE 
    satkerId = '%s'
";

$sql['update_satker_wo_level']="
UPDATE 
   pub_satuan_kerja
SET 
   satkerParentId = '%s',
   satkerUnitId = '%s',
   satkerNama = '%s',
   satkerStruktural = '%s',
   satkerLastUpdate = now(),
   satkerUserId = '%s'
WHERE 
    satkerId = '%s'
";

$sql['update_satker_descendants']="
UPDATE 
   pub_satuan_kerja
SET 
   satkerLevel = CONCAT('--new_level--', SUBSTRING(satkerLevel, LENGTH('--old_level--') + 1)),
   satkerLastUpdate = now(),
   satkerUserId = '%s'
WHERE 
    satkerLevel LIKE CONCAT('--old_level--', '.%%')
";

$sql['update_satker_node']="
UPDATE 
   pub_satuan_kerja
SET 
   satkerLevel = '%s',
   satkerLastUpdate = now()
WHERE 
    satkerId = '%s'
";


$sql['count_satker_pegawai']="
SELECT COUNT(satkerpegPegId) AS total
FROM pub_satuan_kerja a
    LEFT JOIN pub_satuan_kerja b ON (b.satkerId = a.satkerId OR b.satkerParentId = a.satkerId OR b.satkerLevel LIKE CONCAT(a.satkerLevel, '.%%'))
    INNER JOIN sdm_satuan_kerja_pegawai ON b.satkerId = satkerpegSatkerId
WHERE a.satkerId = '%s'
";

$sql['count_satker_jabstrukr']="
SELECT COUNT(jabstrukrId) AS total
FROM pub_satuan_kerja a
    LEFT JOIN pub_satuan_kerja b ON (b.satkerId = a.satkerId OR b.satkerParentId = a.satkerId OR b.satkerLevel LIKE CONCAT(a.satkerLevel, '.%%'))
    INNER JOIN sdm_ref_jabatan_struktural ON b.satkerId = jabstrukrSatker
WHERE a.satkerId = '%s'
";

$sql['delete_satker']="
DELETE FROM
   pub_satuan_kerja
WHERE
   satkerId = '%s' OR satkerLevel LIKE CONCAT('%s', '.%%')
";

$sql['get_satker_and_level']="
SELECT 
satkerId,
satkerLevel 
FROM 
pub_satuan_kerja 
INNER JOIN gtfw_user_satuan_kerja ON 
userunitSatuanKerjaId=satkerId 
WHERE userunitUserId='%s' 
";

$sql['get_list_pub_satuan_kerja']="
SELECT
   satkerId,
   satkerLevel,
   satkerUnitId,
   satkerNama,
   satkerParentId
FROM 
   pub_satuan_kerja a
";

$sql['get_parent_level_refrensi']="
SELECT
   satkerId,
   satkerLevel,
   satkerUnitId,
   satkerNama
FROM 
   pub_satuan_kerja a
   
WHERE satkerLevel = '%s'
";


$sql['get_data_satuan_kerja']="
SELECT
   satkerId,
   satkerLevel,
   satkerUnitId,
   satkerParentId,
   satkerNama
FROM 
   pub_satuan_kerja a
   WHERE satkerId = '%s'
";

$sql['get_cek_level_satuan_kerja']="
SELECT
   satkerId,
   satkerLevel,
   satkerUnitId,
   satkerParentId,
   satkerNama
FROM 
   pub_satuan_kerja a
   WHERE satkerId = '%s'  AND `satkerParentId` = '%s'
";

$sql['get_list_satuan_kerja']="
SELECT
   satkerId,
   satkerLevel,
   satkerUnitId,
   satkerParentId,
   satkerNama
FROM 
   pub_satuan_kerja a
   WHERE satkerParentId = '%s' 
";


?>