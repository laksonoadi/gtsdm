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

$sql['get_data_kenaikangaji']="
SELECT
	pegKodeResmi as nip,
	concat(ifnull(pegGelarDepan,''),if(ifnull(pegGelarDepan,'')='','',' '),pegNama,if(ifnull(pegGelarBelakang,'')='','',', '),ifnull(pegGelarBelakang,'')) as nama,
	kgbBerlakuTanggal as tanggal_gaji,
	kgbTanggalAkanDatang as tanggal_gaji_yad,
	satkerNama as unit_kerja
FROM
	pub_pegawai
	INNER JOIN sdm_kenaikan_gaji_berkala ON kgbPegKode=pegId AND kgbAktif='Aktif'
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
	LEFT JOIN 
		(select * from sdm_pangkat_golongan order by pktgolStatus, pktgolId ASC) as why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan a ON a.pktgolrId=pktgolPktgolrId
WHERE
	kgbTanggalAkanDatang>='%s' 
  AND kgbTanggalAkanDatang<='%s'
	%unit_kerja%
	%pangkat_golongan%
ORDER BY kgbTanggalAkanDatang ASC
  %limit%
"; 

$sql['getDataKenaikanGajiById']="
SELECT
	pegKodeResmi as nip,
	pegNoKarpeg as karpeg,
	concat(ifnull(pegGelarDepan,''),if(ifnull(pegGelarDepan,'')='','',' '),pegNama,if(ifnull(pegGelarBelakang,'')='','',', '),ifnull(pegGelarBelakang,'')) as nama,
	a.kgbBerlakuTanggal as tanggal_gaji,
	a.kgbTanggalAkanDatang as tanggal_gaji_yad,
	a.kgbMasaKerja as masa_kerja,
	a.kgbGajiPokokBaru as gaji_pokok_baru,
	a.kgbPejabatPenetap as tanda_tangan,
	a.kgbNomorPenetap as nomor_sk,
	a.kgbTanggalPenetap as tanggal_sk,
	t.kgbGajiPokokBaru as gaji_pokok,
	m.pktgolrNama as golongan_baru,
	c.pktgolrId as pangkat,
	c.pktgolrNama as golongan,
	pktgolTmt as tanggal_masa_kerja_golongan,
	satkerNama as unit_kerja,
	jabstrukrNama as jabatan
FROM
	pub_pegawai
	INNER JOIN sdm_kenaikan_gaji_berkala a ON a.kgbPegKode=pegId AND a.kgbAktif='Aktif'
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan c ON c.pktgolrId=pktgolPktgolrId
	LEFT JOIN (
		SELECT
			kgbId,
			kgbPegKode,
			kgbPktgolId,
			kgbGajiPokokBaru
		FROM sdm_kenaikan_gaji_berkala b
		ORDER BY kgbBerlakuTanggal DESC
	) t ON t.kgbPegKode = a.kgbPegKode AND a.kgbId > t.kgbId
	LEFT JOIN sdm_ref_pangkat_golongan m ON m.pktgolrId=a.kgbPktgolId
	LEFT JOIN sdm_jabatan_struktural ON jbtnPegKode = pegId AND jbtnStatus = 'aktif'
	LEFT JOIN sdm_ref_jabatan_struktural ON jbtnJabstrukrId = jabstrukrId
WHERE
	pegKodeResmi = '%s' AND kgbAktif = 'aktif'
ORDER BY kgbTanggalAkanDatang ASC
"; 

?>
