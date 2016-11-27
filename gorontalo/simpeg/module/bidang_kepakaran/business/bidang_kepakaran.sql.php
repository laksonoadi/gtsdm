<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(kepakaranrId) AS total
FROM 
   sdm_ref_kepakaran
WHERE 
   kepakaranrNama LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   a.kepakaranrId,
   a.kepakaranrNama,
   a.kepakaranBidangIlmurId,
   b.bidangilmurNama
FROM 
   sdm_ref_kepakaran a
LEFT JOIN 
	sdm_ref_kepakaran_bidang_ilmu b ON a.kepakaranBidangIlmurId = b.bidangilmurId
WHERE 
   a.kepakaranrNama LIKE %s
ORDER BY 
   a.kepakaranrId

";

$sql['get_data_by_id']="
SELECT 
   a.kepakaranrNama,
   a.kepakaranBidangIlmurId
FROM 
   sdm_ref_kepakaran a
WHERE 
   a.kepakaranrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_kepakaran
   (kepakaranBidangIlmurId, kepakaranrNama)
VALUES('%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_kepakaran
SET kepakaranBidangIlmurId = '%s',
	kepakaranrNama = '%s'
WHERE 
	kepakaranrId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_kepakaran
WHERE 
   kepakaranrId = %s   
";

$sql['get_bidang_ilmu']="
SELECT 
   bidangilmurId AS id,
   bidangilmurNama AS name
FROM 
   sdm_ref_kepakaran_bidang_ilmu
ORDER BY 
   bidangilmurId
";

?>
