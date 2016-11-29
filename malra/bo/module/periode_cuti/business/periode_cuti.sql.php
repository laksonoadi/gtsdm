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
  cutiperId,
  cutiperPegId,
  cutiperAwal,
  cutiperAkhir,
  cutiperTotal,
  cutiperDiambil,
  cutiperStatus
FROM 
  sdm_cuti_periode
WHERE
  cutiperPegId = %s 
  AND cutiperAwal >= '%s'
  OR cutiperAkhir <= '%s'
ORDER BY 
  cutiperId
LIMIT %s,%s
";

$sql['get_data_by_id']="
SELECT 
  cutiperId,
  cutiperPegId,
  cutiperAwal,
  cutiperAkhir,
  cutiperTotal,
  cutiperDiambil,
  cutiperStatus
FROM 
  sdm_cuti_periode
WHERE 
  cutiperId = %s
";

//===DO===
$sql['do_add'] = "
INSERT INTO 
  sdm_cuti_periode
(cutiperPegId,
 cutiperAwal,
 cutiperAkhir,
 cutiperTotal,
 cutiperStatus)
VALUES
('%s',
 '%s',
 '%s',
 '%s',
 '%s')  
";

$sql['do_update'] = "
UPDATE 
  sdm_cuti_periode
SET 
  cutiperPegId = '%s',
  cutiperAwal = '%s',
  cutiperAkhir = '%s',
  cutiperTotal = '%s',
  cutiperStatus = '%s'
WHERE 
  cutiperId = %s
";  

$sql['do_delete'] = "
DELETE FROM
  sdm_cuti_periode
WHERE 
  cutiperId = %s   
";
?>
