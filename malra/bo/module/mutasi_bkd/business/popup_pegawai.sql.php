<?php
//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   pub_pegawai
   LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId = pegId
   LEFT JOIN pub_satuan_kerja ON satkerId = satkerpegSatkerId
 %s
   GROUP BY pegId
";   

$sql['get_data']="
SELECT 
   pegId as 'id',
   pegKodeResmi as 'kode',
   pegNama as 'nama',
   satkerNama as 'satker',
   satkerpegSatkerId as 'idsatker',
   satkerLevel as 'levelSatker'
FROM 
   pub_pegawai
   LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId = pegId
   LEFT JOIN pub_satuan_kerja ON satkerId = satkerpegSatkerId
%s
GROUP BY
   pegId
ORDER BY 
   pegKodeResmi
LIMIT %s,%s
";

$sql['get_satker_atasan']="
SELECT 
   satkerId as 'idSatker'
FROM 
   pub_satuan_kerja
WHERE 
   satkerLevel = %s
";

$sql['get_level_peg']="
SELECT 
   satkerLevel as 'level'
FROM 
   pub_satuan_kerja
WHERE 
   satkerId = %s
";
 
?>
