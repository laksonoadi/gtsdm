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
	mertuaTmpLahir as 'tmpt',
	mertuaTglLahir as 'tgl_lahir',
	mertuaPekerjaan as 'kerja',
	mertuaKeterangan as 'ket',
	mertuaPendidikan as 'educ',
	mertuaTelp as 'telp',
	IF(mertuaMeninggalStatus='0','Yes','No') as 'meninggal_status'
FROM
	sdm_mertua
WHERE
	mertuaPegId = '%s'
ORDER BY mertuaTglLahir DESC, mertuaNama ASC
";

$sql['get_data_mertua_verifikasi']="
SELECT
	mertuaId AS 'id',
	mertuaHubungan AS 'hub',
	mertuaNama AS 'nama',
	mertuaTmpLahir AS 'tmpt',
	mertuaTglLahir AS 'tgl_lahir',
	mertuaPekerjaan AS 'kerja',
	mertuaKeterangan AS 'ket',
	mertuaPendidikan AS 'educ',
	mertuaTelp AS 'telp',
	IF(mertuaMeninggalStatus='0','Yes','No') AS 'meninggal_status'
FROM
	sdm_mertua
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
	mertuaTmpLahir as 'tmpt',
	mertuaTglLahir as 'tgl_lahir',
	mertuaPekerjaan as 'kerja',
	mertuaKeterangan as 'ket',
	mertuaPendidikan as 'educ',
	mertuaTelp as 'telp',
	mertuaMeninggalStatus as 'meninggal_status'
FROM
	sdm_mertua
WHERE
	mertuaId = '%s'
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_mertua
   (mertuaPegId,mertuaHubungan,mertuaNama,mertuaTmpLahir,mertuaTglLahir,
	mertuaPekerjaan,mertuaKeterangan,mertuaPendidikan,mertuaTelp,mertuaMeninggalStatus)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_mertua
SET 
	mertuaHubungan = '%s',
	mertuaNama = '%s',
	mertuaTmpLahir = '%s',
	mertuaTglLahir = '%s',
	mertuaPekerjaan = '%s',
	mertuaKeterangan = '%s',
	mertuaPendidikan = '%s',
	mertuaTelp = '%s',
	mertuaMeninggalStatus = '%s'
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
