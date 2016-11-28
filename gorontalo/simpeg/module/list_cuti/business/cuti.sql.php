<?php
//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   sdm_cuti
   %s%s
   GROUP BY cutiId
";   

$sql['get_data']="
SELECT 
   cutiId as 'id',
   cutiNo as 'no',
   cutiMulai as 'mulai',
   cutiSelesai as 'selesai',
    tipecutiNama as 'nama',
   cutiStatus as 'status',
   cutiTggjwbKerja as 'tggjwb',
   cutiPegId as 'idpeg'
FROM 
   sdm_cuti
   LEFT JOIN sdm_ref_tipe_cuti ON tipecutiId = cutiTipecutiId
  %s%s
ORDER BY 
   cutiNo
LIMIT %s,%s
"; 

$sql['get_data_cetak']="
SELECT 
   cutiId as 'id',
   cutiNo as 'no',
   cutiMulai as 'mulai',
   cutiSelesai as 'selesai',
    tipecutiNama as 'nama',
   cutiStatus as 'status',
   cutiTggjwbKerja as 'tggjwb',
   cutiPegId as 'idpeg',
    pegNama as 'nmpeg'
FROM 
   sdm_cuti
   LEFT JOIN sdm_ref_tipe_cuti ON tipecutiId = cutiTipecutiId
   LEFT JOIN pub_pegawai ON cutiPegId = pegId
  %s%s
ORDER BY 
   cutiNo
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
	a.cutiNo as 'no',
	a.cutiPengajuan as 'tglaju',
	a.cutiSubmit as 'tglsub',
	a.cutiMulai as 'tglmul',
	a.cutiSelesai as 'tglsel',
	 a.cutiTipecutiId as 'id_cuti',
	 b.tipecutiNama as 'nama_cuti',
	a.cutiAlasan as 'alasan',
	a.cutiStatus as 'status',
	a.cutiTglStatus as 'tglstat',
	a.cutiTggjwbKerja as 'tggkerja',
	a.cutiPeggjwbSmntra as 'pggsmnt',
	a.cutiPeggjwbSmntraKontak as 'pggsmntk'
FROM
	sdm_cuti a
	LEFT JOIN sdm_ref_tipe_cuti b ON b.tipecutiId = a.cutiTipecutiId
WHERE
	a.cutiId = '%s'
";

$sql['get_app_cuti_det']="
SELECT 
   cutiappSatkerId as 'idsatker',
    satkerNama as 'nmsatker',
   cutiappPegId as 'idpegawai',
    pegNama as 'nmpegawai',
   cutiappStatus as 'status'
FROM
   sdm_cuti_approval
   LEFT JOIN pub_satuan_kerja ON satkerId = cutiappSatkerId
   LEFT JOIN pub_pegawai ON pegId = cutiappPegId
WHERE
   cutiappCutiId = '%s'
";

$sql['get_combo_tipe']="
SELECT 
   tipecutiId as id,
   tipecutiNama as name
FROM
   sdm_ref_tipe_cuti
ORDER BY tipecutiNama ASC
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_cuti
   (cutiPegId,cutiNo,cutiPengajuan,cutiSubmit,cutiMulai,cutiSelesai,
    cutiTipecutiId,cutiAlasan,cutiStatus,cutiTglStatus,cutiTggjwbKerja,cutiPeggjwbSmntra,
    cutiPeggjwbSmntraKontak)
VALUES('%s','%s',now(),now(),'%s','%s',
       '%s','%s','submit to approved',now(),'%s','%s',
       '%s')  
";

/*$sql['do_update'] = "
UPDATE sdm_istri_suami
SET 
	sutriNoKartu = '%s',
	sutriHubungan = '%s',
	sutriNama = '%s',
	sutriTmpLahir = '%s',
	sutriTglLahir = '%s',
	sutriTglNikah = '%s',
	sutriIdLain = '%s',
	sutriPekerjaan = '%s',
	sutriKeterangan = '%s',
	sutriTunjanganStatus = '%s',
	sutriMeninggalStatus = '%s'
WHERE 
	sutriId = '%s'
";*/

$sql['do_delete'] = "
DELETE FROM
   sdm_cuti
WHERE 
   cutiId = %s  
";
?>
