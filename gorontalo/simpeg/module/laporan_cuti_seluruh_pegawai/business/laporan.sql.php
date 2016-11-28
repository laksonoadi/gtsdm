<?php
$sql['get_combo_unit_kerja'] = "
SELECT
  satkerId as id,
  satkerNama as name
FROM 
  pub_satuan_kerja
";

$sql['get_count_disetujui'] = "
SELECT 
 COUNT(cutiId) AS disetujui
FROM 
sdm_cuti
WHERE 
cutiStatus='approved' AND
cutiPegId = %s AND cutiCutiperId= %s
";

$sql['get_count_ditolak'] = "
SELECT 
 COUNT(cutiId) AS ditolak
FROM 
sdm_cuti
WHERE 
cutiStatus='rejected' AND
cutiPegId = %s AND cutiCutiperId= %s
";

$sql['get_count_proses'] = "
SELECT 
 COUNT(cutiId) AS diproses
FROM 
sdm_cuti
WHERE 
cutiStatus='request' AND
cutiPegId = %s AND cutiCutiperId= %s
";

$sql['get_data_cuti_pegawai']="
SELECT 
DISTINCT
pegId AS idPeg,
pegKodeResmi AS nip,
CONCAT(IFNULL(pegGelarDepan, ''), IF(IFNULL(pegGelarDepan, '')='', '', ' '), pegNama, IF(IFNULL(pegGelarBelakang, '')='', '', ', '), IFNULL(pegGelarBelakang, '')) AS nama, 
pktgolPktgolrId AS gol, 
jabstrukrNama AS jabatan,
cutiperId AS idper,
CONCAT(periodecutiAwal,'-',periodecutiAkhir) AS periode,
cutiperTotal AS jatahcuti,
cutiperDiambil AS cutiambil,
cutiperSisaPeriodeSebelumnya AS sisa
FROM 
sdm_cuti 
LEFT JOIN sdm_cuti_periode ON cutiperId=cutiCutiperId
LEFT JOIN sdm_ref_periode_cuti ON periodecutiId=cutiperPeriodecutiId
LEFT JOIN pub_pegawai ON cutiperPegId=pegId
INNER JOIN sdm_jabatan_fungsional a ON a.jbtnPegKode=pegId 
LEFT JOIN pub_ref_jabatan_fungsional ON jbtnJabfungrId=jabfungrId 
LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif' 
LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId 
LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif' 
LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId 
LEFT JOIN sdm_jabatan_struktural b ON b.jbtnPegKode=pegId AND b.jbtnStatus='Aktif' 
LEFT JOIN sdm_ref_jabatan_struktural ON jbtnJabstrukrId=jabstrukrId 
LEFT JOIN sdm_dosen_kepakaran ON dosenpakarPegKode=pegId 
LEFT JOIN sdm_ref_Kepakaran ON dosenKepakaranId=kepakaranrId 
WHERE 
periodecutiStatus ='Aktif' 
%unit_kerja%
ORDER BY pegNama
%limit%
"; 

?>
