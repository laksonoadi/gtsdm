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

$sql['get_data_mertua']="
SELECT
	mertuaId as 'id',
	mertuaHubungan as 'hub',
	mertuaNama as 'nama',
	mertuaGelarDepan as 'gelar_depan',
	mertuaGelarBelakang as 'gelar_belakang',
	mertuaTmpLahir as 'tmpt',
	mertuaTglLahir as 'tgl_lahir',
	mertuaAgamaId as 'agama_id',
	b.agmNama as 'agama',
	mertuaAlamat as 'alamat',
	mertuaPekerjaan as 'kerja',
	mertuaKeterangan as 'ket',
	mertuaPendidikan as 'educ',
	mertuaTelp as 'telp',
	IF(mertuaMeninggalStatus='0','Ya','Tidak') as 'meninggal_status',
	mertuaMeninggalStatus as 'meninggal_status_ori',
	mertuaTglMeninggal as 'tgl_meninggal',
	mertuaNoAktaMeninggal as 'no_akta_meninggal',
	mertuaAktaMeninggal as 'akta_meninggal'
FROM
	sdm_mertua
	LEFT JOIN pub_ref_agama b ON mertuaAgamaId = b.agmId
WHERE
	mertuaPegId = '%s'
ORDER BY mertuaTglLahir DESC, mertuaNama ASC
";

$sql['get_data_mertua_verifikasi']="
SELECT
	mertuaId as 'id',
	mertuaHubungan as 'hub',
	mertuaNama as 'nama',
	mertuaGelarDepan as 'gelar_depan',
	mertuaGelarBelakang as 'gelar_belakang',
	mertuaTmpLahir as 'tmpt',
	mertuaTglLahir as 'tgl_lahir',
	mertuaAgamaId as 'agama_id',
	b.agmNama as 'agama',
	mertuaAlamat as 'alamat',
	mertuaPekerjaan as 'kerja',
	mertuaKeterangan as 'ket',
	mertuaPendidikan as 'educ',
	mertuaTelp as 'telp',
	IF(mertuaMeninggalStatus='0','Ya','Tidak') as 'meninggal_status',
	mertuaMeninggalStatus as 'meninggal_status_ori',
	mertuaTglMeninggal as 'tgl_meninggal',
	mertuaNoAktaMeninggal as 'no_akta_meninggal',
	mertuaAktaMeninggal as 'akta_meninggal'
FROM
	sdm_mertua
	LEFT JOIN pub_ref_agama b ON mertuaAgamaId = b.agmId
	INNER JOIN sdm_verifikasi_data vd ON vd.`verdataValue`=mertuaId AND vd.`verdataStatus`='3' AND vd.`verdataVerifikasiId`='15'
WHERE
	mertuaPegId = '%s'
ORDER BY mertuaTglLahir DESC, mertuaNama ASC
";

$sql['get_data_mertua_det']="
SELECT
	mertuaId as 'id',
	mertuaHubungan as 'hub',
	mertuaNama as 'nama',
	mertuaGelarDepan as 'gelar_depan',
	mertuaGelarBelakang as 'gelar_belakang',
	mertuaTmpLahir as 'tmpt',
	mertuaTglLahir as 'tgl_lahir',
	mertuaAgamaId as 'agama_id',
	b.agmNama as 'agama',
	mertuaAlamat as 'alamat',
	mertuaPekerjaan as 'kerja',
	mertuaKeterangan as 'ket',
	mertuaPendidikan as 'educ',
	mertuaTelp as 'telp',
	IF(mertuaMeninggalStatus='0','Ya','Tidak') as 'meninggal_status',
	mertuaMeninggalStatus as 'meninggal_status_ori',
	mertuaTglMeninggal as 'tgl_meninggal',
	mertuaNoAktaMeninggal as 'no_akta_meninggal',
	mertuaAktaMeninggal as 'akta_meninggal'
FROM
	sdm_mertua
	LEFT JOIN pub_ref_agama b ON mertuaAgamaId = b.agmId
WHERE
	mertuaId = '%s'
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
   sdm_mertua
   (mertuaPegId,mertuaHubungan,
   mertuaNama,mertuaGelarDepan,mertuaGelarBelakang,
   mertuaTmpLahir,mertuaTglLahir,
   mertuaAgamaId,mertuaAlamat,mertuaPekerjaan,mertuaPendidikan,
   mertuaTelp,mertuaKeterangan,
   mertuaMeninggalStatus,mertuaTglMeninggal,mertuaNoAktaMeninggal,mertuaAktaMeninggal)
VALUES('%s','%s',
        '%s','%s','%s',
        '%s','%s',
        '%s','%s','%s','%s',
        '%s','%s',
        '%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_mertua
SET 
	mertuaHubungan = '%s',
	mertuaNama = '%s',
	mertuaGelarDepan = '%s',
	mertuaGelarBelakang = '%s',
	mertuaTmpLahir = '%s',
	mertuaTglLahir = '%s',
	mertuaAgamaId = '%s',
	mertuaAlamat = '%s',
	mertuaPekerjaan = '%s',
	mertuaPendidikan = '%s',
	mertuaTelp = '%s',
	mertuaKeterangan = '%s',
	mertuaMeninggalStatus = '%s',
	mertuaTglMeninggal = '%s',
	mertuaNoAktaMeninggal = '%s',
	mertuaAktaMeninggal = '%s'
WHERE 
	mertuaId = '%s'
";

$sql['do_delete'] = "
DELETE FROM
   sdm_mertua
WHERE 
   mertuaId = %s  
";
?>
