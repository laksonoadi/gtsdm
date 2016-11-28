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

$sql['get_data_ortu']="
SELECT
	ortuId as 'id',
	ortuHubungan as 'hub',
	ortuNama as 'nama',
	ortuGelarDepan as 'gelar_depan',
	ortuGelarBelakang as 'gelar_belakang',
	ortuTmpLahir as 'tmpt',
	ortuTglLahir as 'tgl_lahir',
	ortuAgamaId as 'agama_id',
	b.agmNama as 'agama',
	ortuAlamat as 'alamat',
	ortuPekerjaan as 'kerja',
	ortuKeterangan as 'ket',
	ortuPendidikan as 'educ',
	ortuTelp as 'telp',
	IF(ortuMeninggalStatus='0','Ya','Tidak') as 'meninggal_status',
	ortuMeninggalStatus as 'meninggal_status_ori',
	ortuTglMeninggal as 'tgl_meninggal',
	ortuNoAktaMeninggal as 'no_akta_meninggal',
	ortuAktaMeninggal as 'akta_meninggal'
FROM
	sdm_orangtua
	LEFT JOIN pub_ref_agama b ON ortuAgamaId = b.agmId
WHERE
	ortuPegId = '%s'
ORDER BY
  ortuNama ASC
";

$sql['get_data_ortu_verifikasi']="
SELECT
	ortuId as 'id',
	ortuHubungan as 'hub',
	ortuNama as 'nama',
	ortuGelarDepan as 'gelar_depan',
	ortuGelarBelakang as 'gelar_belakang',
	ortuTmpLahir as 'tmpt',
	ortuTglLahir as 'tgl_lahir',
	ortuAgamaId as 'agama_id',
	b.agmNama as 'agama',
	ortuAlamat as 'alamat',
	ortuPekerjaan as 'kerja',
	ortuKeterangan as 'ket',
	ortuPendidikan as 'educ',
	ortuTelp as 'telp',
	IF(ortuMeninggalStatus='0','Ya','Tidak') as 'meninggal_status',
	ortuMeninggalStatus as 'meninggal_status_ori',
	ortuTglMeninggal as 'tgl_meninggal',
	ortuNoAktaMeninggal as 'no_akta_meninggal',
	ortuAktaMeninggal as 'akta_meninggal'
FROM
	sdm_orangtua
	LEFT JOIN pub_ref_agama b ON ortuAgamaId = b.agmId
	INNER JOIN sdm_verifikasi_data vd ON vd.`verdataValue`=ortuId AND vd.`verdataStatus`='3' AND vd.`verdataVerifikasiId`='14'
WHERE
	ortuPegId = '%s'
ORDER BY
  ortuNama ASC
";

$sql['get_data_ortu_det']="
SELECT
	ortuId as 'id',
	ortuHubungan as 'hub',
	ortuNama as 'nama',
	ortuGelarDepan as 'gelar_depan',
	ortuGelarBelakang as 'gelar_belakang',
	ortuTmpLahir as 'tmpt',
	ortuTglLahir as 'tgl_lahir',
	ortuAgamaId as 'agama_id',
	b.agmNama as 'agama',
	ortuAlamat as 'alamat',
	ortuPekerjaan as 'kerja',
	ortuKeterangan as 'ket',
	ortuPendidikan as 'educ',
	ortuTelp as 'telp',
	IF(ortuMeninggalStatus='0','Ya','Tidak') as 'meninggal_status',
	ortuMeninggalStatus as 'meninggal_status_ori',
	ortuTglMeninggal as 'tgl_meninggal',
	ortuNoAktaMeninggal as 'no_akta_meninggal',
	ortuAktaMeninggal as 'akta_meninggal'
FROM
	sdm_orangtua
	LEFT JOIN pub_ref_agama b ON ortuAgamaId = b.agmId
WHERE
	ortuId = '%s'
";

$sql['get_combo_agama']="
SELECT 
   agmId as id,
   agmNama as name
FROM
   pub_ref_agama
ORDER BY agmNama ASC
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_orangtua
   (ortuPegId,ortuHubungan,
   ortuNama,ortuGelarDepan,ortuGelarBelakang,
   ortuTmpLahir,ortuTglLahir,
   ortuAgamaId,ortuAlamat,ortuPekerjaan,ortuPendidikan,
   ortuTelp,ortuKeterangan,
   ortuMeninggalStatus,ortuTglMeninggal,ortuNoAktaMeninggal,ortuAktaMeninggal)
VALUES('%s','%s',
        '%s','%s','%s',
        '%s','%s',
        '%s','%s','%s','%s',
        '%s','%s',
        '%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_orangtua
SET 
	ortuHubungan = '%s',
	ortuNama = '%s',
	ortuGelarDepan = '%s',
	ortuGelarBelakang = '%s',
	ortuTmpLahir = '%s',
	ortuTglLahir = '%s',
	ortuAgamaId = '%s',
	ortuAlamat = '%s',
	ortuPekerjaan = '%s',
	ortuPendidikan = '%s',
	ortuTelp = '%s',
	ortuKeterangan = '%s',
	ortuMeninggalStatus = '%s',
	ortuTglMeninggal = '%s',
	ortuNoAktaMeninggal = '%s',
	ortuAktaMeninggal = '%s'
WHERE 
	ortuId = '%s'
";

$sql['do_delete'] = "
DELETE FROM
   sdm_orangtua
WHERE 
   ortuId = %s  
";
?>
