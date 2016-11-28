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
	pegKodeResmi AS nip,
	CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,'')) AS nama,
	pegkelamin AS jenis_kelamin,
	b.jabfungrNama AS jabatan,
	pktgolPktgolrId AS golongan,
	satkerNama AS unit_kerja,
	IFNULL(SUM(pakkumdetAngkaKredit),0) AS pak_jumlah_baru,
	
	a.jabfungrNama AS dapat_diangkat,
	pakkumTanggalPenetapan AS tanggal_pak,
	pakkumPegId AS id,
	pakkumId AS dataid
FROM
	sdm_ref_pak_unsur
	LEFT JOIN sdm_ref_pak_kegiatan ON unsurId=kegiatanUnsurId
	LEFT JOIN sdm_pak_kumulatif_detail ON pakkumdetKegiatanId=kegiatanId 
	LEFT JOIN sdm_pak_kumulatif ON pakkumId=pakkumdetPakkumId
	LEFT JOIN pub_ref_jabatan_fungsional a ON pakkumNextJabatanId=a.jabfungrId
	INNER JOIN pub_pegawai ON pakkumPegId=pegId
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_jabatan_fungsional ON jbtnPegKode=pegId AND jbtnStatus='Aktif'
	LEFT JOIN pub_ref_jabatan_fungsional b ON jbtnJabfungrId=b.jabfungrId
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
WHERE
pakkumPeriodeAwal>='%s' AND pakkumPeriodeAkhir<='%s' AND pakkumIsApproved=1
	
GROUP BY pakkumId 
ORDER BY pakkumdetPakkumId, unsurId ASC
  %limit%
"; 

$sql['get_data_unsur_penilaian_lama_group2']="
SELECT
	SUM(total) AS lama
FROM
(
	SELECT
		unsurJenis AS jenis,
		unsurNama AS unsur,
		unsurKeterangan AS subunsur,
		IFNULL(SUM(pakkumdetAngkaKredit),0) AS total
	FROM
		sdm_ref_pak_unsur
		LEFT JOIN sdm_ref_pak_kegiatan ON unsurId=kegiatanUnsurId
		LEFT JOIN sdm_pak_kumulatif_detail ON pakkumdetKegiatanId=kegiatanId AND pakkumdetPakkumId IN (SELECT pakkumId FROM sdm_pak_kumulatif WHERE pakkumPegId='%s' AND pakkumIsApproved=1 AND pakkumPeriodeAkhir<(SELECT pakkumPeriodeAwal FROM sdm_pak_kumulatif WHERE pakkumId='%s'))
	GROUP BY unsurJenis, unsurNama, unsurKeterangan
	ORDER BY unsurId
) AS DATA
"; 

?>
