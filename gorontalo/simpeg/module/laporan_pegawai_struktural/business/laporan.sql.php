<?php
$sql['get_combo_unit_kerja'] = "
SELECT
  satkerId as id,
  satkerNama as name
FROM 
  pub_satuan_kerja
";

$sql['get_combo_struktural'] = "
SELECT
	  jabstrukrId AS id,
	  jabstrukrNama AS name
FROM 
	  sdm_ref_jabatan_struktural
";

$sql['get_combo_jenis_pegawai'] = "
SELECT
  jnspegrId as id,
  jnspegrNama as name
FROM 
  sdm_ref_jenis_pegawai
";

$sql['get_combo_jabatan_struktural'] = "
SELECT
  jabstrukrId AS id,
  jabstrukrNama AS name
FROM 
  sdm_ref_jabatan_struktural
";

$sql['get_data_pegawai']="
SELECT
  jenis_kelamin,
  struktural,
  unit_kerja,
  SUM(jumlah) AS jumlah
FROM
  (SELECT
  	pegKelamin AS jenis_kelamin,
  	IFNULL(jabstrukrId,99999) AS struktural,
		IFNULL(satkerId,99999) AS unit_kerja,
  	COUNT(DISTINCT pegId) AS jumlah
  FROM
  	pub_pegawai
  	LEFT JOIN 
  			(SELECT * FROM (
  					SELECT * FROM 
  						sdm_pendidikan 
  						INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
  					WHERE pddkStatusTamat='Selesai'
  					ORDER BY pendPendkelId DESC
  			) AS b GROUP BY pddkPegKode ) AS b
  			ON pddkPegKode=pegId
  	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
  	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
  	LEFT JOIN sdm_jabatan_struktural ON jbtnPegKode=PegId AND jbtnStatus='Aktif'
  	LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=jbtnJabstrukrId
  	INNER JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
  WHERE
  	pegId>0
  	%jenis_pegawai% 
  GROUP BY satkerId,jabstrukrId, pegKelamin) AS a
GROUP BY unit_kerja, struktural, jenis_kelamin
"; 

?>
