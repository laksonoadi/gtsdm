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

$sql['get_data_penghargaan']="
SELECT
  nip,
  nama,
  pangkat,
  jabatan_struktural,
  jabatan_fungsional,
  tanggal_masuk_institusi,
  masa_kerja,
  IF(masa_kerja>=55,55,IF(masa_kerja>=35,35,IF(masa_kerja>=25,25,0))) as masa_kerja_penghargaan,
  DATE_ADD(tanggal_masuk_institusi, INTERVAL IF(masa_kerja>=55,55,IF(masa_kerja>=35,35,IF(masa_kerja>=25,25,0))) YEAR) as tanggal_penghargaan,
  unit_kerja
FROM (
  SELECT DISTINCT
  	pegKodeResmi as nip,
  	concat(ifnull(pegGelarDepan,''),if(ifnull(pegGelarDepan,'')='','',' '),pegNama,if(ifnull(pegGelarBelakang,'')='','',', '),ifnull(pegGelarBelakang,'')) as nama,
  	pktgolrId as pangkat,
  	jabstrukrNama as jabatan_struktural,
  	jabfungrNama as jabatan_fungsional,
  	pegTglMasukInstitusi as tanggal_masuk_institusi,
  	YEAR(now())-YEAR(pegTglMasukInstitusi) as masa_kerja,
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
  	pegTglKeluarInstitusi IS NULL
	  %unit_kerja%
  	%pangkat_golongan%
  ) AS TABEL
WHERE
  DATE_ADD(tanggal_masuk_institusi, INTERVAL IF(masa_kerja>=55,55,IF(masa_kerja>=35,35,IF(masa_kerja>=25,25,0))) YEAR)>='2000-01-01' 
  AND DATE_ADD(tanggal_masuk_institusi, INTERVAL IF(masa_kerja>=55,55,IF(masa_kerja>=35,35,IF(masa_kerja>=25,25,0))) YEAR)<='2010-08-31'
  AND IF(masa_kerja>=55,55,IF(masa_kerja>=35,35,IF(masa_kerja>=25,25,0)))>0
%limit%
"; 

?>
