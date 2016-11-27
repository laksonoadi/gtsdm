<?php
//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   pub_pegawai
 %s
   GROUP BY pegId
";   

$sql['get_data']="
SELECT 
   pegId as 'id',
   pegKodeResmi as 'kode',
   pegNama as 'nama',
   satkerNama as 'satker',
   satkerpegSatkerId as 'idsatker'
FROM 
   pub_pegawai
   LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId = pegId
   LEFT JOIN pub_satuan_kerja ON satkerId = satkerpegSatkerId
%s
ORDER BY 
   pegKodeResmi
LIMIT %s,%s
"; 
?>
