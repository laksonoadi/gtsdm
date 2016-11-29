<?php
//===GET===
$sql['get_count'] = "
SELECT 
  COUNT(cutiperId) AS total
FROM 
  sdm_cuti_periode
WHERE 
  cutiperAwal >= '%s'
  AND cutiperAkhir <= '%s'
LIMIT 1
";   

$sql['get_data']="
SELECT 
  periodecutiId,
  periodecutiAwal,
  periodecutiAkhir,
  periodecutiTotal,
  periodecutiStatus
FROM 
  sdm_ref_periode_cuti
ORDER BY 
  periodecutiId
LIMIT %s,%s
";

$sql['get_data_by_id']="
SELECT 
  periodecutiId,
  periodecutiAwal,
  periodecutiAkhir,
  periodecutiTotal,
  periodecutiStatus
FROM 
  sdm_ref_periode_cuti
WHERE 
  periodecutiId = %s
";

//===DO===
$sql['do_add'] = "
INSERT INTO 
  sdm_ref_periode_cuti
(
 periodecutiAwal,
 periodecutiAkhir,
 periodecutiTotal,
 periodecutiStatus)
VALUES
(
 '%s',
 '%s',
 '%s',
 '%s')  
";

$sql['do_update'] = "
UPDATE 
  sdm_ref_periode_cuti
SET 
  periodecutiId = '%s',
  periodecutiAwal = '%s',
  periodecutiAkhir = '%s',
  periodecutiTotal = '%s',
  periodecutiStatus = '%s'
WHERE 
  periodecutiId = %s
";  

$sql['do_delete'] = "
DELETE FROM
  sdm_ref_periode_cuti
WHERE 
  periodecutiId = %s   
";
?>
