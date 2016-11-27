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
   sdm_lembur
WHERE
   lemburPegId = %s %s
   GROUP BY lemburId
";   

$sql['get_data2']="
SELECT 
   lemburId as 'id',
   lemburNo as 'no',
   lemburPengajuan as 'tglaju',
   lemburSubmit as 'tglsubmit',
   TIME_FORMAT(lemburMulai, '%s') as 'mulai',
   TIME_FORMAT(lemburSelesai, '%s') as 'selesai',
   TIME_FORMAT(TIMEDIFF(lemburSelesai, lemburMulai), '%s') as 'durasi',
   lemburStatus as 'status',
   lemburTglStatus as 'tglstat'
FROM 
   sdm_lembur
WHERE
   lemburPegId = %s %s
ORDER BY 
   lemburNo
LIMIT %s,%s
"; 

$sql['get_data_by_id']="
SELECT 
   a.pegId as 'id',
   a.pegKodeResmi as 'nip',
   a.pegNama as 'nama',
   a.pegAlamat as 'alamat',
   a.pegFoto as 'foto',
   b.pegdtDirSpv as 'id_spv',
   b.pegdtMor as 'id_mor'
FROM 
   pub_pegawai a
LEFT JOIN sdm_pegawai_detail b ON b.pegdtPegId = a.pegId
WHERE 
   pegId = %s
";

$sql['get_spv_by_spv_id']="
SELECT 
   a.pegId as 'id',
   a.pegNama as 'spv',
   b.pegdtDirSpv as 'id_spv'
FROM 
   pub_pegawai a
LEFT JOIN sdm_pegawai_detail b ON b.pegdtPegId = a.pegId
WHERE 
   pegId = %s
";

$sql['get_mor_by_mor_id']="
SELECT 
   a.pegId as 'id',
   a.pegNama as 'mor',
   b.pegdtMor as 'id_mor'
FROM 
   pub_pegawai a
LEFT JOIN sdm_pegawai_detail b ON b.pegdtPegId = a.pegId
WHERE 
   pegId = %s
";

$sql['get_data_lembur_det']="
SELECT
	a.lemburId as 'id',
	a.lemburNo as 'no',
	a.lemburPengajuan as 'tglaju',
	a.lemburSubmit as 'tglsub',
	TIME_FORMAT(a.lemburMulai, '%s') as 'mulai',
	HOUR(a.lemburMulai) as 'mulai_jam',
	MINUTE(a.lemburMulai) as 'mulai_menit',
	TIME_FORMAT(a.lemburSelesai, '%s') as 'selesai',
	HOUR(a.lemburSelesai) as 'selesai_jam',
	MINUTE(a.lemburSelesai) as 'selesai_menit',
	TIME_FORMAT(TIMEDIFF(a.lemburSelesai, a.lemburMulai), '%s') as 'durasi',
	a.lemburAlasan as 'alasan',
	a.lemburStatus as 'status',
	DATE(a.lemburTglStatus) as 'tglstat'
FROM
	sdm_lembur a
WHERE
	a.lemburId = '%s'
";

$sql['cek_nmr_lembur']="
SELECT 
   lemburNo as 'no'
FROM
   sdm_lembur
WHERE
   lemburNo = %s
";

$sql['get_tahun_no']="
SELECT 
   substr(lemburNo,6,4) as 'tahun'
FROM
   sdm_lembur
ORDER BY
   lemburNo ASC
";

$sql['get_no_baru']="
SELECT 
   max(substr(lemburNo,1,4))+1 as 'nmr'
FROM
   sdm_lembur
WHERE
   substr(lemburNo,6,4) = '%s'
ORDER BY
   lemburNo ASC
";

// DO-----------
$sql['do_add'] = "
INSERT INTO sdm_lembur (
	lemburPegId,
	lemburNo,
	lemburPengajuan,
	lemburSubmit,
	lemburMulai,
	lemburSelesai,
	lemburAlasan,
	lemburStatus,
	lemburTglStatus
	) VALUES ('%s','%s','%s',now(),'%s','%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_lembur
SET 
	lemburPegId='%s',
	lemburNo='%s',
	lemburPengajuan='%s',
	lemburMulai='%s',
	lemburSelesai='%s',
	lemburAlasan='%s',
	lemburStatus='%s',
	lemburTglStatus='%s'
WHERE 
	lemburId = '%s'
";

$sql['do_delete'] = "
DELETE FROM
   sdm_lembur
WHERE 
   lemburId = %s  
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

?>
