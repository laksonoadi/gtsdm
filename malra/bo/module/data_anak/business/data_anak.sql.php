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
   pegAlamat as 'alamat',
   pegFoto as 'foto'
FROM 
   pub_pegawai
WHERE 
   pegId = %s
";

$sql['get_data_anak']="
SELECT
	anakId as 'id',
	anakNama as 'nama',
	anakGelarDepan as 'gelar_depan',
	anakGelarBelakang as 'gelar_belakang',
	anakNmr as 'nmr',
	anakJnsKelamin as 'jenkel',
	anakTmpLahir as 'tmpt',
	anakTglLahir as 'tgl_lahir',
	anakNoAkta as 'no_akta',
	anakAkta as 'akta',
	anakAgamaId as 'agama_id',
	b.agmNama as 'agama',
	anakAlamat as 'alamat',
	anakPekerjaan as 'kerja',
	anakKeterangan as 'ket',
	anakNoAskes as 'no_askes',
	IF(anakTunjanganStatus='0','Ya','Tidak') as 'tunjang_status',
	anakTunjanganStatus as 'tunjang_status_ori',
	IF(anakMeninggalStatus='0','Ya','Tidak') as 'meninggal_status',
	anakMeninggalStatus as 'meninggal_status_ori',
	anakTglMeninggal as 'tgl_meninggal',
	anakNoAktaMeninggal as 'no_akta_meninggal',
	anakAktaMeninggal as 'akta_meninggal',
	IF(anakNikahStatus='0','Menikah','Lajang') as 'nikah',
	anakNikahStatus as 'nikah_ori',
	IF(anakStudiStatus='0','Ya','Tidak') as 'studi',
	anakStudiStatus as 'studi_ori',
	anakNpwp as 'npwp',
	anakTglNpwp as 'tgl_npwp',
	anakTelp as 'telp',
	anakPendidikan as 'educ'
FROM
	sdm_anak
	LEFT JOIN pub_ref_agama b ON anakAgamaId = b.agmId
WHERE
	anakPegId = '%s'
ORDER BY anakTglLahir DESC, anakNmr ASC
";

$sql['get_data_anak_verifikasi']="
SELECT
	anakId as 'id',
	anakNama as 'nama',
	anakGelarDepan as 'gelar_depan',
	anakGelarBelakang as 'gelar_belakang',
	anakNmr as 'nmr',
	anakJnsKelamin as 'jenkel',
	anakTmpLahir as 'tmpt',
	anakTglLahir as 'tgl_lahir',
	anakNoAkta as 'no_akta',
	anakAkta as 'akta',
	anakAgamaId as 'agama_id',
	b.agmNama as 'agama',
	anakAlamat as 'alamat',
	anakPekerjaan as 'kerja',
	anakKeterangan as 'ket',
	anakNoAskes as 'no_askes',
	IF(anakTunjanganStatus='0','Ya','Tidak') as 'tunjang_status',
	anakTunjanganStatus as 'tunjang_status_ori',
	IF(anakMeninggalStatus='0','Ya','Tidak') as 'meninggal_status',
	anakMeninggalStatus as 'meninggal_status_ori',
	anakTglMeninggal as 'tgl_meninggal',
	anakNoAktaMeninggal as 'no_akta_meninggal',
	anakAktaMeninggal as 'akta_meninggal',
	IF(anakNikahStatus='0','Menikah','Lajang') as 'nikah',
	anakNikahStatus as 'nikah_ori',
	IF(anakStudiStatus='0','Ya','Tidak') as 'studi',
	anakStudiStatus as 'studi_ori',
	anakNpwp as 'npwp',
	anakTglNpwp as 'tgl_npwp',
	anakTelp as 'telp',
	anakPendidikan as 'educ'
FROM
	sdm_anak
	LEFT JOIN pub_ref_agama b ON anakAgamaId = b.agmId
	INNER JOIN sdm_verifikasi_data vd ON vd.`verdataValue`=anakId AND vd.`verdataStatus`='3' AND vd.`verdataVerifikasiId`='12'
WHERE
	anakPegId = '%s'
ORDER BY anakTglLahir DESC, anakNmr ASC";

$sql['get_data_anak_det']="
SELECT
	anakId as 'id',
	anakNama as 'nama',
	anakGelarDepan as 'gelar_depan',
	anakGelarBelakang as 'gelar_belakang',
	anakNmr as 'nmr',
	anakJnsKelamin as 'jenkel',
	anakTmpLahir as 'tmpt',
	anakTglLahir as 'tgl_lahir',
	anakNoAkta as 'no_akta',
	anakAkta as 'akta',
	anakAgamaId as 'agama_id',
	b.agmNama as 'agama',
	anakAlamat as 'alamat',
	anakPekerjaan as 'kerja',
	anakKeterangan as 'ket',
	anakNoAskes as 'no_askes',
	IF(anakTunjanganStatus='0','Ya','Tidak') as 'tunjang_status',
	anakTunjanganStatus as 'tunjang_status_ori',
	IF(anakMeninggalStatus='0','Ya','Tidak') as 'meninggal_status',
	anakMeninggalStatus as 'meninggal_status_ori',
	anakTglMeninggal as 'tgl_meninggal',
	anakNoAktaMeninggal as 'no_akta_meninggal',
	anakAktaMeninggal as 'akta_meninggal',
	IF(anakNikahStatus='0','Menikah','Lajang') as 'nikah',
	anakNikahStatus as 'nikah_ori',
	IF(anakStudiStatus='0','Ya','Tidak') as 'studi',
	anakStudiStatus as 'studi_ori',
	anakNpwp as 'npwp',
	anakTglNpwp as 'tgl_npwp',
	anakTelp as 'telp',
	anakPendidikan as 'educ'
FROM
	sdm_anak
	LEFT JOIN pub_ref_agama b ON anakAgamaId = b.agmId
WHERE
	anakId = '%s'
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
   sdm_anak
   (anakPegId,anakNama,anakGelarDepan,anakGelarBelakang,
   anakNmr,anakJnsKelamin,
   anakTmpLahir,anakTglLahir,anakNoAkta,anakAkta,
   anakAgamaId,anakAlamat,anakPekerjaan,anakPendidikan,
   anakKeterangan,anakTunjanganStatus,anakNikahStatus,anakStudiStatus,
   anakNpwp,anakTglNpwp,anakTelp,anakNoAskes,
   anakMeninggalStatus,anakTglMeninggal,anakNoAktaMeninggal,anakAktaMeninggal)
VALUES('%s','%s','%s','%s',
        '%s','%s',
        '%s','%s','%s','%s',
        '%s','%s','%s','%s',
        '%s','%s','%s','%s',
        '%s','%s','%s','%s',
       '%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_anak
SET 
	anakNama = '%s',
	anakGelarDepan = '%s',
	anakGelarBelakang = '%s',
	anakNmr = '%s',
	anakJnsKelamin = '%s',
	anakTmpLahir = '%s',
	anakTglLahir = '%s',
	anakNoAkta = '%s',
	anakAkta = '%s',
	anakAgamaId = '%s',
	anakAlamat = '%s',
	anakPekerjaan = '%s',
	anakPendidikan = '%s',
	anakKeterangan = '%s',
	anakTunjanganStatus = '%s',
	anakNikahStatus = '%s',
	anakStudiStatus = '%s',
	anakNpwp = '%s',
	anakTglNpwp = '%s',
	anakTelp = '%s',
	anakNoAskes = '%s',
	anakMeninggalStatus = '%s',
	anakTglMeninggal = '%s',
	anakNoAktaMeninggal = '%s',
	anakAktaMeninggal = '%s'
WHERE 
	anakId = '%s'
";

$sql['do_delete'] = "
DELETE FROM
   sdm_anak
WHERE 
   anakId = %s  
";
?>
