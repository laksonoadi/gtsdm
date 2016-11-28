<?php
$sql['get_combo_unit_kerja'] = "
SELECT
  satkerId as id,
  satkerNama as name
FROM 
  pub_satuan_kerja
";

$sql['get_data_pak']="
SELECT
	pegKodeResmi as nip,
	concat(ifnull(pegGelarDepan,''),if(ifnull(pegGelarDepan,'')='','',' '),pegNama,if(ifnull(pegGelarBelakang,'')='','',', '),ifnull(pegGelarBelakang,'')) as nama,
	pegkelamin as jenis_kelamin,
	b.jabfungrNama as jabatan,
	pktgolPktgolrId as golongan,
	satkerNama as unit_kerja,
	sum(paknAngkaLama) as pak_jumlah_lama,
	sum(paknAngkaBaru) as pak_jumlah_baru,
	sum(paknJumlahDigunakan) as pak_jumlah_digunakan,
	sum(paknJumlahLebihan) as pak_jumlah_lebihan,
	a.jabfungrNama as dapat_diangkat,
	pakTanggal as tanggal_pak
	
FROM
	sdm_pak
	INNER JOIN sdm_pak_penilaian ON paknPakId=pakId
	LEFT JOIN pub_ref_jabatan_fungsional a ON pakJbtnIdDapatDiangkat=a.jabfungrId
	INNER JOIN pub_pegawai ON pakPegKode=pegId
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_jabatan_fungsional ON jbtnPegKode=pegId ANd jbtnStatus='Aktif'
	LEFT JOIN pub_ref_jabatan_fungsional b ON jbtnJabfungrId=b.jabfungrId
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
WHERE
	pakPeriodeAwal>='%s' AND pakPeriodeAkhir<='%s'
	%unit_kerja%
GROUP BY pakId
  %limit%
"; 

?>
