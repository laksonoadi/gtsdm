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

$sql['get_data_kenaikanpangkat']="
SELECT
	pegKodeResmi as nip,
	concat(ifnull(pegGelarDepan,''),if(ifnull(pegGelarDepan,'')='','',' '),pegNama,if(ifnull(pegGelarBelakang,'')='','',', '),ifnull(pegGelarBelakang,'')) as nama,
	pktgolPktgolrId as golongan,
	pktgolTmt as golongan_tmt,
	(SELECT b.pktgolrId from sdm_ref_pangkat_golongan b where b.pktgolrUrut=a.pktgolrUrut+1) as golongan_yad,
	pktgolNaikPktYad as golongan_yad_tmt,
	satkerNama as unit_kerja
FROM
	pub_pegawai
	INNER JOIN 
		(select * from sdm_pangkat_golongan order by pktgolStatus, pktgolId ASC) as why ON pktgolPegKode=pegId
	INNER JOIN sdm_ref_pangkat_golongan a ON a.pktgolrId=pktgolPktgolrId
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
WHERE
	pktgolNaikPktYad>='%s' 
  AND pktgolNaikPktYad<='%s'
	%unit_kerja%
	%pangkat_golongan%
ORDER BY pktgolNaikPktYad ASC
  %limit%
"; 

?>
