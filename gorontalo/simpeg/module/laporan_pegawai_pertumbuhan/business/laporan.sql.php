<?php
$sql['get_combo_unit_kerja'] = "
SELECT
  satkerId as id,
  satkerNama as name
FROM 
  pub_satuan_kerja
";

$sql['get_combo_unit_kerja_like'] = "
SELECT
  satkerId AS id,
  ROUND((LENGTH(satkerLevel)-LENGTH(REPLACE(satkerLevel, '.', '')))/LENGTH('.')) AS level,
  satkerNama AS name
FROM 
  pub_satuan_kerja
WHERE 1=1 %filter%
ORDER BY satkerLevel
";

$sql['get_combo_pendidikan'] = "
SELECT
	  pendId as id,
	  pendNama as name
FROM 
	  pub_ref_pendidikan
ORDER BY pendPendkelId DESC, pendId DESC
";

$sql['get_combo_jenis_pegawai'] = "
SELECT
  jnspegrId as id,
  jnspegrNama as name
FROM 
  sdm_ref_jenis_pegawai
";

$sql['get_combo_jabatan_fungsional'] = "
SELECT
  jabfungjenisrId as id,
  jabfungJenis as name
FROM 
  pub_ref_jabatan_fungsional_jenis
";

$sql['get_data_pegawai']="
SELECT
  	pegKelamin as jenis_kelamin,
  	YEAR(pegTglMasukInstitusi) as tahun_masuk,
	  ifnull(satkerId,99999) as unit_kerja,
  	count(distinct pegId) as jumlah
FROM
  	pub_pegawai
  	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
  	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
  	LEFT JOIN sdm_jabatan_fungsional ON jbtnPegKode=PegId AND jbtnStatus='Aktif'
  	LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=jbtnJabfungrId
  	LEFT JOIN pub_ref_jabatan_fungsional_jenis ON jabfungrJenisrId=jabfungjenisrId
    LEFT JOIN sdm_verifikasi_data ON pegId=verdataValue 
WHERE
  	pegId<>-1 AND verdataStatus='3' AND verdataVerifikasiId='19'
    AND (satkerLevel LIKE CONCAT('%s', '.%%') OR satkerId = '%s')
  	%jenis_pegawai%
  	%jabatan_fungsional%
GROUP BY satkerId,YEAR(pegTglMasukInstitusi),pegKelamin
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
