<?php
$sql['get_count_data_notifikasi']="
SELECT 
	count(*) as total
FROM
	(%referensiQuery%) AS hasil
WHERE
	(nama LIKE '%s' OR nip LIKE '%s')
"; 

$sql['get_data_notifikasi']="
SELECT 
	*,
	verstatId as status,
	verstatName as status_label,
	verstatIcon as status_icon
FROM
	(%referensiQuery%) AS hasil
	LEFT JOIN sdm_verifikasi_ref_status ON verstatId=hasil.status_data
WHERE
	(nama LIKE '%s' OR nip LIKE '%s')
"; 

$sql['get_count_data_notifikasi_by_userid']="
SELECT 
	count(DISTINCT id_value) as total
FROM
	(%referensiQuery%) AS hasil
	LEFT JOIN pub_pegawai ON pegKodeResmi=nip
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
WHERE
	(nama LIKE '%s' OR nip LIKE '%s')
	AND (satkerLevel LIKE CONCAT('%s', '.%%') OR satkerId = '%s')
";  

$sql['get_data_notifikasi_by_userid']="
SELECT DISTINCT
	hasil.*,
	verstatId as status,
	verstatName as status_label,
	verstatIcon as status_icon
FROM
	(%referensiQuery%) AS hasil
	LEFT JOIN sdm_verifikasi_ref_status ON verstatId=hasil.status_data
	LEFT JOIN pub_pegawai ON pegKodeResmi=nip
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
WHERE
	(nama LIKE '%s' OR nip LIKE '%s')
	AND (satkerLevel LIKE CONCAT('%s', '.%%') OR satkerId = '%s')
";

$sql['get_count_data_pencarian_by_userid']="
SELECT 
	count(DISTINCT id_value) as total
FROM
	(%referensiQuery%) AS hasil
	LEFT JOIN pub_pegawai ON pegKodeResmi=nip
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
WHERE
	(nama LIKE '%s' OR nip LIKE '%s' OR LOWER(CAST(uraian AS CHAR)) LIKE '%s' OR judul LIKE '%s')
	AND (satkerId IN('%unitdata%')  OR	 
	satkerLevel IS NULL)
";  

$sql['get_data_pencarian_by_userid']="
SELECT DISTINCT
	hasil.*,
	CONCAT(verifikasiUrlData,pegId) as urldata,
	verstatId as status,
	verstatName as status_label,
	verstatIcon as status_icon
FROM
	(%referensiQuery%) AS hasil
	LEFt JOIN sdm_verifikasi_ref ON jenis_data=verifikasiNamaTabel
	LEFT JOIN sdm_verifikasi_ref_status ON verstatId=hasil.status_data
	LEFT JOIN pub_pegawai ON pegKodeResmi=nip
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
WHERE
	(nama LIKE '%s' OR nip LIKE '%s' OR LOWER(CAST(uraian AS CHAR)) LIKE '%s' OR judul LIKE '%s')
	AND ( satkerId IN('%unitdata%')  OR	 
	satkerLevel IS NULL)
";

$sql['get_data_notifikasi_by_id']="
SELECT 
	*,
	verstatId as status,
	verstatName as status_label,
	verstatIcon as status_icon
FROM
	(%referensiQuery%) AS hasil
	LEFT JOIN sdm_verifikasi_ref_status ON verstatId=hasil.status_data
WHERE
	hasil.id_value='%s'
";

$sql['get_jenis_data']="
SELECT 
   *
FROM
   sdm_verifikasi_ref
WHERE
	verifikasiIsAktif=1 AND verifikasiNamaTabel='%s'
";

$sql['get_all_jenis_data']="
SELECT 
   *
FROM
   sdm_verifikasi_ref
WHERE
	verifikasiIsAktif=1
";

$sql['get_data_pencarian_by_name'] = '
SELECT 
	*,
	TRIM(pegNama) as `pegNama`
FROM 
	pub_pegawai
WHERE
	1=1
	--where--
ORDER BY pegNama ASC
--limit--
';
$sql['count_data_pencarian_by_name'] = '
SELECT 
	COUNT(pegId) as `total`
FROM 
	pub_pegawai
WHERE
	1=1
	--where--
';


$sql['get_combo_status_data']="
SELECT 
   sdm_verifikasi_ref_status.*,
   verstatId as id,
   verstatName as name
FROM
   sdm_verifikasi_ref_status
";

$sql['get_combo_jenis_data']="
SELECT 
   verifikasiNamaTabel as id,
   verifikasiJudul as name
FROM
   sdm_verifikasi_ref
WHERE
	verifikasiIsAktif=1
";

$sql['get_id_referensi']="
SELECT 
   verifikasiId as id
FROM
   sdm_verifikasi_ref
WHERE
	verifikasiIsAktif=1 AND verifikasiNamaTabel='%s'
";

// DO-----------
$sql['do_update_status'] = "
INSERT INTO sdm_verifikasi_data
SET
	verdataVerifikasiId='%s',
	verdataValue='%s',
	verdataStatus='%s',
	verdataCreatedDate=now(),
	verdataCreatedUser='%s',
	verdataModifiedDate=now(),
	verdataModifiedUser='%s'
ON DUPLICATE KEY UPDATE
	verdataStatus=VALUES(verdataStatus),
	verdataModifiedDate=VALUES(verdataModifiedDate),
	verdataModifiedUser=VALUES(verdataModifiedUser)
";

$sql['get_satker_and_level']="
SELECT 
a.satkerId,
a.satkerLevel
FROM 
pub_satuan_kerja AS a
INNER JOIN gtfw_user_satuan_kerja ON 
userunitSatuanKerjaId=satkerId 
WHERE userunitUserId='%s' 
";

?>
