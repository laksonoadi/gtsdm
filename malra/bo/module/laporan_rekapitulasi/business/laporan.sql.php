<?php
$sql['get_combo_unit_kerja'] = "
SELECT
  satkerId AS id,
  ROUND((LENGTH(satkerLevel)-LENGTH(REPLACE(satkerLevel, '.', '')))/LENGTH('.')) AS level,
  satkerNama AS name
FROM 
  pub_satuan_kerja
WHERE 1=1 %filter%
    AND (satkerId = '%s' OR satkerLevel LIKE CONCAT('%s', '.%%'))
ORDER BY satkerLevel
";

$sql['get_combo_unit_kerja_'] = "
SELECT
  satkerId as id,
  satkerNama as name
FROM 
  pub_satuan_kerja
";

$sql['get_combo_status'] = "
SELECT
  statrId AS id,
  statrPegawai AS name
FROM 
  sdm_ref_status_pegawai
WHERE 1=1 %filter%
";

$sql['get_combo_jenisfungsional'] = "
SELECT
  jabfungjenisrId as id,
  jabfungJenis as name
FROM 
  pub_ref_jabatan_fungsional_jenis jfrs
WHERE 1=1 %filter%
";

$sql['get_combo_golongan'] = "
SELECT
	*
FROM
	(SELECT
	  pktgolrId as id,
	  pktgolrId,
	  if(pktgolrTingkat=0,'0',pktgolrId) as name,
	  pktgolrTingkat as tingkat,
	  pktgolrUrut as urut
	FROM 
	  sdm_ref_pangkat_golongan
	ORDER BY pktgolrTingkat) as a
WHERE 1=1 %filter%
GROUP BY name
ORDER BY urut DESC
";

$sql['get_combo_fungsional'] = "
SELECT
	jabfungrId as id,
	jabfungrNama as name
FROM 
	pub_ref_jabatan_fungsional jfr
WHERE 1=1 %filter%
ORDER BY jabfungrTingkat DESC
";

$sql['get_combo_struktural'] = "
SELECT
	  jabstrukrId AS id,
	  jabstrukrNama AS name
FROM 
	  sdm_ref_jabatan_struktural
ORDER BY jabstrukrTingkat
";

$sql['get_combo_eselon'] = "
SELECT '0' as id, '0' as name UNION
SELECT 'IA' as id, 'IA' as name UNION
SELECT 'IB' as id, 'IB' as name UNION
SELECT 'IIA' as id, 'IIA' as name UNION
SELECT 'IIB' as id, 'IIB' as name UNION
SELECT 'IIIA' as id, 'IIIA' as name UNION
SELECT 'IIIB' as id, 'IIIB' as name UNION
SELECT 'IVA' as id, 'IVA' as name UNION
SELECT 'IVB' as id, 'IVB' as name
";

$sql['get_combo_pendidikan'] = "
SELECT
	  pendId as id,
	  pendNama as name
FROM 
	  pub_ref_pendidikan
WHERE 1=1 %filter%
ORDER BY pendPendkelId DESC, pendId DESC
";

$sql['get_combo_spendidikan'] = "
SELECT 'S1' as id, 'S1' as name UNION
SELECT 'S1S2' as id, 'S1 Sedang S2' as name UNION
SELECT 'S2' as id, 'S2' as name UNION
SELECT 'S2S3' as id, 'S2 Sedang S3' as name UNION
SELECT 'S3' as id, 'S3' as name
";

$sql['get_combo_jenis'] = "
SELECT
  jnspegrId as id,
  jnspegrNama as name
FROM 
  sdm_ref_jenis_pegawai
WHERE 1=1 %filter%
";

$sql['get_combo_agama'] = "
SELECT
  agmId as id,
  agmNama as name
FROM 
  pub_ref_agama
";

$sql['get_combo_statnikah'] = "
SELECT
  statnkhId as id,
  statnkhNama as name
FROM 
  pub_ref_status_nikah
";

$sql['get_combo_usia'] = "
SELECT 'A' as id, '20 ke bawah' as name UNION
SELECT 'B' as id, '21 s/d 30' as name UNION
SELECT 'C' as id, '31 s/d 40' as name UNION
SELECT 'D' as id, '41 s/d 50' as name UNION
SELECT 'E' as id, '51 s/d 60' as name UNION
SELECT 'F' as id, '61 ke atas' as name
";

$sql['get_combo_masakerja'] = "
SELECT 'A' as id, '0 s/d 10' as name UNION
SELECT 'B' as id, '11 s/d 20' as name UNION
SELECT 'C' as id, '21 s/d 30' as name UNION
SELECT 'D' as id, '31 s/d 40' as name UNION
SELECT 'E' as id, '41 s/d 50' as name UNION
SELECT 'F' as id, '51 s/d 60' as name UNION
SELECT 'G' as id, '61 ke atas' as name
";

$sql['get_combo_sertifikasi'] = "
SELECT
	srtfkTahun as id,
	srtfkTahun as name
FROM
	sdm_sertifikasi
";

$sql['get_combo_unit'] = "
SELECT '1' as id, 'Jumlah' as name
";

$sql['get_combo_jabatan_fungsional'] = "
SELECT
  jabfungjenisrId as id,
  jabfungJenis as name
FROM 
  pub_ref_jabatan_fungsional_jenis
";

$sql['get_statistik_pegawai']="
SELECT
  IFNULL(jenis_kelamin,'X') AS jenis_kelamin,
  %variabel_tampil%,
  unit_kerja,
  SUM(jumlah) as jumlah
FROM
  (SELECT
  	pegKelamin as jenis_kelamin,
  	IFNULL(%variabel%,99999) AS nama,
	ifnull(satkerId,99999) as unit_kerja,
  	count(DISTINCT pegId) as jumlah
  FROM
		pub_pegawai
		LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
		LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
		%join%
  WHERE 1=1
  	 AND (satkerId = '%s' OR satkerLevel LIKE CONCAT('%s', '.%%'))
  	%filter%
  GROUP BY satkerId,%variabel_group% pegKelamin) as a
GROUP BY unit_kerja, nama, jenis_kelamin
";

$sql['join_golongan']="
LEFT JOIN (SELECT * FROM sdm_pangkat_golongan WHERE pktgolStatus='Aktif' ORDER BY pktgolStatus, pktgolId ASC) AS why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
";

$sql['id_golongan']="
	pktgolrId
";

$sql['join_unit']="

";

$sql['id_unit']="
	1
";

$sql['join_struktural']="
LEFT JOIN sdm_jabatan_struktural js ON js.jbtnPegKode=PegId AND js.jbtnStatus='Aktif'
LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=js.jbtnJabstrukrId
";

$sql['id_struktural']="
	jabstrukrId
";

$sql['join_eselon']="
LEFT JOIN sdm_jabatan_struktural js ON js.jbtnPegKode=PegId AND js.jbtnStatus='Aktif'
";

$sql['id_eselon']="
	js.jbtnEselon
";

$sql['join_fungsional']="
LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=PegId AND jf.jbtnStatus='Aktif'
LEFT JOIN pub_ref_jabatan_fungsional jfr ON jfr.jabfungrId=jf.jbtnJabfungrId
LEFT JOIN pub_ref_jabatan_fungsional_jenis jfrs ON jfr.jabfungrJenisrId=jfrs.jabfungjenisrId
";

$sql['id_fungsional']="
	jfr.jabfungrId
";

$sql['join_jenisfungsional']="
LEFT JOIN sdm_jabatan_fungsional jf2 ON jf2.jbtnPegKode=PegId AND jf2.jbtnStatus='Aktif'
LEFT JOIN pub_ref_jabatan_fungsional jfr2 ON jfr2.jabfungrId=jf2.jbtnJabfungrId
LEFT JOIN pub_ref_jabatan_fungsional_jenis jfrs2 ON jfr2.jabfungrJenisrId=jfrs2.jabfungjenisrId
";

$sql['id_jenisfungsional']="
	jfrs2.jabfungjenisrId
";

$sql['join_jenis']="
LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
";

$sql['id_jenis']="
	jnspegrId
";

$sql['join_status']="		
	LEFT JOIN sdm_status_pegawai ssp ON ssp.statpegPegId=pegId AND statpegAktif = 'Aktif'
	LEFT JOIN sdm_ref_status_pegawai ON statpegStatrId=statrId
";

$sql['id_status']="
	statrId
";

$sql['join_pendidikan']="
LEFT JOIN 
		(SELECT * FROM (
  			SELECT * FROM 
  				sdm_pendidikan 
  				INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
  			WHERE pddkStatusTamat='Selesai'
  				ORDER BY pendPendkelId DESC
  		) AS b GROUP BY pddkPegKode ) AS b
  		ON pddkPegKode=pegId
";

$sql['id_pendidikan']="
	pendId
";

$sql['join_spendidikan']="
LEFT JOIN 
		(SELECT
			CONCAT(aa.pendNama,IFNULL(bb.pendNama,'')) AS pendNama,
			aa.pddkPegKode as pddkPegKode
		 FROM 
			(SELECT 
				pendNama,
				pddkPegKode 
			 FROM 
				sdm_pendidikan 
				INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
			 WHERE pddkStatusTamat='Selesai'
			 ORDER BY pendPendkelId DESC) AS aa 
			LEFT JOIN
			(SELECT 
				pendNama,
				pddkPegKode 
			 FROM 
				sdm_pendidikan 
				INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
			 WHERE pddkStatusTamat<>'Selesai'
			 ORDER BY pendPendkelId DESC) AS bb ON aa.pddkPegKode=bb.pddkPegKode
			GROUP BY aa.pddkPegKode) AS b
  		ON pddkPegKode=pegId
";

$sql['id_spendidikan']="
	pendNama
";

$sql['join_agama']="
LEFT JOIN pub_ref_agama ON agmId=pegAgamaId
";

$sql['id_agama']="
	pegAgamaId
";

$sql['join_statnikah']="
LEFT JOIN pub_ref_status_nikah ON statnkhId=pegStatnikahId
";

$sql['id_statnikah']="
	pegStatnikahId
";

$sql['id_usia']="
	IF(YEAR(pegTglLahir)='0000' OR MONTH(pegTglLahir)='00' OR DATE(pegTglLahir)='00',99999,ROUND(DATEDIFF(NOW(),pegTglLahir)/365))
";

$sql['id_masakerja']="
	IF(YEAR(pegTglMasukInstitusi)='0000' OR MONTH(pegTglMasukInstitusi)='00' OR DATE(pegTglMasukInstitusi)='00',99999,ROUND(DATEDIFF(NOW(),pegTglMasukInstitusi)/365))
";

$sql['tampil_masakerja']="
CASE
    WHEN nama BETWEEN 0 AND 10 THEN 'A'
	WHEN nama BETWEEN 11 AND 20 THEN 'B'
    WHEN nama BETWEEN 21 AND 30 THEN 'C'
    WHEN nama BETWEEN 31 AND 40 THEN 'D'
    WHEN nama BETWEEN 41 AND 50 THEN 'E'
    WHEN nama BETWEEN 51 AND 60 THEN 'F'
    WHEN nama BETWEEN 61 AND 99998 THEN 'G'
    ELSE '99999'
END as nama
";

$sql['tampil_usia']="
CASE
    WHEN (nama <=20) THEN 'A'
    WHEN nama BETWEEN 21 AND 30 THEN 'B'
    WHEN nama BETWEEN 31 AND 40 THEN 'C'
    WHEN nama BETWEEN 41 AND 50 THEN 'D'
    WHEN nama BETWEEN 51 AND 60 THEN 'E'
    WHEN nama BETWEEN 61 AND 99998 THEN 'F'
    ELSE '99999'
END as nama
";

$sql['join_sertifikasi']="
INNER JOIN sdm_sertifikasi_detail ON srtfkdetPegId=pegId AND srtfkdetHasilAkhir='LULUS' 
";

$sql['id_sertifikasi']="
	srtfkdetTahun
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
$sql['get_satker_by_id']="
SELECT 
a.satkerId,
a.satkerLevel
FROM 
pub_satuan_kerja AS a
WHERE satkerId='%s' 
";

$sql['get_unit_list']="
SELECT satkerLevel AS unit_id FROM pub_satuan_kerja WHERE satkerId='%s' 
";

$sql['get_unit_list_data']="
SELECT satkerId AS unit_id FROM pub_satuan_kerja WHERE satkerLevel LIKE %s
";

/*
$sql['get_data_pegawai_jenisfungsional']="
SELECT
  IFNULL(jenis_kelamin,'X') as jenis_kelamin,
  nama,
  unit_kerja,
  SUM(jumlah) AS jumlah
FROM
  (SELECT
		pegKelamin AS jenis_kelamin,
		IFNULL(jabfungjenisrId,99999) AS nama,
		IFNULL(satkerId,99999) AS unit_kerja,
		COUNT(DISTINCT pegId) AS jumlah
	FROM
		pub_pegawai
		LEFT JOIN (SELECT * FROM sdm_pangkat_golongan WHERE pktgolStatus='Aktif' ORDER BY pktgolStatus, pktgolId ASC) AS why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
		LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
		
		LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
		LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
		
		LEFT JOIN sdm_jabatan_struktural js ON js.jbtnPegKode=PegId AND js.jbtnStatus='Aktif'
		LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=js.jbtnJabstrukrId
		
		LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=PegId AND jf.jbtnStatus='Aktif'
		LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=jf.jbtnJabfungrId
		LEFT JOIN pub_ref_jabatan_fungsional_jenis ON jabfungrJenisrId=jabfungjenisrId
		
		LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
		
		LEFT JOIN sdm_ref_status_pegawai ON pegStatrId=statrId
		
		LEFT JOIN 
  			(SELECT * FROM (
  					SELECT * FROM 
  						sdm_pendidikan 
  						INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
  					WHERE pddkStatusTamat='Selesai'
  					ORDER BY pendPendkelId DESC
  			) AS b GROUP BY pddkPegKode ) AS b
  			ON pddkPegKode=pegId
  WHERE
  	pegId>0
  	%filter%
  GROUP BY satkerId,jabfungjenisrId, pegKelamin) AS a
GROUP BY unit_kerja, nama, jenis_kelamin
";

$sql['get_data_pegawai_status']="
SELECT
  IFNULL(jenis_kelamin,'X') as jenis_kelamin,
  nama,
  unit_kerja,
  SUM(jumlah) AS jumlah
FROM
  (SELECT
  	pegKelamin AS jenis_kelamin,
  	IFNULL(statrId,99999) AS nama,
	IFNULL(satkerId,99999) AS unit_kerja,
  	COUNT(DISTINCT pegId) AS jumlah
  FROM
		pub_pegawai
		LEFT JOIN (SELECT * FROM sdm_pangkat_golongan WHERE pktgolStatus='Aktif' ORDER BY pktgolStatus, pktgolId ASC) AS why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
		LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
		
		LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
		LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
		
		LEFT JOIN sdm_jabatan_struktural js ON js.jbtnPegKode=PegId AND js.jbtnStatus='Aktif'
		LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=js.jbtnJabstrukrId
		
		LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=PegId AND jf.jbtnStatus='Aktif'
		LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=jf.jbtnJabfungrId
		LEFT JOIN pub_ref_jabatan_fungsional_jenis ON jabfungrJenisrId=jabfungjenisrId
		
		LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
		
		LEFT JOIN sdm_ref_status_pegawai ON pegStatrId=statrId
		
		LEFT JOIN 
  			(SELECT * FROM (
  					SELECT * FROM 
  						sdm_pendidikan 
  						INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
  					WHERE pddkStatusTamat='Selesai'
  					ORDER BY pendPendkelId DESC
  			) AS b GROUP BY pddkPegKode ) AS b
  			ON pddkPegKode=pegId
  WHERE
  	pegId>0
  	%filter%
  GROUP BY satkerId,statrId, pegKelamin) AS a
GROUP BY unit_kerja, nama, jenis_kelamin
"; 

$sql['get_data_pegawai_golongan']="
SELECT
	IFNULL(jenis_kelamin,'X') AS jenis_kelamin,
	nama,
	unit_kerja,
	SUM(jumlah) AS jumlah
FROM
	(SELECT
		pegKelamin AS jenis_kelamin,
		IFNULL(pktgolrId,99999) AS nama,
		IFNULL(satkerId,99999) AS unit_kerja,
		COUNT(DISTINCT pegId) AS jumlah
	FROM
		pub_pegawai
		LEFT JOIN (SELECT * FROM sdm_pangkat_golongan WHERE pktgolStatus='Aktif' ORDER BY pktgolStatus, pktgolId ASC) AS why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
		LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
		
		LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
		LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
		
		LEFT JOIN sdm_jabatan_struktural js ON js.jbtnPegKode=PegId AND js.jbtnStatus='Aktif'
		LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=js.jbtnJabstrukrId
		
		LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=PegId AND jf.jbtnStatus='Aktif'
		LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=jf.jbtnJabfungrId
		LEFT JOIN pub_ref_jabatan_fungsional_jenis ON jabfungrJenisrId=jabfungjenisrId
		
		LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
		
		LEFT JOIN sdm_ref_status_pegawai ON pegStatrId=statrId
		
		LEFT JOIN 
  			(SELECT * FROM (
  					SELECT * FROM 
  						sdm_pendidikan 
  						INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
  					WHERE pddkStatusTamat='Selesai'
  					ORDER BY pendPendkelId DESC
  			) AS b GROUP BY pddkPegKode ) AS b
  			ON pddkPegKode=pegId
	WHERE
	  pegId>0
	  %filter%
	GROUP BY satkerId,pktgolrId, pegKelamin
	) AS a
GROUP BY unit_kerja,nama, jenis_kelamin
"; 

$sql['get_data_pegawai_fungsional']="
SELECT
  IFNULL(jenis_kelamin,'X') AS jenis_kelamin,
  nama,
  unit_kerja,
  SUM(jumlah) as jumlah
FROM
  (SELECT
  	pegKelamin as jenis_kelamin,
  	ifnull(jabfungrId,99999) as nama,
	ifnull(satkerId,99999) as unit_kerja,
  	count(DISTINCT pegId) as jumlah
  FROM
	  	pub_pegawai
		LEFT JOIN (SELECT * FROM sdm_pangkat_golongan WHERE pktgolStatus='Aktif' ORDER BY pktgolStatus, pktgolId ASC) AS why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
		LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
		
		LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
		LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
		
		LEFT JOIN sdm_jabatan_struktural js ON js.jbtnPegKode=PegId AND js.jbtnStatus='Aktif'
		LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=js.jbtnJabstrukrId
		
		LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=PegId AND jf.jbtnStatus='Aktif'
		LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=jf.jbtnJabfungrId
		LEFT JOIN pub_ref_jabatan_fungsional_jenis ON jabfungrJenisrId=jabfungjenisrId
		
		LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
		
		LEFT JOIN sdm_ref_status_pegawai ON pegStatrId=statrId
		
		LEFT JOIN 
  			(SELECT * FROM (
  					SELECT * FROM 
  						sdm_pendidikan 
  						INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
  					WHERE pddkStatusTamat='Selesai'
  					ORDER BY pendPendkelId DESC
  			) AS b GROUP BY pddkPegKode ) AS b
  			ON pddkPegKode=pegId
  WHERE
  	pegId>0
  	%filter%
  GROUP BY satkerId,jabfungrId, pegKelamin) as a
GROUP BY unit_kerja, nama, jenis_kelamin
";

$sql['get_data_pegawai_struktural']="
SELECT
  IFNULL(jenis_kelamin,'X') AS jenis_kelamin,
  nama,
  unit_kerja,
  SUM(jumlah) AS jumlah
FROM
  (SELECT
  	pegKelamin AS jenis_kelamin,
  	IFNULL(jabstrukrId,99999) AS nama,
	IFNULL(satkerId,99999) AS unit_kerja,
  	COUNT(DISTINCT pegId) AS jumlah
  FROM
		pub_pegawai
		LEFT JOIN (SELECT * FROM sdm_pangkat_golongan WHERE pktgolStatus='Aktif' ORDER BY pktgolStatus, pktgolId ASC) AS why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
		LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
		
		LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
		LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
		
		LEFT JOIN sdm_jabatan_struktural js ON js.jbtnPegKode=PegId AND js.jbtnStatus='Aktif'
		LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=js.jbtnJabstrukrId
		
		LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=PegId AND jf.jbtnStatus='Aktif'
		LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=jf.jbtnJabfungrId
		LEFT JOIN pub_ref_jabatan_fungsional_jenis ON jabfungrJenisrId=jabfungjenisrId
		
		LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
		
		LEFT JOIN sdm_ref_status_pegawai ON pegStatrId=statrId
		
		LEFT JOIN 
  			(SELECT * FROM (
  					SELECT * FROM 
  						sdm_pendidikan 
  						INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
  					WHERE pddkStatusTamat='Selesai'
  					ORDER BY pendPendkelId DESC
  			) AS b GROUP BY pddkPegKode ) AS b
  			ON pddkPegKode=pegId
  WHERE
  	pegId>0
  	%filter% 
  GROUP BY satkerId,jabstrukrId, pegKelamin) AS a
GROUP BY unit_kerja, nama, jenis_kelamin
";

$sql['get_data_pegawai_eselon']="
SELECT
  IFNULL(jenis_kelamin,'X') AS jenis_kelamin,
  nama,
  unit_kerja,
  SUM(jumlah) AS jumlah
FROM
  (SELECT
  	pegKelamin AS jenis_kelamin,
  	IFNULL(js.jbtnEselon,99999) AS nama,
	IFNULL(satkerId,99999) AS unit_kerja,
  	COUNT(DISTINCT pegId) AS jumlah
  FROM
		pub_pegawai
		LEFT JOIN (SELECT * FROM sdm_pangkat_golongan WHERE pktgolStatus='Aktif' ORDER BY pktgolStatus, pktgolId ASC) AS why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
		LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
		
		LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
		LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
		
		LEFT JOIN sdm_jabatan_struktural js ON js.jbtnPegKode=PegId AND js.jbtnStatus='Aktif'
		LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=js.jbtnJabstrukrId
		
		LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=PegId AND jf.jbtnStatus='Aktif'
		LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=jf.jbtnJabfungrId
		LEFT JOIN pub_ref_jabatan_fungsional_jenis ON jabfungrJenisrId=jabfungjenisrId
		
		LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
		
		LEFT JOIN sdm_ref_status_pegawai ON pegStatrId=statrId
		
		LEFT JOIN 
  			(SELECT * FROM (
  					SELECT * FROM 
  						sdm_pendidikan 
  						INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
  					WHERE pddkStatusTamat='Selesai'
  					ORDER BY pendPendkelId DESC
  			) AS b GROUP BY pddkPegKode ) AS b
  			ON pddkPegKode=pegId
  WHERE
  	pegId>0
  	%filter% 
  GROUP BY satkerId,js.jbtnEselon, pegKelamin) AS a
GROUP BY unit_kerja, nama, jenis_kelamin
";

$sql['get_data_pegawai_pendidikan']="
SELECT
  IFNULL(jenis_kelamin,'X') AS jenis_kelamin,
  nama,
  unit_kerja,
  SUM(jumlah) as jumlah
FROM
  (SELECT
  	pegKelamin as jenis_kelamin,
  	ifnull(pendId,99999) as nama,
		ifnull(satkerId,99999) as unit_kerja,
  	count(DISTINCT pegId) as jumlah
  FROM
		pub_pegawai
		LEFT JOIN (SELECT * FROM sdm_pangkat_golongan WHERE pktgolStatus='Aktif' ORDER BY pktgolStatus, pktgolId ASC) AS why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
		LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
		
		LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
		LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
		
		LEFT JOIN sdm_jabatan_struktural js ON js.jbtnPegKode=PegId AND js.jbtnStatus='Aktif'
		LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=js.jbtnJabstrukrId
		
		LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=PegId AND jf.jbtnStatus='Aktif'
		LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=jf.jbtnJabfungrId
		LEFT JOIN pub_ref_jabatan_fungsional_jenis ON jabfungrJenisrId=jabfungjenisrId
		
		LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
		
		LEFT JOIN sdm_ref_status_pegawai ON pegStatrId=statrId
		
		LEFT JOIN 
  			(SELECT * FROM (
  					SELECT * FROM 
  						sdm_pendidikan 
  						INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
  					WHERE pddkStatusTamat='Selesai'
  					ORDER BY pendPendkelId DESC
  			) AS b GROUP BY pddkPegKode ) AS b
  			ON pddkPegKode=pegId
  WHERE
  	pegId>0
  	%filter%
  GROUP BY satkerId,pendId, pegKelamin) as a
GROUP BY unit_kerja, nama, jenis_kelamin
"; 

$sql['get_data_pegawai_jenis']="
SELECT
  IFNULL(jenis_kelamin,'X') AS jenis_kelamin,
  nama,
  unit_kerja,
  SUM(jumlah) as jumlah
FROM
  (SELECT
  	pegKelamin as jenis_kelamin,
  	ifnull(jnspegrId,99999) as nama,
		ifnull(satkerId,99999) as unit_kerja,
  	count(DISTINCT pegId) as jumlah
  FROM
		pub_pegawai
		LEFT JOIN (SELECT * FROM sdm_pangkat_golongan WHERE pktgolStatus='Aktif' ORDER BY pktgolStatus, pktgolId ASC) AS why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
		LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
		
		LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
		LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
		
		LEFT JOIN sdm_jabatan_struktural js ON js.jbtnPegKode=PegId AND js.jbtnStatus='Aktif'
		LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=js.jbtnJabstrukrId
		
		LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=PegId AND jf.jbtnStatus='Aktif'
		LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=jf.jbtnJabfungrId
		LEFT JOIN pub_ref_jabatan_fungsional_jenis ON jabfungrJenisrId=jabfungjenisrId
		
		LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
		
		LEFT JOIN sdm_ref_status_pegawai ON pegStatrId=statrId
		
		LEFT JOIN 
  			(SELECT * FROM (
  					SELECT * FROM 
  						sdm_pendidikan 
  						INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
  					WHERE pddkStatusTamat='Selesai'
  					ORDER BY pendPendkelId DESC
  			) AS b GROUP BY pddkPegKode ) AS b
  			ON pddkPegKode=pegId
  WHERE
  	pegId>0
  	%filter%
  GROUP BY satkerId,jnspegrId, pegKelamin) as a
GROUP BY unit_kerja, nama, jenis_kelamin
"; 

$sql['get_data_pegawai_agama']="
SELECT
  IFNULL(jenis_kelamin,'X') AS jenis_kelamin,
  nama,
  unit_kerja,
  SUM(jumlah) as jumlah
FROM
  (SELECT
  	pegKelamin as jenis_kelamin,
  	ifnull(pegAgamaId,99999) as nama,
	ifnull(satkerId,99999) as unit_kerja,
  	count(DISTINCT pegId) as jumlah
  FROM
		pub_pegawai
		LEFT JOIN (SELECT * FROM sdm_pangkat_golongan WHERE pktgolStatus='Aktif' ORDER BY pktgolStatus, pktgolId ASC) AS why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
		LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
		
		LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
		LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
		
		LEFT JOIN sdm_jabatan_struktural js ON js.jbtnPegKode=PegId AND js.jbtnStatus='Aktif'
		LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=js.jbtnJabstrukrId
		
		LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=PegId AND jf.jbtnStatus='Aktif'
		LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=jf.jbtnJabfungrId
		LEFT JOIN pub_ref_jabatan_fungsional_jenis ON jabfungrJenisrId=jabfungjenisrId
		
		LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
		
		LEFT JOIN sdm_ref_status_pegawai ON pegStatrId=statrId
		
		LEFT JOIN 
  			(SELECT * FROM (
  					SELECT * FROM 
  						sdm_pendidikan 
  						INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
  					WHERE pddkStatusTamat='Selesai'
  					ORDER BY pendPendkelId DESC
  			) AS b GROUP BY pddkPegKode ) AS b
  			ON pddkPegKode=pegId
  WHERE
  	pegId>0
  	%filter%
  GROUP BY satkerId,pegAgamaId, pegKelamin) as a
GROUP BY unit_kerja, nama, jenis_kelamin
";

$sql['get_data_pegawai_statnikah']="
SELECT
  IFNULL(jenis_kelamin,'X') AS jenis_kelamin,
  nama,
  unit_kerja,
  SUM(jumlah) as jumlah
FROM
  (SELECT
  	pegKelamin as jenis_kelamin,
  	ifnull(pegStatnikahId,99999) as nama,
	ifnull(satkerId,99999) as unit_kerja,
  	count(DISTINCT pegId) as jumlah
  FROM
		pub_pegawai
		LEFT JOIN (SELECT * FROM sdm_pangkat_golongan WHERE pktgolStatus='Aktif' ORDER BY pktgolStatus, pktgolId ASC) AS why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
		LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
		
		LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
		LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
		
		LEFT JOIN sdm_jabatan_struktural js ON js.jbtnPegKode=PegId AND js.jbtnStatus='Aktif'
		LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=js.jbtnJabstrukrId
		
		LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=PegId AND jf.jbtnStatus='Aktif'
		LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=jf.jbtnJabfungrId
		LEFT JOIN pub_ref_jabatan_fungsional_jenis ON jabfungrJenisrId=jabfungjenisrId
		
		LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
		
		LEFT JOIN sdm_ref_status_pegawai ON pegStatrId=statrId
		
		LEFT JOIN 
  			(SELECT * FROM (
  					SELECT * FROM 
  						sdm_pendidikan 
  						INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
  					WHERE pddkStatusTamat='Selesai'
  					ORDER BY pendPendkelId DESC
  			) AS b GROUP BY pddkPegKode ) AS b
  			ON pddkPegKode=pegId
  WHERE
  	pegId>0
  	%filter%
  GROUP BY satkerId,pegStatnikahId, pegKelamin) as a
GROUP BY unit_kerja, nama, jenis_kelamin
";

$sql['get_data_pegawai_usia']="
SELECT
  IFNULL(jenis_kelamin,'X') AS jenis_kelamin,
  CASE
    WHEN (nama <=20) THEN 'A'
    WHEN nama BETWEEN 21 AND 30 THEN 'B'
    WHEN nama BETWEEN 31 AND 40 THEN 'C'
    WHEN nama BETWEEN 41 AND 50 THEN 'D'
    WHEN nama BETWEEN 51 AND 60 THEN 'E'
    WHEN nama BETWEEN 61 AND 99998 THEN 'F'
    ELSE '99999'
  END as nama,
  unit_kerja,
  SUM(jumlah) as jumlah
FROM
  (SELECT
  	pegKelamin as jenis_kelamin,
  	IF(YEAR(pegTglLahir)='0000' OR MONTH(pegTglLahir)='00' OR DATE(pegTglLahir)='00',99999,ROUND(DATEDIFF(NOW(),pegTglLahir)/365)) AS nama,
	ifnull(satkerId,99999) as unit_kerja,
  	count(DISTINCT pegId) as jumlah
  FROM
		pub_pegawai
		LEFT JOIN (SELECT * FROM sdm_pangkat_golongan WHERE pktgolStatus='Aktif' ORDER BY pktgolStatus, pktgolId ASC) AS why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
		LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
		
		LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
		LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
		
		LEFT JOIN sdm_jabatan_struktural js ON js.jbtnPegKode=PegId AND js.jbtnStatus='Aktif'
		LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=js.jbtnJabstrukrId
		
		LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=PegId AND jf.jbtnStatus='Aktif'
		LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=jf.jbtnJabfungrId
		LEFT JOIN pub_ref_jabatan_fungsional_jenis ON jabfungrJenisrId=jabfungjenisrId
		
		LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
		
		LEFT JOIN sdm_ref_status_pegawai ON pegStatrId=statrId
		
		LEFT JOIN 
  			(SELECT * FROM (
  					SELECT * FROM 
  						sdm_pendidikan 
  						INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
  					WHERE pddkStatusTamat='Selesai'
  					ORDER BY pendPendkelId DESC
  			) AS b GROUP BY pddkPegKode ) AS b
  			ON pddkPegKode=pegId
  WHERE
  	pegId>0
  	%filter%
  GROUP BY satkerId,pegKelamin) as a
GROUP BY unit_kerja, nama, jenis_kelamin
";

$sql['get_data_pegawai_masakerja']="
SELECT
  IFNULL(jenis_kelamin,'X') AS jenis_kelamin,
  CASE
    WHEN nama BETWEEN 0 AND 10 THEN 'A'
	WHEN nama BETWEEN 11 AND 20 THEN 'B'
    WHEN nama BETWEEN 21 AND 30 THEN 'C'
    WHEN nama BETWEEN 31 AND 40 THEN 'D'
    WHEN nama BETWEEN 41 AND 50 THEN 'E'
    WHEN nama BETWEEN 51 AND 60 THEN 'F'
    WHEN nama BETWEEN 61 AND 99998 THEN 'G'
    ELSE '99999'
  END as nama,
  unit_kerja,
  SUM(jumlah) as jumlah
FROM
  (SELECT
  	pegKelamin as jenis_kelamin,
  	IF(YEAR(pegTglMasukInstitusi)='0000' OR MONTH(pegTglMasukInstitusi)='00' OR DATE(pegTglMasukInstitusi)='00',99999,ROUND(DATEDIFF(NOW(),pegTglMasukInstitusi)/365)) AS nama,
	ifnull(satkerId,99999) as unit_kerja,
  	count(DISTINCT pegId) as jumlah
  FROM
		pub_pegawai
		LEFT JOIN (SELECT * FROM sdm_pangkat_golongan WHERE pktgolStatus='Aktif' ORDER BY pktgolStatus, pktgolId ASC) AS why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
		LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
		
		LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
		LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
		
		LEFT JOIN sdm_jabatan_struktural js ON js.jbtnPegKode=PegId AND js.jbtnStatus='Aktif'
		LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=js.jbtnJabstrukrId
		
		LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=PegId AND jf.jbtnStatus='Aktif'
		LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=jf.jbtnJabfungrId
		LEFT JOIN pub_ref_jabatan_fungsional_jenis ON jabfungrJenisrId=jabfungjenisrId
		
		LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
		
		LEFT JOIN sdm_ref_status_pegawai ON pegStatrId=statrId
		
		LEFT JOIN 
  			(SELECT * FROM (
  					SELECT * FROM 
  						sdm_pendidikan 
  						INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
  					WHERE pddkStatusTamat='Selesai'
  					ORDER BY pendPendkelId DESC
  			) AS b GROUP BY pddkPegKode ) AS b
  			ON pddkPegKode=pegId
  WHERE
  	pegId>0
  	%filter%
  GROUP BY satkerId,pegKelamin) as a
GROUP BY unit_kerja, nama, jenis_kelamin
";
*/

?>
