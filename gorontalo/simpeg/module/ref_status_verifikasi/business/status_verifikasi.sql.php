<?php
//===GET===
$sql['get_count'] = "
SELECT 
	COUNT(verstatId) AS total
FROM 
	sdm_verifikasi_ref_status
";   

$sql['get_data']="
SELECT 
	verstatId,
	verstatName,
	verstatIsApproved,
	verstatIcon
FROM 
	sdm_verifikasi_ref_status
ORDER BY 
	verstatId
LIMIT %s,%s
";

$sql['get_data_by_id']="
SELECT 
	verstatId,
	verstatName,
	verstatIsApproved,
	verstatIcon
FROM 
	sdm_verifikasi_ref_status
WHERE 
	verstatId = %s
";

//===DO===
$sql['do_add'] = "
INSERT INTO 
	sdm_verifikasi_ref_status
SET
	verstatName='%s',
	verstatIsApproved='%s',
	verstatIcon='%s',
	verstatCreatedDate=now(),
	verstatCreatedUser='%s'
";

$sql['do_update'] = "
UPDATE
	sdm_verifikasi_ref_status
SET
	verstatName='%s',
	verstatIsApproved='%s',
	verstatIcon='%s',
	verstatModifiedDate=now(),
	verstatModifiedUser='%s'
WHERE 
	verstatId = %s
";  

$sql['do_delete'] = "
DELETE FROM
	sdm_verifikasi_ref_status
WHERE 
	verstatId = %s   
";
?>
