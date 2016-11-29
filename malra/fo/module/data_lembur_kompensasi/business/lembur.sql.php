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
   sdm_lembur_kompensasi
WHERE
   lemburkompPegId = %s %s
   GROUP BY lemburkompId
";   

$sql['get_data2']="
SELECT 
   lemburkompId as 'id',   
   lemburkompPengajuan as 'tglaju',
   lemburkompSubmit as 'tglsubmit',
   lemburkompStatus as 'status',
   lemburkompAlasan as 'alasan',
   lemburkompTglStatus as 'tglstat'
FROM 
   sdm_lembur_kompensasi
WHERE
   lemburkompPegId = %s %s
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

$sql['get_data_lembur_kompensasi_det']="
SELECT
	a.lemburkompId as 'id',
	a.lemburkompPengajuan as 'tglaju',
	a.lemburkompSubmit as 'tglsub',
	a.lemburkompAlasan as 'alasan',
	a.lemburkompStatus as 'status',
	DATE_FORMAT(a.lemburkompTglStatus,'%s') as 'tglstat'
FROM
	sdm_lembur_kompensasi a
WHERE
	a.lemburkompId = '%s'
";

$sql['cek_nmr_lembur']="
SELECT 
   lemburkompNo as 'no'
FROM
   sdm_lembur_kompensasi
WHERE
   lemburkompNo = %s
";

$sql['get_tahun_no']="
SELECT 
   substr(lemburkompNo,6,4) as 'tahun'
FROM
   sdm_lembur_kompensasi
ORDER BY
   lemburkompNo ASC
";

$sql['get_no_baru']="
SELECT 
   max(substr(lemburkompNo,1,4))+1 as 'nmr'
FROM
   sdm_lembur_kompensasi
WHERE
   substr(lemburkompNo,6,4) = '%s'
ORDER BY
   lemburkompNo ASC
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
  sdm_lembur_kompensasi
 (lemburkompPegId,
  lemburkompPengajuan,
  lemburkompSubmit,
  lemburkompAlasan,
  lemburkompStatus,
  lemburkompTglStatus)
VALUES
('%s','%s',now(),'%s','request','%s')  
";

$sql['do_update'] = "
UPDATE sdm_lembur_kompensasi
SET 
 lemburkompPegId='%s',
 lemburkompPengajuan='%s',
 lemburkompSubmit=now(),
 lemburkompAlasan='%s',
 lemburkompStatus='%s',
 lemburkompTglStatus='%s'
WHERE 
	lemburkompId = '%s'
";

$sql['do_delete'] = "
DELETE FROM
   sdm_lembur_kompensasi
WHERE 
   lemburkompId = %s  
";

$sql['tambah_jatah_cuti_kompensasi'] = "
UPDATE 
  sdm_cuti_kompensasi_balance
  LEFT JOIN sdm_ref_periode_cuti ON periodecutiId=cutikompbalancePeriodecutiId
SET 
 cutikompbalanceTotal=cutikompbalanceTotal+1
WHERE 
 cutikompbalancePegId = '%s' and periodecutiStatus = 'Aktif'
";

$sql['kurangi_jatah_cuti_kompensasi'] = "
UPDATE 
  sdm_cuti_kompensasi_balance
  LEFT JOIN sdm_ref_periode_cuti ON periodecutiId=cutikompbalancePeriodecutiId
SET 
  cutikompbalanceTotal=cutikompbalanceTotal-1
WHERE 
 cutikompbalancePegId = '%s' and periodecutiStatus = 'Aktif'
";

?>
