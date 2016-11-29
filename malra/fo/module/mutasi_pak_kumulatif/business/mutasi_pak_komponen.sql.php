<?php
//===GET===
$sql['get_komponen_pak'] = "
SELECT 
   otopakId as komponenid,
   otopakTable as tabel,
   otopakField as field,
   otopakKodeField as kode
FROM 
   sdm_ref_pak_komponen
";

$sql['get_kegiatan_pak_otomatis'] = "
SELECT
	kegiatanId as id,
	kegiatanNama as nama,
	kegiatanId as kegiatan_id,
	kegiatanNama as kegiatan,
	unsurJenis as jenisUnsur,
	unsurNama as unsur,
	unsurKeterangan as subunsur,
	IF (kegiatanId IN (1,2,3),
		kegiatanAngkaKredit-(SELECT IFNULL(SUM(pakkumdetAngkaKredit),0) FROM sdm_pak_kumulatif_detail INNER JOIN sdm_pak_kumulatif ON pakkumId=pakkumdetPakkumId WHERE pakkumdetKegiatanId IN (1,2,3) AND pakkumPegId=%pegid%),
		kegiatanAngkaKredit) as angka_kredit,
	deskripsi,
	lokasi,
	waktu,
	bukti,
	lampiran,
	CONCAT('%table%',':%kode%',':',idRef) as referensi
FROM
	sdm_ref_pak_komponen_detail
	INNER JOIN (
		SELECT DISTINCT
			%kode%Id as idRef,
			%komponenid% as id,
			UPPER(%field%) as value,
			%kode%Keterangan as deskripsi,
			%kode%Lokasi as lokasi,
			%kode%Waktu as waktu,
			if(%kode%Dokumen IS NULL OR %kode%Dokumen='','','Softcopy') as bukti,
			%kode%Dokumen as lampiran
		FROM 
			%table%
		WHERE
			%kode%PegId=%pegid% AND
			%kode%Digunakan=0 AND
			%kode%Selesai>=(Select IFNULL(Max(pakkumPeriodeAkhir),IFNULL(pegCpnsTmt,pegTglMasukInstitusi)) FROM pub_pegawai LEFT JOIN sdm_pak_kumulatif ON pakkumPegId=pegId WHERE pakkumPegId=%pegid%)) as a ON a.id=otopakdetOtopakId AND a.value=otopakdetFieldNilai
	INNER JOIN sdm_ref_pak_kegiatan ON kegiatanId=otopakdetKegiatanId
	INNER JOIN sdm_ref_pak_unsur ON unsurId=kegiatanUnsurId
";

$sql['get_all_kegiatan_pak'] = "
SELECT 
	pakkumdetrelReferensi as referensi
FROM 
	sdm_pak_kumulatif_detail_relasi
	INNER JOIN sdm_pak_kumulatif_detail ON pakkumdetId=pakkumdetrelPakkumdetId
	INNER JOIN sdm_pak_kumulatif ON pakkumdetPakkumId=pakkumId
WHERE
	pakkumPegId='%s'
UNION
SELECT
	CONCAT(otopaktable,':',otopakKodeField,':0') as referensi
FROM
	sdm_ref_pak_komponen
";

?>
