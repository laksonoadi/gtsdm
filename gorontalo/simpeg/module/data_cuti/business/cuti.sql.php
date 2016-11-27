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

$sql['get_count2'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   sdm_cuti
WHERE
   cutiPegId = %s %s
   GROUP BY cutiId
";   

$sql['get_data2']="
SELECT 
   cutiId as 'id',
   cutiNo as 'no',
   cutiMulai as 'mulai',
   cutiSelesai as 'selesai',
   tipecutiNama as 'nama',
   cutiStatus as 'status',
   cutiTggjwbKerja as 'tggjwb'
FROM 
   sdm_cuti
   LEFT JOIN sdm_ref_tipe_cuti ON tipecutiId = cutiTipecutiId
WHERE
   cutiPegId = %s %s
ORDER BY cutiNo DESC
LIMIT %s,%s
"; 

$sql['get_data_by_id']="
SELECT 
   pegId as 'id',
   pegKodeResmi as 'nip',
   pegNama as 'nama',
   pegAlamat as 'alamat',
   pegFoto as 'foto'
FROM 
   pub_pegawai
WHERE 
   pegId = %s
";

$sql['get_data_cuti_det']="
SELECT
	a.cutiId as 'id',
	a.cutiPegId as 'peg_id',
	a.cutiNo as 'no',
	a.cutiPengajuan as 'tglaju',
	a.cutiSubmit as 'tglsub',
	a.cutiMulai as 'tglmul',
	a.cutiSelesai as 'tglsel',
	DATEDIFF(a.cutiSelesai, a.cutiMulai)+1 as 'durasi',
	 a.cutiTipecutiId as 'id_cuti',
	 b.tipecutiNama as 'nama_cuti',
	 a.cutiKrngiJthCutiStatus as 'reduced',
	a.cutiAlasan as 'alasan',
	a.cutiStatus as 'status',
	DATE_FORMAT(a.cutiTglStatus, '%s') as 'tglstat',
	a.cutiTggjwbKerja as 'tggkerja',
	a.cutiPeggjwbSmntra as 'pggsmnt',
	a.cutiPeggjwbSmntraKontak as 'pggsmntk',
	a.cutiCutiperId as 'per_id',
	a.cutiCutimassalId as 'cutimassal_id'	
FROM
	sdm_cuti a
	LEFT JOIN sdm_ref_tipe_cuti b ON b.tipecutiId = a.cutiTipecutiId
WHERE
	a.cutiId = '%s'
";

$sql['get_combo_tipe']="
SELECT 
   tipecutiId as id,
   tipecutiNama as name
FROM
   sdm_ref_tipe_cuti
ORDER BY tipecutiNama ASC
";

$sql['cek_nmr_cuti']="
SELECT 
   cutiNo as 'no'
FROM
   sdm_cuti
WHERE
   cutiNo = %s
";

$sql['get_tahun_no']="
SELECT 
   substr(cutiNo,6,4) as 'tahun'
FROM
   sdm_cuti
ORDER BY
   cutiNo ASC
";

$sql['get_no_baru']="
SELECT 
   max(substr(cutiNo,1,4))+1 as 'nmr'
FROM
   sdm_cuti
WHERE
   substr(cutiNo,6,4) = '%s'
ORDER BY
   cutiNo ASC
";

$sql['get_last_id']="
SELECT 
   max(cutiId) as 'last_id'
FROM
   sdm_cuti
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_cuti
   (cutiPegId,cutiNo,cutiPengajuan,cutiSubmit,cutiMulai,cutiSelesai,
    cutiTipecutiId,cutiKrngiJthCutiStatus,cutiAlasan,cutiStatus,cutiTglStatus,cutiTggjwbKerja,cutiPeggjwbSmntra,
    cutiPeggjwbSmntraKontak,cutiCutiperId)
VALUES('%s','%s',now(),now(),'%s','%s','%s',
       '%s','%s','%s',now(),'%s','%s',
       '%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_cuti
SET 
	cutipegId = '%s',
	cutiNo = '%s',
	cutiMulai = '%s',
	cutiSelesai = '%s',
	 cutiTipecutiId = '%s',
	 cutiKrngiJthCutiStatus = '%s',
	 cutiAlasan = '%s',
	 cutiStatus = '%s',
	cutiTglStatus = '%s',
	cutiTggjwbKerja = '%s',
	cutiPeggjwbSmntra = '%s',
  cutiPeggjwbSmntraKontak = '%s',
   cutiCutiperId = '%s'
WHERE 
	cutiId = '%s'
";

$sql['do_delete'] = "
DELETE FROM
   sdm_cuti
WHERE 
   cutiId = %s  
";

$sql['get_periode_cuti_by_peg_id']="
SELECT 
  cutiperId as per_id,
  cutiperPegId,
  periodecutiAwal,
  periodecutiAkhir,
  cutiperPeriodecutiId,
  (cutiperDiambil+cutiperTotal) as cutiperTotal,
  cutiperDiambil,
  cutiperTotal as cutipersisa
FROM
   sdm_cuti_periode
LEFT JOIN sdm_cuti ON cutiPegId = cutiperPegId
LEFT JOIN sdm_ref_periode_cuti ON periodecutiId=cutiperPeriodecutiId
WHERE
   cutiperPegId = '%s'
   AND periodecutiStatus = 'Aktif'
ORDER BY
   cutiId ASC
LIMIT
  0,1
";

$sql['do_update_periode_cuti_diambil'] = "
UPDATE
  sdm_cuti_periode 
SET
  cutiperDiambil = IFNULL(cutiperDiambil,0) + %s,
  cutiperTotal = cutiperTotal - %s
WHERE 
  cutiperPegId	= %s
  AND cutiperId = %s
  #AND cutiperStatus = 'Active'
";

$sql['do_update_periode_cuti_diambil_tambah_by_id'] = "
UPDATE
  sdm_cuti_periode 
SET
  cutiperDiambil = cutiperDiambil + %s,
  cutiperTotal = cutiperTotal - %s
WHERE 
  cutiperPegId	= %s
  AND cutiperId = %s
  #AND cutiperStatus = 'Active'
";

$sql['do_update_periode_cuti_diambil_kurang_by_id'] = "
UPDATE
  sdm_cuti_periode 
SET
  cutiperDiambil = cutiperDiambil - %s,
  cutiperTotal = cutiperTotal + %s
WHERE 
  cutiperPegId	= %s
  AND cutiperId = %s
  #AND cutiperStatus = 'Active'
";

$sql['get_sql_generate_number'] = "
	SELECT 
		formatNumberFormula 
	FROM 
		sdm_ref_formula_number 
	WHERE 
		formatNumberCode = '%s' 
	AND 
		formatNumberIsAktif = 'Y' 
	LIMIT 0,1
";

$sql['get_hari_libur'] = "
SELECT 
   hariliburTgl as tanggal
FROM 
   sdm_ref_hari_libur
";

$sql['get_combo_tahun_cuti']="
SELECT
	Year(PeriodecutiAwal) as id,
	Year(PeriodecutiAwal) as name
FROM
	sdm_cuti_periode
	LEFT JOIN sdm_ref_periode_cuti ON (periodecutiId=cutiperPeriodecutiId)
WHERE cutiperPegId=%s
ORDER BY Year(PeriodecutiAwal) DESC
"; 

?>
