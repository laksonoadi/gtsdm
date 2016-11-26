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

$sql['get_data_anak']="
SELECT
	anakId as 'id',
	anakNama as 'nama',
	anakNmr as 'nmr',
	anakJnsKelamin as 'jenkel',
	anakTmpLahir as 'tmpt',
	anakTglLahir as 'tgl_lahir',
	anakPekerjaan as 'kerja',
	anakKeterangan as 'ket',
	IF(anakTunjanganStatus='0','Yes','No') as 'tunjang_status',
	IF(anakMeninggalStatus='0','Yes','No') as 'meninggal_status',
	IF(anakNikahStatus='0','Marriage','Single') as 'nikah',
	IF(anakStudiStatus='0','Yes','No') as 'studi',
	anakNpwp as 'npwp',
	anakPendidikan as 'educ'
FROM
	sdm_anak
WHERE
	anakPegId = '%s'
ORDER BY anakTglLahir DESC, anakNmr ASC
";

$sql['get_data_anak_det']="
SELECT
	anakId as 'id',
	anakNama as 'nama',
	anakNmr as 'nmr',
	anakJnsKelamin as 'jenkel',
	anakTmpLahir as 'tmpt',
	anakTglLahir as 'tgl_lahir',
	anakPekerjaan as 'kerja',
	anakKeterangan as 'ket',
	anakTunjanganStatus as 'tunjang_status',
	anakMeninggalStatus as 'meninggal_status',
	anakNikahStatus as 'nikah',
	anakStudiStatus as 'studi',
	anakNpwp as 'npwp',
	anakPendidikan as 'educ'
FROM
	sdm_anak
WHERE
	anakId = '%s'
";

$sql['get_data_ref_pendidikan']="
SELECT
	pendNama AS id,
	pendNama AS name
FROM
	pub_ref_pendidikan 
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_anak
   (anakPegId,anakNama,anakNmr,anakJnsKelamin,anakTmpLahir,anakTglLahir,
   anakPekerjaan,anakKeterangan,anakTunjanganStatus,anakMeninggalStatus,anakNikahStatus,anakStudiStatus,
   anakNpwp,anakPendidikan)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
       '%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_anak
SET 
	anakNama = '%s',
	anakNmr = '%s',
	anakJnsKelamin = '%s',
	anakTmpLahir = '%s',
	anakTglLahir = '%s',
	anakPekerjaan = '%s',
	anakKeterangan = '%s',
	anakTunjanganStatus = '%s',
	anakMeninggalStatus = '%s',
	anakNikahStatus = '%s',
	anakStudiStatus = '%s',
	anakNpwp = '%s',
	anakPendidikan = '%s'
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
