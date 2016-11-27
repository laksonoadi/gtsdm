<?php
//===GET===

$sql['get_count1'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   pub_pegawai
 %s
   GROUP BY pegId
";   

$sql['get_data1']="
SELECT 
   pegId as 'id',
   pegKodeResmi as 'nip',
   pegNama as 'nama'
FROM 
   pub_pegawai
%s
ORDER BY 
   pegKodeResmi
LIMIT %s,%s
"; 

$sql['get_count'] = "
SELECT 
  COUNT(balancebenefitId) AS total
FROM 
  sdm_benefit_balance
WHERE 
  balancebenefitAwal >= '%s'
  AND balancebenefitAkhir <= '%s'
LIMIT 1
";   


$sql['get_data']="
SELECT 
  balancebenefitId,
  balancebenefitPegId,
  balancebenefitAwal,
  balancebenefitAkhir,
  balancebenefitTotal,
  balancebenefitDiambil,
  balancebenefitStatus
FROM 
  sdm_benefit_balance
WHERE
  balancebenefitPegId = %s 
  AND balancebenefitAwal >= '%s'
  AND balancebenefitAkhir <= '%s'
ORDER BY 
  balancebenefitId
LIMIT %s,%s
";

$sql['get_data_by_id']="
SELECT 
  balancebenefitId,
  balancebenefitPegId,
  balancebenefitAwal,
  balancebenefitAkhir,
  balancebenefitTotal,
  balancebenefitDiambil,
  balancebenefitStatus
FROM 
  sdm_benefit_balance
WHERE 
  balancebenefitId = %s
";

//===DO===
$sql['do_add'] = "
INSERT INTO 
  sdm_benefit_balance
(balancebenefitPegId,
 balancebenefitAwal,
 balancebenefitAkhir,
 balancebenefitTotal,
 balancebenefitStatus)
VALUES
('%s',
 '%s',
 '%s',
 '%s',
 '%s')  
";

$sql['do_update'] = "
UPDATE 
  sdm_benefit_balance
SET 
  balancebenefitPegId = '%s',
  balancebenefitAwal = '%s',
  balancebenefitAkhir = '%s',
  balancebenefitTotal = '%s',
  balancebenefitStatus = '%s'
WHERE 
  balancebenefitId = %s
";  

$sql['do_delete'] = "
DELETE FROM
  sdm_benefit_balance
WHERE 
  balancebenefitId = %s   
";
?>
