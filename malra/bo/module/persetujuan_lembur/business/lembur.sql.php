<?php
//===GET===
$sql['get_count2'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   sdm_lembur
WHERE
   lemburStatus = 'request'
   %s
   GROUP BY lemburId
";   

$sql['get_data2']="
SELECT
	a.lemburId as 'id',
	b.pegNama as 'nama',
	b.pegKodeResmi as 'nip',
	d.satkerNama as 'satker',
	a.lemburPegId as 'idPeg',
	a.lemburNo as 'no',
	a.lemburPengajuan as 'tglaju',
	a.lemburSubmit as 'tglsub',
	TIME_FORMAT(a.lemburMulai, '%s') as 'mulai',
  TIME_FORMAT(a.lemburSelesai, '%s') as 'selesai',
  TIME_FORMAT(TIMEDIFF(a.lemburSelesai, a.lemburMulai), '%s') as 'durasi',
	a.lemburAlasan as 'alasan',
	a.lemburStatus as 'status',
	a.lemburTglStatus as 'tglstat'
FROM
	sdm_lembur a
LEFT JOIN pub_pegawai b ON b.pegId = a.lemburPegId
LEFT JOIN sdm_satuan_kerja_pegawai c ON c.satkerpegPegId = b.pegId
LEFT JOIN pub_satuan_kerja d ON d.satkerId = c.satkerpegSatkerId
WHERE
  a.lemburStatus = 'request'
  %s
GROUP BY 
  a.lemburId
ORDER BY 
  a.lemburNo
LIMIT %s,%s
"; 

$sql['get_data_by_id']="
SELECT 
   pegId as 'id',
   pegKodeResmi as 'nip',
   pegNama as 'nama',
   pegAlamat as 'alamat',
   pegFoto as 'foto',
   satkerNama as 'satker',
   pegdtDirSpv as 'id_spv',
   pegdtMor as 'id_mor'
FROM 
   pub_pegawai
   LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId = pegId
   LEFT JOIN pub_satuan_kerja ON satkerId = satkerpegSatkerId
   LEFT JOIN sdm_pegawai_detail ON pegdtPegId = pegId
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
	a.lemburPegId as 'idPeg',
	a.lemburNo as 'no',
	a.lemburPengajuan as 'tglaju',
	a.lemburSubmit as 'tglsub',
	TIME_FORMAT(a.lemburMulai, '%s') as 'mulai',
  TIME_FORMAT(a.lemburSelesai, '%s') as 'selesai',
  TIME_FORMAT(TIMEDIFF(a.lemburSelesai, a.lemburMulai), '%s') as 'durasi',
	a.lemburAlasan as 'alasan',
	a.lemburStatus as 'status',
	a.lemburTglStatus as 'tglstat'
FROM
	sdm_lembur a
WHERE
	a.lemburId = '%s'

";

$sql['do_update_lembur'] = "
UPDATE sdm_lembur
SET 
	lemburStatus = '%s',
	lemburTglStatus = '%s'
WHERE 
	lemburId = '%s'
";

?>
