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
  periodecutiAwal,
  periodecutiAkhir,
  cutiperPeriodecutiId,
  cutiperTotal as cutiperTotal,
  cutiperDiambil,
  cutiperSisaPeriodeSebelumnya as cutipersisa
FROM 
  sdm_cuti_periode
  LEFT JOIN sdm_ref_periode_cuti ON periodecutiId=cutiperPeriodecutiId
WHERE
  cutiperPegId = %s 
  AND periodecutiAwal >= '%s'
  AND periodecutiAkhir <= '%s'
ORDER BY 
  cutiperId
LIMIT %s,%s
";

$sql['get_data_periode_cuti']="
SELECT 
	periodecutiId as id,
	CONCAT(DATE_FORMAT(periodecutiAwal,'%d-%M-%Y'),' - ',DATE_FORMAT(periodecutiAkhir,'%d-%M-%Y')) AS name
	
FROM 
	sdm_ref_periode_cuti
";

$sql['get_data_periode_cuti_by_id']="
SELECT 
	periodecutiId AS id,
	periodecutiTotal AS totalall
	
FROM 
	sdm_ref_periode_cuti
WHERE 
	periodecutiId = %s
";

$sql['get_data_by_id']="
SELECT 
  cutiperId,
  cutiperPegId,
  cutiperPeriodecutiId,
  periodecutiAwal,
  periodecutiAkhir,
  cutiperTotal,
  cutiperDiambil,
  cutiperSisaPeriodeSebelumnya
FROM 
  sdm_cuti_periode 
  LEFT JOIN sdm_ref_periode_cuti ON periodecutiId=cutiperPeriodecutiId
WHERE 
  cutiperId = %s
";

$sql['get_data_by_idpeg']="
SELECT 
  cutiperId,
  cutiperPegId,
  cutiperPeriodecutiId,
  periodecutiAwal,
  periodecutiAkhir,
  (cutiperDiambil+cutiperTotal) AS cutiperTotal,
  cutiperDiambil,
  cutiperSisaPeriodeSebelumnya,
  cutiperTotal AS cutipersisa
FROM 
  sdm_cuti_periode 
  LEFT JOIN sdm_ref_periode_cuti ON periodecutiId=cutiperPeriodecutiId
WHERE 
  cutiperPegId = %s
"; 

$sql['get_sisa_cuti_periode'] = "
SELECT 
  	cutiperSisaPeriodeSebelumnya AS sisa
FROM 
  	sdm_cuti_periode
WHERE
  	cutiperPegId = %d AND cutiperPeriodecutiId = %d
ORDER BY 
  	cutiperId DESC
LIMIT 0, 1
";

$sql['get_sisa_cuti_sebelumnya'] = "
SELECT 
  	cutiperSisaPeriodeSebelumnya AS sisa
FROM 
  	sdm_cuti_periode
WHERE
  	cutiperPegId = %d AND cutiperPeriodecutiId != %d
ORDER BY 
  	cutiperId DESC
LIMIT 0, 1
";

//===DO===
$sql['do_add'] = "
INSERT INTO sdm_cuti_periode(
	cutiperPegId,
 	cutiperPeriodecutiId,
 	cutiperTotal,
 	cutiperDiambil,
 	cutiperSisaPeriodeSebelumnya
)
VALUES(
	'%s',
 	'%s',
 	'%s',
 	'%s',
 	'%s'
)  
";

$sql['do_update'] = "
UPDATE 
  sdm_cuti_periode
SET 
  cutiperPegId = '%s',
  cutiperPeriodecutiId = '%s',
  cutiperTotal = '%s',
  cutiperDiambil = '%s',
  cutiperSisaPeriodeSebelumnya = '%s'
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
