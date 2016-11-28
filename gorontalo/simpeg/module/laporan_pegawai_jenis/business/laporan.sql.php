<?php
$sql['get_combo_unit_kerja'] = "
SELECT
  satkerId as id,
  satkerNama as name
FROM 
  pub_satuan_kerja
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
  jenis_kelamin,
  jenis,
  unit_kerja,
  SUM(jumlah) as jumlah
FROM
  (SELECT
  	pegKelamin as jenis_kelamin,
  	ifnull(jnspegrId,99999) as jenis,
		ifnull(satkerId,99999) as unit_kerja,
  	count(DISTINCT pegId) as jumlah
  FROM
  	pub_pegawai
  	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
  	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
  	LEFT JOIN sdm_jabatan_fungsional ON jbtnPegKode=PegId AND jbtnStatus='Aktif'
  	LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=jbtnJabfungrId
  	LEFT JOIN pub_ref_jabatan_fungsional_jenis ON jabfungrJenisrId=jabfungjenisrId
  	INNER JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
  WHERE
  	pegId>0
  	%jabatan_fungsional%
  GROUP BY satkerId,jnspegrId, pegKelamin) as a
GROUP BY unit_kerja, jenis, jenis_kelamin
"; 

?>
