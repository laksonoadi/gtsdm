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

$sql['get_data_istri']="
SELECT
	sutriId as 'id',
	sutriNoKartu as 'no_kartu',
	sutriHubungan as 'hub',
	sutriNama as 'nama',
	sutriTmpLahir as 'tmpt',
	sutriTglLahir as 'tgl_lahir',
	sutriTglNikah as 'tgl_nikah',
	sutriIdLain as 'id_lain',
	sutriPekerjaan as 'kerja',
	sutriKeterangan as 'ket',
	IF(sutriTunjanganStatus='0','Yes','No') as 'tunjang_status',
	IF(sutriMeninggalStatus='0','Yes','No') as 'meninggal_status',
	sutriNpwp as 'npwp',
	sutriTelp as 'telp',
	sutriPendidikan as 'educ'
FROM
	sdm_istri_suami
WHERE
	sutriPegId = '%s'
ORDER BY sutriTglNikah DESC
";

$sql['get_data_istri_verifikasi']="
SELECT
	sutriId as 'id',
	sutriNoKartu as 'no_kartu',
	sutriHubungan as 'hub',
	sutriNama as 'nama',
	sutriTmpLahir as 'tmpt',
	sutriTglLahir as 'tgl_lahir',
	sutriTglNikah as 'tgl_nikah',
	sutriIdLain as 'id_lain',
	sutriPekerjaan as 'kerja',
	sutriKeterangan as 'ket',
	IF(sutriTunjanganStatus='0','Yes','No') as 'tunjang_status',
	IF(sutriMeninggalStatus='0','Yes','No') as 'meninggal_status',
	sutriNpwp as 'npwp',
	sutriTelp as 'telp',
	sutriPendidikan as 'educ'
FROM
	sdm_istri_suami
	INNER JOIN sdm_verifikasi_data vd ON vd.`verdataValue`=sutriId AND vd.`verdataStatus`='3' AND vd.`verdataVerifikasiId`='13'
WHERE
	sutriPegId = '%s'
ORDER BY sutriTglNikah DESC
";

$sql['get_data_istri_det']="
SELECT
	sutriId as 'id',
	sutriNoKartu as 'no_kartu',
	sutriHubungan as 'hub',
	sutriNama as 'nama',
	sutriTmpLahir as 'tmpt',
	sutriTglLahir as 'tgl_lahir',
	sutriTglNikah as 'tgl_nikah',
	sutriIdLain as 'id_lain',
	sutriPekerjaan as 'kerja',
	sutriKeterangan as 'ket',
	sutriTunjanganStatus as 'tunjang_status',
	sutriMeninggalStatus as 'meninggal_status',
	sutriNpwp as 'npwp',
	sutriTelp as 'telp',
	sutriPendidikan as 'educ'
FROM
	sdm_istri_suami
WHERE
	sutriId = '%s'
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_istri_suami
   (sutriPegId,sutriNoKartu,sutriHubungan,sutriNama,sutriTmpLahir,sutriTglLahir,
   sutriTglNikah,sutriIdLain,sutriPekerjaan,sutriKeterangan,sutriTunjanganStatus,sutriMeninggalStatus,
   sutriNpwp,sutriTelp,sutriPendidikan)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
	   '%s','%s','%s','%s')  
";

$sql['do_update'] = "
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
	sutriMeninggalStatus = '%s',
	sutriNpwp = '%s',
	sutriTelp = '%s',
	sutriPendidikan = '%s'
WHERE 
	sutriId = '%s'
";

$sql['do_delete'] = "
DELETE FROM
   sdm_istri_suami
WHERE 
   sutriId = %s  
";
?>
