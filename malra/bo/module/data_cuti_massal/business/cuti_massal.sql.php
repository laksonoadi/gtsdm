<?php

$sql['get_count'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   sdm_cuti_massal
GROUP BY cutimassalId
";   

$sql['get_data']="
SELECT 
   cutimassalId as 'id',
   cutimassalNama as 'nama',
   cutimassalSubmit as 'submit',
   cutimassalMulai as 'mulai',
   cutimassalSelesai as 'selesai',
   cutimassalAlasan as 'alasan',
   cutimassalTdkIkutPegId as 'tidak_ikut',
   cutimassalFile as 'file'
FROM 
   sdm_cuti_massal
ORDER BY 
   cutimassalSubmit
LIMIT %s,%s
";

$sql['get_data_cuti_massal_det']="
SELECT
	cutimassalId AS 'id',
   cutimassalNama AS 'nama',
   cutimassalSubmit AS 'submit',
   cutimassalMulai AS 'mulai',
   cutimassalSelesai AS 'selesai',
   cutimassalAlasan AS 'alasan',
   cutimassalTdkIkutPegId AS 'tidak_ikut',
   pegKodeResmi AS 'nopak',
   pegNama AS 'namapak',
   cutimassalFile AS 'file'
FROM
	sdm_cuti_massal
	LEFT JOIN pub_pegawai ON pegId=cutimassalTdkIkutPegId
WHERE
	cutimassalId = '%s'
";

$sql['get_max_cuti_massal_id']="
SELECT
	 MAX(cutimassalId) AS 'id' as 'id'
FROM
	sdm_cuti_massal
";

$sql['get_data_cuti_massal_pegawai']="
SELECT 
  a.pegId as 'id',
  a.pegKodeResmi as 'kode',
  a.pegNama as 'nama'
FROM 
  pub_pegawai a
  LEFT JOIN sdm_cuti_massal_pegawai b ON (b.cutimassalpegPegId = a.pegId)
  LEFT JOIN sdm_cuti_massal c ON (c.cutimassalId = b.cutimassalpegCutimassalId)
WHERE
  c.cutimassalId='%s'
ORDER BY
  a.pegKodeResmi ASC
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
  sdm_cuti_massal
(
  cutimassalNama,
  cutimassalSubmit,
  cutimassalMulai,
  cutimassalSelesai,
  cutimassalAlasan,
  cutimassalFile)
VALUES
(
  '%s',
  now(),
  '%s',
  '%s',
  '%s',
  '%s')  
";

$sql['do_update'] = "
UPDATE sdm_cuti_massal
SET 
	cutimassalNama = '%s',
  cutimassalMulai = '%s',
  cutimassalSelesai = '%s',
  cutimassalAlasan = '%s',
  cutimassalTdkIkutPegId = '%s',
  cutimassalFile = '%s'
WHERE 
	cutimassalId = '%s'
";

$sql['do_delete'] = "
DELETE FROM
   sdm_cuti_massal
WHERE 
   cutimassalId = %s  
";

$sql['do_add_massal_cuti'] = "
INSERT INTO 
   sdm_cuti
   (cutiPegId,cutiNo,cutiPengajuan,cutiSubmit,cutiMulai,cutiSelesai,
    cutiTipecutiId,cutiKrngiJthCutiStatus,cutiAlasan,cutiStatus,cutiTglStatus,cutiTggjwbKerja,cutiPeggjwbSmntra,
    cutiPeggjwbSmntraKontak)
VALUES('%s','%s',now(),now(),'%s','%s','%s',
       '%s','%s','approved',now(),'%s','%s',
       '%s')  
";

$sql['do_add_cuti_massal_pegawai'] = "
INSERT INTO 
   sdm_cuti_massal_pegawai
   (cutimassalpegCutimassalId,cutimassalpegPegId)
VALUES('%s','%s')  
";

$sql['get_periode_cuti_by_peg_id']="
SELECT 
   cutiperId as 'per_id'
FROM
   sdm_cuti_periode
LEFT JOIN sdm_cuti ON cutiPegId = cutiperPegId
WHERE
   cutiperPegId = '%s'
   AND cutiperStatus = 'Active'
ORDER BY
   cutiId ASC
LIMIT
  0,1
";

$sql['do_update_periode_cuti_diambil'] = "
UPDATE
  sdm_cuti_periode 
SET
  cutiperDiambil = cutiperDiambil + 1,
  cutiperTotal = cutiperTotal - 1
WHERE 
  cutiperPegId	= %s
";

$sql['do_update_periode_cuti_diambil_tambah_by_id'] = "
UPDATE
  sdm_cuti_periode 
SET
  cutiperDiambil = cutiperDiambil + 1,
  cutiperTotal = cutiperTotal - 1
WHERE 
  cutiperPegId	= %s
  AND cutiId = %s
";

$sql['do_update_periode_cuti_diambil_kurang_by_id'] = "
UPDATE
  sdm_cuti_periode 
SET
  cutiperDiambil = cutiperDiambil - 1,
  cutiperTotal = cutiperTotal + 1
WHERE 
  cutiperPegId	= %s
  AND cutiId = %s
";

?>
