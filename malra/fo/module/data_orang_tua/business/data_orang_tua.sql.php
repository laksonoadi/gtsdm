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
	ortuTmpLahir as 'tmpt',
	ortuTglLahir as 'tgl_lahir',
	ortuPekerjaan as 'kerja',
	ortuKeterangan as 'ket',
	IF(ortuMeninggalStatus='0','Yes','No') as 'meninggal_status',
	ortuPendidikan as 'educ'
FROM
	sdm_orangtua
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
	ortuTmpLahir as 'tmpt',
	ortuTglLahir as 'tgl_lahir',
	ortuPekerjaan as 'kerja',
	ortuKeterangan as 'ket',
	ortuMeninggalStatus as 'meninggal_status',
	ortuPendidikan as 'educ'
FROM
	sdm_orangtua
WHERE
	ortuId = '%s'
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
   sdm_orangtua
   (ortuPegId,ortuHubungan,ortuNama,ortuTmpLahir,ortuTglLahir,
   ortuPekerjaan,ortuKeterangan,ortuPendidikan,ortuMeninggalStatus)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_orangtua
SET 
  ortuHubungan = '%s',
	ortuNama = '%s',
	ortuTmpLahir = '%s',
	ortuTglLahir = '%s',
	ortuPekerjaan = '%s',
	ortuKeterangan = '%s',
	ortuPendidikan = '%s',
	ortuMeninggalStatus = '%s'
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
