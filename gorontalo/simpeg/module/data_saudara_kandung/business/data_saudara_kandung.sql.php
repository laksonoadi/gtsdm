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
   pegKodeResmi as 'nip',
   pegNama as 'nama'
FROM 
   pub_pegawai
%s
ORDER BY 
   pegKodeResmi
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

$sql['get_data_saudara']="
SELECT
	sdrId as 'id',
	sdrNama as 'nama',
	sdrJnsKelamin as 'jenkel',
	sdrTmpLahir as 'tmpt',
	sdrTglLahir as 'tgl_lahir',
	sdrPekerjaan as 'kerja',
	sdrKeterangan as 'ket',
	sdrPendidikan as 'educ',
	sdrTelp as 'telp',
	IF(sdrMeninggalStatus='0','Yes','No') as 'meninggal_status'
FROM
	sdm_saudara
WHERE
	sdrPegId = '%s'
ORDER BY sdrTglLahir ASC
";

$sql['get_data_saudara_verifikasi']="
SELECT
	sdrId as 'id',
	sdrNama as 'nama',
	sdrJnsKelamin as 'jenkel',
	sdrTmpLahir as 'tmpt',
	sdrTglLahir as 'tgl_lahir',
	sdrPekerjaan as 'kerja',
	sdrKeterangan as 'ket',
	sdrPendidikan as 'educ',
	sdrTelp as 'telp',
	IF(sdrMeninggalStatus='0','Yes','No') as 'meninggal_status'
FROM
	sdm_saudara
	INNER JOIN sdm_verifikasi_data vd ON vd.`verdataValue`=sdrId AND vd.`verdataStatus`='3' AND vd.`verdataVerifikasiId`='16'
WHERE
	sdrPegId = '%s'
ORDER BY sdrTglLahir ASC
";

$sql['get_data_saudara_det']="
SELECT
	sdrId as 'id',
	sdrNama as 'nama',
	sdrJnsKelamin as 'jenkel',
	sdrTmpLahir as 'tmpt',
	sdrTglLahir as 'tgl_lahir',
	sdrPekerjaan as 'kerja',
	sdrKeterangan as 'ket',
	sdrPendidikan as 'educ',
	sdrTelp as 'telp',
	sdrMeninggalStatus as 'meninggal_status'
FROM
	sdm_saudara
WHERE
	sdrId = '%s'
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_saudara
   (sdrPegId,sdrNama,sdrJnsKelamin,sdrTmpLahir,sdrTglLahir,
	sdrPekerjaan,sdrKeterangan,sdrPendidikan,sdrTelp,sdrMeninggalStatus)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_saudara
SET 
	sdrNama = '%s',
	sdrJnsKelamin = '%s',
	sdrTmpLahir = '%s',
	sdrTglLahir = '%s',
	sdrPekerjaan = '%s',
	sdrKeterangan = '%s',
	sdrPendidikan = '%s',
	sdrTelp = '%s',
	sdrMeninggalStatus = '%s'
WHERE 
	sdrId = '%s'
";

$sql['do_delete'] = "
DELETE FROM
   sdm_saudara
WHERE 
   sdrId = %s  
";
?>
