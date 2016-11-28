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

$sql['get_combo_jenis_pegawai'] = "
SELECT
  jnspegrId as id,
  jnspegrNama as name
FROM 
  sdm_ref_jenis_pegawai
";

$sql['get_data_dp4']="
SELECT DISTINCT
	pegKodeResmi as nip,
	concat(ifnull(pegGelarDepan,''),if(ifnull(pegGelarDepan,'')='','',' '),pegNama,if(ifnull(pegGelarBelakang,'')='','',', '),ifnull(pegGelarBelakang,'')) as nama,
	pktgolrId as pangkat,
	dpKesetiaan as kesetiaan,
	dpPrestasiKerja as prestasi_kerja,
	dpTanggungJawab as tanggung_jawab,
	dpKetaatan as ketaatan,
	dpKejujuran as kejujuran,
	dpKerjasama as kerjasama,
	dpPrakarsa as prakarsa,
	dpKepemimpinan as kepemimpinan,
	dpNilaiDasarYayasan as nilai_yayasan,
	satkerNama as unit_kerja,
	dpPeriode
FROM
	sdm_dp4
	INNER JOIN pub_pegawai ON dpPegKode=pegId
	LEFT JOIN sdm_ref_pangkat_golongan ON dpPktgolrId=pktgolrId
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
WHERE
	dpPeriode>='%s' AND dpPeriodeAkhir<='%s'
	%unit_kerja%
	%pangkat_golongan%
  %limit%
"; 

?>
