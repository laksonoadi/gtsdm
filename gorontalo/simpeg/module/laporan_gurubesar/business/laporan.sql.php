<?php
$sql['get_combo_unit_kerja'] = "
SELECT
  satkerId as id,
  satkerNama as name
FROM 
  pub_satuan_kerja
";

$sql['get_data_gurubesar']="
SELECT DISTINCT
	pegKodeResmi as nip,
	concat(ifnull(pegGelarDepan,''),if(ifnull(pegGelarDepan,'')='','',' '),pegNama,if(ifnull(pegGelarBelakang,'')='','',', '),ifnull(pegGelarBelakang,'')) as nama,
	pktgolPktgolrId as gol,
	jabstrukrNama as jabatan,
	pktgolTmt as gol_tmt,
  b.jbtnTglMulai as jabatan_tmt,
	pendNama as pendidikan,
	kepakaranrNama as kepakaran,
	satkerNama as unit_kerja
FROM
	pub_pegawai
	INNER JOIN sdm_jabatan_fungsional a ON a.jbtnPegKode=pegId
	LEFT JOIN pub_ref_jabatan_fungsional ON jbtnJabfungrId=jabfungrId
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId 
	LEFT JOIN 
		(select * from (
				select * from 
					sdm_pendidikan 
					INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
				where pddkStatusTamat='Selesai'
				ORDER BY pendPendkelId DESC
		) as b GROUP by pddkPegKode ) as b
		ON pddkPegKode=pegId
	LEFT JOIN sdm_jabatan_struktural b ON  b.jbtnPegKode=pegId AND b.jbtnStatus='Aktif'
	LEFT JOIN sdm_ref_jabatan_struktural ON jbtnJabstrukrId=jabstrukrId
	
	LEFT JOIN sdm_dosen_kepakaran ON dosenpakarPegKode=pegId
	LEFT JOIN sdm_ref_kepakaran ON dosenKepakaranId=kepakaranrId
WHERE	
	UPPER(jabfungrNama)='GURU BESAR'
	%unit_kerja%
ORDER BY pegNama
%limit%
"; 

?>
