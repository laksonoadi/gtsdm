<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(pendId) AS total
FROM 
   pub_ref_pendidikan
WHERE 
   pendNama LIKE %s
LIMIT 1
   ";   


$sql['get_data']="
SELECT 
   a.pendId,
   a.pendNama,
   a.pendPendkelId,
   b.pendkelNama,
   a.pendCreationDate,
   a.pendLastUpdate,
   a.pendUserId
FROM 
   pub_ref_pendidikan a
LEFT JOIN 
	pub_ref_pendidikan_kelompok b ON a.pendPendkelId = b.pendkelId
WHERE 
   a.pendNama LIKE %s
ORDER BY 
   a.pendId

";

$sql['get_data_by_id']="
SELECT 
   a.pendNama,
   a.pendPendkelId
FROM 
   pub_ref_pendidikan a
WHERE 
   a.pendId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   pub_ref_pendidikan
   (pendPendkelId, pendNama, pendCreationDate, pendUserId)
VALUES('%s','%s',now(),'%s')  
";

$sql['do_update'] = "
UPDATE pub_ref_pendidikan
SET pendPendkelId = '%s',
	pendNama = '%s',
	pendLastUpdate = now(),
	pendUserId = '%s'
WHERE 
	pendId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   pub_ref_pendidikan
WHERE 
   pendId = %s   
";

$sql['get_kelompok_pendidikan']="
SELECT pendkelId AS id,
   pendkelNama AS name
FROM 
   pub_ref_pendidikan_kelompok
ORDER BY 
   pendkelId
";

?>
