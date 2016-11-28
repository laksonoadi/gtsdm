<?php
$sql['get_combo_unit_kerja'] = "
SELECT
  satkerId as id,
  satkerNama as name
FROM 
  pub_satuan_kerja
";

$sql['get_combo_status_pegawai'] = "
SELECT
  statrId AS id,
  statrPegawai AS name
FROM 
  sdm_ref_status_pegawai
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
  statuspeg,
  unit_kerja,
  SUM(jumlah) AS jumlah
FROM
  (SELECT
  	pegKelamin AS jenis_kelamin,
  	IFNULL(statrId,99999) AS statuspeg,
		IFNULL(satkerId,99999) AS unit_kerja,
  	COUNT(DISTINCT pegId) AS jumlah
  FROM
  	pub_pegawai
  	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
  	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
  	LEFT JOIN sdm_jabatan_fungsional ON jbtnPegKode=PegId AND jbtnStatus='Aktif'
  	LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=jbtnJabfungrId
  	LEFT JOIN pub_ref_jabatan_fungsional_jenis ON jabfungrJenisrId=jabfungjenisrId
  	INNER JOIN sdm_ref_status_pegawai ON pegStatrId=statrId
  WHERE
  	pegId>0
  	%jabatan_fungsional%
  GROUP BY satkerId,statrId, pegKelamin) AS a
GROUP BY unit_kerja, statuspeg, jenis_kelamin
"; 

?>
