<?php
$sql['get_combo_satwil'] = "
SELECT 
   satwilId as id,
   satwilNama as name,
   satwilKode,
   satwilLevel
FROM 
   pub_ref_satuan_wilayah
ORDER BY satwilId
";

$sql['get_parent_level']="
SELECT satwilId
FROM pub_ref_satuan_wilayah
WHERE satwilLevel='%s'
";

$sql['get_satwil_by_level']="
SELECT * 
FROM 
   pub_ref_satuan_wilayah
WHERE 
   satwilLevel like %s
";

/*$sql['get_combo_unit']="
SELECT 
   UnitId as id,
   UnitName as name
FROM
   gtfw_unit
ORDER BY UnitId
";*/

$sql['get_list_satwil']="
SELECT
   satwilId,
   satwilLevel,
   satwilKode,
   satwilNama
FROM 
   pub_ref_satuan_wilayah
";

$sql['get_list_satwil_nama']="
SELECT
   a.satwilId,
   a.satwilLevel,
   a.satwilKode,
   a.satwilNama
FROM 
   pub_ref_satuan_wilayah a
WHERE
   a.satwilNama LIKE %s
";

$sql['count_list_satwil_nama']="
SELECT
   COUNT(satwilId) as TOTAL
FROM 
   pub_ref_satuan_wilayah
WHERE
   satwilNama LIKE %s
";

$sql['get_satwil_detail']="
SELECT
   a.satwilId,
   a.satwilLevel,
   a.satwilKode,
   a.satwilNama
FROM 
   pub_ref_satuan_wilayah a
WHERE
   a.satwilId = '%s'
";

$sql['insert_satwil']="
INSERT INTO
   pub_ref_satuan_wilayah
(satwilLevel, satwilKode, satwilNama, satwilCreationDate, satwilUserId)
 VALUES('%s','%s','%s',now(),'%s')
";

$sql['update_satwil']="
UPDATE 
   pub_ref_satuan_wilayah
SET 
   satwilLevel = '%s',
   satwilKode = '%s',
   satwilNama = '%s',
   satwilLastUpdate = now(),
   satwilUserId = '%s'
WHERE 
    satwilId = '%s'
";

$sql['delete_satwil']="
DELETE FROM
   pub_ref_satuan_wilayah
WHERE
   satwilLevel LIKE %s
";


?>