<?php
$sql['get_combo_unit_kerja'] = "
SELECT
  satkerId as id,
  satkerNama as name
FROM 
  pub_satuan_kerja
";

$sql['get_combo_pangkat_golongan'] = "
SELECT
	*
FROM
	(SELECT
	  pktgolrId as id,
	  if(pktgolrTingkat=0,'0',pktgolrId) as name,
	  pktgolrTingkat as tingkat,
	  pktgolrUrut as urut
	FROM 
	  sdm_ref_pangkat_golongan
	ORDER BY pktgolrTingkat) as a
GROUP BY name
ORDER BY urut DESC
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
	golongan,
	unit_kerja,
	SUM(jumlah) as jumlah
FROM
	(SELECT
		pegKelamin as jenis_kelamin,
		ifnull(pktgolrId,99999) as golongan,
		ifnull(satkerId,99999) as unit_kerja,
		count(DISTINCT pegId) as jumlah
	FROM
		pub_pegawai
		LEFT JOIN 
			(select * from sdm_pangkat_golongan order by pktgolStatus, pktgolId ASC) as why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
		LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
		LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
		LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
		LEFT JOIN sdm_jabatan_fungsional ON jbtnPegKode=PegId AND jbtnStatus='Aktif'
		LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=jbtnJabfungrId
		LEFT JOIN pub_ref_jabatan_fungsional_jenis ON jabfungrJenisrId=jabfungjenisrId
		INNER JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
	WHERE
	  pegId>0
	  %jenis_pegawai%
	  %jabatan_fungsional%
	GROUP BY satkerId,pktgolrId, pegKelamin
	) as a
GROUP BY unit_kerja,golongan, jenis_kelamin
"; 

?>
