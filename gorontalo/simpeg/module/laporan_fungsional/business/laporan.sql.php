<?php
$sql['get_combo_unit_kerja'] = "
SELECT
  satkerId as id,
  satkerNama as name
FROM 
  pub_satuan_kerja
";

$sql['get_combo_jabatan_fungsional'] = "
SELECT
  jabfungjenisrId as id,
  jabfungJenis as name
FROM 
  pub_ref_jabatan_fungsional_jenis
";

$sql["count_data_fungsional"] = "
SELECT FOUND_ROWS() AS total
";

$sql['get_data_fungsional']="
SELECT SQL_CALC_FOUND_ROWS
	DISTINCT pegKodeResmi as nip,
	concat(ifnull(pegGelarDepan,''),if(ifnull(pegGelarDepan,'')='','',' '),pegNama,if(ifnull(pegGelarBelakang,'')='','',', '),ifnull(pegGelarBelakang,'')) as nama,
	pktgolPktgolrId as gol,
	pktgolTmt as tmt,
	jabfungrNama as jabatan,
	pendNama as pendidikan,
	pegKelamin as jenis_kelamin,
	satkerNama as unit_kerja
FROM
	pub_pegawai
	INNER JOIN sdm_jabatan_fungsional ON jbtnPegKode=pegId
	LEFT JOIN pub_ref_jabatan_fungsional ON jbtnJabfungrId=jabfungrId
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId 
	LEFT JOIN sdm_verifikasi_data ON pegId=verdataValue 
	LEFT JOIN 
		(select * from (
				select * from 
					sdm_pendidikan 
					INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
				where pddkStatusTamat='Selesai'
				ORDER BY pendPendkelId DESC
		) as b GROUP by pddkPegKode ) as b
		ON pddkPegKode=pegId
WHERE	
	1=1 AND verdataStatus='3' AND verdataVerifikasiId='19'
	%unit_kerja%
	%jabatan_fungsional%
	%status_jabatan%
ORDER BY pegNama
%limit%
";

?>
