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
  pktgolrId as id,
  concat(pktgolrId,'-',pktgolrNama) as name
FROM 
  sdm_ref_pangkat_golongan
ORDER BY pktgolrUrut
";

$sql['get_data_pensiun']="
SELECT DISTINCT
	pegKodeResmi as nip,
	concat(ifnull(pegGelarDepan,''),if(ifnull(pegGelarDepan,'')='','',' '),pegNama,if(ifnull(pegGelarBelakang,'')='','',', '),ifnull(pegGelarBelakang,'')) as nama,
	pktgolrId as pangkat,
	jabstrukrNama as jabatan_struktural,
	jabfungrNama as jabatan_fungsional,
	pegTglLahir as tanggal_lahir,
	YEAR(now())-YEAR(pegTglLahir) as umur,
	pegUsiaPensiun as usia_pensiun,
	DATE_ADD(pegTglLahir, INTERVAL pegUsiaPensiun YEAR) as tanggal_pensiun,
	satkerNama as unit_kerja
FROM
	pub_pegawai
	INNER JOIN sdm_ref_status_pegawai ON pegstatrId=statrId
	
	LEFT JOIN 
		(select * from sdm_pangkat_golongan order by pktgolStatus, pktgolId ASC) as why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId

	LEFT JOIN sdm_jabatan_struktural js ON  js.jbtnPegKode=pegId AND js.jbtnStatus='Aktif'
	LEFT JOIN sdm_ref_jabatan_struktural ON js.jbtnJabstrukrId=jabstrukrId

	LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=pegId ANd jf.jbtnStatus='Aktif'
	LEFT JOIN pub_ref_jabatan_fungsional ON jf.jbtnJabfungrId=jabfungrId

	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
WHERE
	#statrPegawai='Pensiun'
	1=1
	AND DATE_ADD(pegTglLahir, INTERVAL pegUsiaPensiun YEAR)>='%s' 
  AND DATE_ADD(pegTglLahir, INTERVAL pegUsiaPensiun YEAR)<='%s'
  AND (satkerLevel LIKE CONCAT('%s', '.%%') OR satkerId = '%s')
	%unit_kerja%
	%pangkat_golongan%
  %limit%
"; 

?>
