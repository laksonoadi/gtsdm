<?php
$sql['get_combo_satker'] = "
SELECT 
   satkerId as id,
   satkerNama as name,
   satkerLevel
FROM 
   pub_satuan_kerja
ORDER BY satkerId
";

$sql['get_parent_level']="
SELECT satkerId
FROM pub_satuan_kerja
WHERE satkerLevel='%s'
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
SELECT
   satkerId,
   satkerLevel,
   satkerUnitId,
   satkerNama
FROM 
   pub_satuan_kerja
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
   a.satkerNama
FROM 
   pub_satuan_kerja a
LEFT JOIN gtfw_unit b ON a.satkerUnitId = b.UnitId
WHERE
   a.satkerId = '%s'
";

$sql['insert_satker']="
INSERT INTO
   pub_satuan_kerja
(satkerLevel, satkerUnitId, satkerNama, satkerCreationDate, satkerUserId)
 VALUES('%s','%s','%s',now(),'%s')
";

$sql['update_satker']="
UPDATE 
   pub_satuan_kerja
SET 
   satkerLevel = '%s',
   satkerUnitId = '%s',
   satkerNama = '%s',
   satkerLastUpdate = now(),
   satkerUserId = '%s'
WHERE 
    satkerId = '%s'
";

$sql['delete_satker']="
DELETE FROM
   pub_satuan_kerja
WHERE
   satkerLevel LIKE %s
";



?>