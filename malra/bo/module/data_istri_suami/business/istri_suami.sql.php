<?php
//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   pub_pegawai
WHERE 1=1
   --user--
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
WHERE 1=1
    --user--
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
   pegKelamin as 'gender',
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
	sutriGelarDepan as 'gelar_depan',
	sutriGelarBelakang as 'gelar_belakang',
	sutriTmpLahir as 'tmpt',
	sutriTglLahir as 'tgl_lahir',
	sutriNoAkta as 'no_akta',
	sutriAkta as 'akta',
	sutriTglNikah as 'tgl_nikah',
	sutriNoAktaNikah as 'no_akta_nikah',
	sutriAktaNikah as 'akta_nikah',
	sutriTglMeninggal as 'tgl_meninggal',
	sutriNoAktaMeninggal as 'no_akta_meninggal',
	sutriAktaMeninggal as 'akta_meninggal',
	sutriTglCerai as 'tgl_cerai',
	sutriNoAktaCerai as 'no_akta_cerai',
	sutriAktaCerai as 'akta_cerai',
	sutriIdLain as 'id_lain',
	sutriAgamaId as 'agama_id',
	b.agmNama as 'agama',
	sutriPekerjaan as 'kerja',
	sutriKeterangan as 'ket',
	sutriNoAskes as 'no_askes',
	IF(sutriTunjanganStatus='0','Ya','Tidak') as 'tunjang_status',
	sutriTunjanganStatus as 'tunjang_status_ori',
	IF(sutriMeninggalStatus='0','Ya','Tidak') as 'meninggal_status',
	sutriMeninggalStatus as 'meninggal_status_ori',
	IF(sutriCeraiStatus='1','Ya','Tidak') as 'cerai_status',
	sutriCeraiStatus as 'cerai_status_ori',
	sutriNpwp as 'npwp',
	sutriTglNpwp as 'tgl_npwp',
	sutriTelp as 'telp',
	sutriPendidikan as 'educ'
FROM
	sdm_istri_suami
	LEFT JOIN pub_ref_agama b ON sutriAgamaId = b.agmId
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
	sutriGelarDepan as 'gelar_depan',
	sutriGelarBelakang as 'gelar_belakang',
	sutriTmpLahir as 'tmpt',
	sutriTglLahir as 'tgl_lahir',
	sutriNoAkta as 'no_akta',
	sutriAkta as 'akta',
	sutriTglNikah as 'tgl_nikah',
	sutriNoAktaNikah as 'no_akta_nikah',
	sutriAktaNikah as 'akta_nikah',
	sutriTglMeninggal as 'tgl_meninggal',
	sutriNoAktaMeninggal as 'no_akta_meninggal',
	sutriAktaMeninggal as 'akta_meninggal',
	sutriTglCerai as 'tgl_cerai',
	sutriNoAktaCerai as 'no_akta_cerai',
	sutriAktaCerai as 'akta_cerai',
	sutriIdLain as 'id_lain',
	sutriAgamaId as 'agama_id',
	b.agmNama as 'agama',
	sutriPekerjaan as 'kerja',
	sutriKeterangan as 'ket',
	sutriNoAskes as 'no_askes',
	IF(sutriTunjanganStatus='0','Ya','Tidak') as 'tunjang_status',
	sutriTunjanganStatus as 'tunjang_status_ori',
	IF(sutriMeninggalStatus='0','Ya','Tidak') as 'meninggal_status',
	sutriMeninggalStatus as 'meninggal_status_ori',
	IF(sutriCeraiStatus='1','Ya','Tidak') as 'cerai_status',
	sutriCeraiStatus as 'cerai_status_ori',
	sutriNpwp as 'npwp',
	sutriTglNpwp as 'tgl_npwp',
	sutriTelp as 'telp',
	sutriPendidikan as 'educ'
FROM
	sdm_istri_suami
	LEFT JOIN pub_ref_agama b ON sutriAgamaId = b.agmId
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
	sutriGelarDepan as 'gelar_depan',
	sutriGelarBelakang as 'gelar_belakang',
	sutriTmpLahir as 'tmpt',
	sutriTglLahir as 'tgl_lahir',
	sutriNoAkta as 'no_akta',
	sutriAkta as 'akta',
	sutriTglNikah as 'tgl_nikah',
	sutriNoAktaNikah as 'no_akta_nikah',
	sutriAktaNikah as 'akta_nikah',
	sutriTglMeninggal as 'tgl_meninggal',
	sutriNoAktaMeninggal as 'no_akta_meninggal',
	sutriAktaMeninggal as 'akta_meninggal',
	sutriTglCerai as 'tgl_cerai',
	sutriNoAktaCerai as 'no_akta_cerai',
	sutriAktaCerai as 'akta_cerai',
	sutriIdLain as 'id_lain',
	sutriAgamaId as 'agama_id',
	b.agmNama as 'agama',
	sutriPekerjaan as 'kerja',
	sutriKeterangan as 'ket',
	sutriNoAskes as 'no_askes',
	IF(sutriTunjanganStatus='0','Ya','Tidak') as 'tunjang_status',
	sutriTunjanganStatus as 'tunjang_status_ori',
	IF(sutriMeninggalStatus='0','Ya','Tidak') as 'meninggal_status',
	sutriMeninggalStatus as 'meninggal_status_ori',
	IF(sutriCeraiStatus='1','Ya','Tidak') as 'cerai_status',
	sutriCeraiStatus as 'cerai_status_ori',
	sutriNpwp as 'npwp',
	sutriTglNpwp as 'tgl_npwp',
	sutriTelp as 'telp',
	sutriPendidikan as 'educ'
FROM
	sdm_istri_suami
	LEFT JOIN pub_ref_agama b ON sutriAgamaId = b.agmId
WHERE
	sutriId = '%s'
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
   sdm_istri_suami
   (sutriPegId,sutriNoKartu,sutriHubungan,
   sutriNama,sutriGelarDepan,sutriGelarBelakang,
   sutriTmpLahir,sutriTglLahir,sutriNoAkta,sutriAkta,
   sutriIdLain,sutriAgamaId,sutriPekerjaan,sutriKeterangan,sutriTunjanganStatus,
   sutriNpwp,sutriTglNpwp,sutriTelp,sutriNoAskes,sutriPendidikan,
   sutriTglNikah,sutriNoAktaNikah,sutriAktaNikah,
   sutriMeninggalStatus,sutriTglMeninggal,sutriNoAktaMeninggal,sutriAktaMeninggal,
   sutriCeraiStatus,sutriTglCerai,sutriNoAktaCerai,sutriAktaCerai)
VALUES('%s','%s','%s',
        '%s','%s','%s',
        '%s','%s','%s','%s',
        '%s','%s','%s','%s','%s',
        '%s','%s','%s','%s','%s',
        '%s','%s','%s',
        '%s','%s','%s','%s',
        '%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_istri_suami
SET 
	sutriNoKartu = '%s',
	sutriHubungan = '%s',
	sutriNama = '%s',
	sutriGelarDepan = '%s',
	sutriGelarBelakang = '%s',
	sutriTmpLahir = '%s',
	sutriTglLahir = '%s',
	sutriNoAkta = '%s',
	sutriAkta = '%s',
	sutriIdLain = '%s',
	sutriAgamaId = '%s',
	sutriPekerjaan = '%s',
	sutriKeterangan = '%s',
	sutriTunjanganStatus = '%s',
	sutriNpwp = '%s',
	sutriTglNpwp = '%s',
	sutriTelp = '%s',
	sutriNoAskes = '%s',
	sutriPendidikan = '%s',
	sutriTglNikah = '%s',
	sutriNoAktaNikah = '%s',
	sutriAktaNikah = '%s',
	sutriMeninggalStatus = '%s',
	sutriTglMeninggal = '%s',
	sutriNoAktaMeninggal = '%s',
	sutriAktaMeninggal = '%s',
	sutriCeraiStatus = '%s',
	sutriTglCerai = '%s',
	sutriNoAktaCerai = '%s',
	sutriAktaCerai = '%s'
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
