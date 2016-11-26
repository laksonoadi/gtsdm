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
	IF(mertuaMeninggalStatus='0','Yes','No') as 'meninggal_status',
	mertuaPendidikan as 'educ'
FROM
	sdm_mertua
WHERE
	mertuaPegId = '%s'
ORDER BY
  mertuaNama ASC
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
	mertuaMeninggalStatus as 'meninggal_status',
	mertuaPendidikan as 'educ'
FROM
	sdm_mertua
WHERE
	mertuaId = '%s'
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
   sdm_mertua
   (mertuaPegId,mertuaHubungan,mertuaNama,mertuaTmpLahir,mertuaTglLahir,
	mertuaPekerjaan,mertuaKeterangan,mertuaPendidikan,mertuaMeninggalStatus)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s')  
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
