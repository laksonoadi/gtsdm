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
   pegKodeResmi as 'nip',
   pegNama as 'nama',
   satkerNama as 'satker',
   satkerpegSatkerId as 'idsatker',
   satkerLevel as 'levelSatker',
   concat(pktgolrId,' ',pktgolrNama) as 'pangkat',
   jabstrukrNama as 'jabatan'
FROM 
   pub_pegawai
   LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId = pegId AND satkerpegAktif='Aktif'
   LEFT JOIN pub_satuan_kerja ON satkerId = satkerpegSatkerId
   LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
   LEFT JOIN sdm_ref_pangkat_golongan ON pktgolPktgolrId=pktgolrId
   LEFT JOIN sdm_jabatan_struktural ON jbtnPegKode=pegId AND jbtnStatus='Aktif'
   LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=jbtnJabstrukrId
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
