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
	sdrGelarDepan as 'gelar_depan',
	sdrGelarBelakang as 'gelar_belakang',
	sdrJnsKelamin as 'jenkel',
	sdrTmpLahir as 'tmpt',
	sdrTglLahir as 'tgl_lahir',
	sdrAgamaId as 'agama_id',
	b.agmNama as 'agama',
	sdrAlamat as 'alamat',
	sdrPekerjaan as 'kerja',
	sdrKeterangan as 'ket',
	sdrPendidikan as 'educ',
	sdrTelp as 'telp',
	IF(sdrMeninggalStatus='0','Ya','Tidak') as 'meninggal_status',
	sdrMeninggalStatus as 'meninggal_status_ori',
	sdrTglMeninggal as 'tgl_meninggal',
	sdrNoAktaMeninggal as 'no_akta_meninggal',
	sdrAktaMeninggal as 'akta_meninggal'
FROM
	sdm_saudara
	LEFT JOIN pub_ref_agama b ON sdrAgamaId = b.agmId
WHERE
	sdrPegId = '%s'
ORDER BY sdrTglLahir ASC
";

$sql['get_data_saudara_verifikasi']="
SELECT
	sdrId as 'id',
	sdrNama as 'nama',
	sdrGelarDepan as 'gelar_depan',
	sdrGelarBelakang as 'gelar_belakang',
	sdrJnsKelamin as 'jenkel',
	sdrTmpLahir as 'tmpt',
	sdrTglLahir as 'tgl_lahir',
	sdrAgamaId as 'agama_id',
	b.agmNama as 'agama',
	sdrAlamat as 'alamat',
	sdrPekerjaan as 'kerja',
	sdrKeterangan as 'ket',
	sdrPendidikan as 'educ',
	sdrTelp as 'telp',
	IF(sdrMeninggalStatus='0','Ya','Tidak') as 'meninggal_status',
	sdrMeninggalStatus as 'meninggal_status_ori',
	sdrTglMeninggal as 'tgl_meninggal',
	sdrNoAktaMeninggal as 'no_akta_meninggal',
	sdrAktaMeninggal as 'akta_meninggal'
FROM
	sdm_saudara
	LEFT JOIN pub_ref_agama b ON sdrAgamaId = b.agmId
	INNER JOIN sdm_verifikasi_data vd ON vd.`verdataValue`=sdrId AND vd.`verdataStatus`='3' AND vd.`verdataVerifikasiId`='16'
WHERE
	sdrPegId = '%s'
ORDER BY sdrTglLahir ASC
";

$sql['get_data_saudara_det']="
SELECT
	sdrId as 'id',
	sdrNama as 'nama',
	sdrGelarDepan as 'gelar_depan',
	sdrGelarBelakang as 'gelar_belakang',
	sdrJnsKelamin as 'jenkel',
	sdrTmpLahir as 'tmpt',
	sdrTglLahir as 'tgl_lahir',
	sdrAgamaId as 'agama_id',
	b.agmNama as 'agama',
	sdrAlamat as 'alamat',
	sdrPekerjaan as 'kerja',
	sdrKeterangan as 'ket',
	sdrPendidikan as 'educ',
	sdrTelp as 'telp',
	IF(sdrMeninggalStatus='0','Ya','Tidak') as 'meninggal_status',
	sdrMeninggalStatus as 'meninggal_status_ori',
	sdrTglMeninggal as 'tgl_meninggal',
	sdrNoAktaMeninggal as 'no_akta_meninggal',
	sdrAktaMeninggal as 'akta_meninggal'
FROM
	sdm_saudara
	LEFT JOIN pub_ref_agama b ON sdrAgamaId = b.agmId
WHERE
	sdrId = '%s'
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
   sdm_saudara
   (sdrPegId,
   sdrNama,sdrGelarDepan,sdrGelarBelakang,
   sdrJnsKelamin,sdrTmpLahir,sdrTglLahir,
   sdrAgamaId,sdrAlamat,sdrPekerjaan,sdrPendidikan,
   sdrTelp,sdrKeterangan,
   sdrMeninggalStatus,sdrTglMeninggal,sdrNoAktaMeninggal,sdrAktaMeninggal)
VALUES('%s',
        '%s','%s','%s',
        '%s','%s','%s',
        '%s','%s','%s','%s',
        '%s','%s',
        '%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_saudara
SET 
	sdrNama = '%s',
	sdrGelarDepan = '%s',
	sdrGelarBelakang = '%s',
	sdrJnsKelamin = '%s',
	sdrTmpLahir = '%s',
	sdrTglLahir = '%s',
	sdrAgamaId = '%s',
	sdrAlamat = '%s',
	sdrPekerjaan = '%s',
	sdrPendidikan = '%s',
	sdrTelp = '%s',
	sdrKeterangan = '%s',
	sdrMeninggalStatus = '%s',
	sdrTglMeninggal = '%s',
	sdrNoAktaMeninggal = '%s',
	sdrAktaMeninggal = '%s'
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
