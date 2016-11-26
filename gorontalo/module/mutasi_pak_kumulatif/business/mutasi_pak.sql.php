<?php
//===GET===
$sql['cek_pak_sebelum'] = "
SELECT 
	*
FROM 
	sdm_pak_kumulatif
WHERE
	pakkumIsApproved=0 AND pakkumPegId='%s'
";

$sql['get_count_pegawai'] = "
SELECT 
   COUNT(pegId) AS total
FROM 
   pub_pegawai
WHERE
   pegKodeResmi like '%s' OR pegNama like '%s'
";

$sql['get_count_kegiatan'] = "
SELECT 
   COUNT(kegiatanId) AS total
FROM 
   sdm_ref_pak_kegiatan
WHERE
   kegiatanNama like '%s'
";

$sql['get_count_mutasi'] = "
SELECT 
   COUNT(pakkumId) AS total
FROM 
   sdm_pak_kumulatif
WHERE 
   pakkumPegId='%s'
";

$sql['get_list_pegawai'] = "
SELECT 
    pegId as id,
	pegNama as nama,
	pegKodeResmi as nip
FROM 
   pub_pegawai
WHERE
   pegKodeResmi like '%s' OR pegNama like '%s'
ORDER BY
	 pegKodeResmi
   LIMIT %s, %s
";

$sql['get_list_kegiatan'] = "
SELECT 
    kegiatanId as id,
	kegiatanNama as nama,
	kegiatanAngkaKredit as angka_kredit
FROM 
    sdm_ref_pak_kegiatan
WHERE
   kegiatanNama like '%s'
ORDER BY kegiatanId
LIMIT %s, %s
";
  
$sql['get_data_pegawai']="
SELECT 
	pegId as id,
	pegNama as name,
	pegKodeResmi as kode,
	pegAlamat as alamat,
	pegNoTelp as telp,
	pegSatwilId as wil,
	pegFoto as foto,
	substring(pegTglMasukInstitusi,1,4) as masuk,
	pegKodeGateAccess as no_seri,
	pegTglLahir as tgl_lahir,
	pegKelamin as jenis_kelamin,
	concat(pendNama,' ',pddkJurusan,' ',pddkInstitusi) as pendidikan_tertinggi,
	concat(pktgolrId,' ',pktgolrNama) as pangkat_golongan,
	pktgolTmt as pangkat_golongan_tmt,
	
	jabfungrNama as jabatan_fungsional,
	jabfungrId as diangkat,
	jabfungrNama as diangkat_label,
	jbtnTglMulai as jabatan_fungsional_tmt,
	satkerId as unit_kerja_id,
	satkerNama as unit_kerja,
	
	IFNULL(MAX(kegiatanAngkaKredit),0) as angka_kredit_pendidikan
FROM
	pub_pegawai
	LEFT JOIN 
		(select * from (
				select * from 
					sdm_pendidikan 
					INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
				where pddkStatusTamat='Selesai'
				ORDER BY pendPendkelId DESC
		) as ba GROUP by pddkPegKode ) as bb
		ON pddkPegKode=pegId
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
	LEFT JOIN sdm_jabatan_fungsional ON jbtnPegKode=pegId ANd jbtnStatus='Aktif'
	LEFT JOIN pub_ref_jabatan_fungsional bc ON jbtnJabfungrId=bc.jabfungrId
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
	LEFT JOIN sdm_pak_kumulatif ON pakkumPegId=pegId
	LEFT JOIN sdm_pak_kumulatif_detail ON pakkumdetPakkumId=pakkumId AND pakkumdetKegiatanId IN (1,2,3)
	LEFT JOIN sdm_ref_pak_kegiatan ON pakkumdetKegiatanId=kegiatanId
WHERE pegId='%s'
GROUP BY pegId 
"; 

$sql['get_list_mutasi_pak']="
SELECT
    pakkumId as id,
	pakkumNomor as nomor,
	pakkumTanggalPenetapan as tanggal_penetapan,
	pakkumPejabat as pejabat,
	pakkumPeriodeAwal as mulai,
	pakkumPeriodeAkhir as selesai,
	SUM(pakkumdetAngkaKredit) as total_angka_kredit,
	pakkumIsApproved as ditetapkan,
	pakkumDateApproved as tanggal_ditetapkan
FROM
	sdm_pak_kumulatif
	INNER JOIN sdm_pak_kumulatif_detail ON pakkumdetPakkumId=pakkumId
WHERE 
    pakkumPegId='%s'
GROUP BY pakkumId
"; 

$sql['get_data_mutasi_pak_by_id']="
SELECT 
	pakkumId as id,
	pakkumNomor as nopak,
	pakkumPegId as pegId,
	pakkumTanggalPenetapan as tgl_penetapan,
	pakkumPejabat as pejabat,
	pakkumPeriodeAwal as mulai,
	pakkumPeriodeAkhir as selesai,
	pakkumNextJabatanId as diangkat,
	jabfungrNama as diangkat_label,
	pakkumDateApproved as tanggal_ditetapkan
FROM
	sdm_pak_kumulatif
	LEFT JOIN pub_ref_jabatan_fungsional b ON pakkumNextJabatanId=b.jabfungrId
WHERE 
   pakkumPegId='%s' AND pakkumId='%s' 
"; 

$sql['get_data_unsur_penilaian']="
SELECT
    pakkumdetId as id,
	pakkumdetPakkumId as idPakkum,
    pakkumdetKegiatanId as kegiatan_id,
	kegiatanNama as kegiatan,
	unsurJenis as jenisUnsur,
	unsurNama as unsur,
	unsurKeterangan as subunsur,
	pakkumdetAngkaKredit as angka_kredit,
	pakkumdetKeterangan as deskripsi,
	pakkumdetPeran as peran,
	pakkumdetLokasi as lokasi,
	pakkumdetWaktu as waktu,
	pakkumdetBuktiFisik as bukti,
	pakkumdetLampiran as lampiran,
	pakkumdetrelReferensi as referensi
FROM	
	sdm_pak_kumulatif_detail
	INNER JOIN sdm_ref_pak_kegiatan ON kegiatanId=pakkumdetKegiatanId
	INNER JOIN sdm_ref_pak_unsur ON unsurId=kegiatanUnsurId
	LEFT JOIN sdm_pak_kumulatif_detail_relasi ON pakkumdetrelPakkumdetId=pakkumdetId
WHERE
	pakkumdetPakkumId='%s'
"; 

$sql['get_data_unsur_penilaian_']="
SELECT
    pakkumdetId as id,
	pakkumdetPakkumId as idPakkum,
    pakkumdetKegiatanId as kegiatan_id,
	kegiatanNama as kegiatan,
	unsurJenis as jenisUnsur,
	unsurNama as unsur,
	unsurKeterangan as subunsur,
	pakkumdetAngkaKredit as angka_kredit,
	pakkumdetKeterangan as deskripsi,
	pakkumdetPeran as peran,
	pakkumdetLokasi as lokasi,
	pakkumdetWaktu as waktu,
	pakkumdetBuktiFisik as bukti,
	pakkumdetLampiran as lampiran
FROM	
	sdm_pak_kumulatif_detail
	INNER JOIN sdm_ref_pak_kegiatan ON kegiatanId=pakkumdetKegiatanId
	INNER JOIN sdm_ref_pak_unsur ON unsurId=kegiatanUnsurId
WHERE
	pakkumdetPakkumId='%s'
";

$sql['get_data_unsur_penilaian_group1']="
SELECT
	unsurJenis AS jenis,
	unsurNama AS unsur,
	unsurKeterangan AS subunsur,
	kegiatanNama AS kegiatan,
	SUM(pakkumdetAngkaKredit) AS total
FROM
	sdm_pak_kumulatif_detail
	INNER JOIN sdm_ref_pak_kegiatan ON pakkumdetKegiatanId=kegiatanId
	INNER JOIN sdm_ref_pak_unsur ON unsurId=kegiatanUnsurId
WHERE
	pakkumdetPakkumId='%s'
GROUP BY unsurJenis, unsurNama, unsurKeterangan, kegiatanNama
ORDER BY unsurId,kegiatanId
"; 

$sql['get_data_unsur_penilaian_group2']="
SELECT
    unsurJenis AS jenis,
	unsurNama AS unsur,
	unsurKeterangan AS subunsur,
	SUM(pakkumdetAngkaKredit) AS total
FROM
	sdm_pak_kumulatif_detail
	INNER JOIN sdm_ref_pak_kegiatan ON pakkumdetKegiatanId=kegiatanId
	INNER JOIN sdm_ref_pak_unsur ON unsurId=kegiatanUnsurId
WHERE
	pakkumdetPakkumId='%s'
GROUP BY unsurJenis, unsurNama, unsurKeterangan
ORDER BY unsurId
"; 

$sql['get_data_unsur_penilaian_lama_group2']="
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
"; 

$sql['get_combo_unit_kerja']="
SELECT
	satkerId as id,
	satkerNama as name
FROM
	pub_satuan_kerja 
";

$sql['get_combo_kegiatan']="
SELECT
	kegiatanId as id,
	kegiatanNama as name
FROM
	sdm_ref_pak_kegiatan 
";

$sql['get_combo_jabatan']="
SELECT
	jabfungrId as id,
	jabfungrNama as name
FROM
	pub_ref_jabatan_fungsional
WHERE
	jabfungrJenisrId IN (
				SELECT 
					jabfungrJenisrId
				FROM
					sdm_jabatan_fungsional 
					INNER JOIN pub_ref_jabatan_fungsional ON jbtnJabfungrId=jabfungrId AND jbtnStatus='Aktif' AND jbtnPegKode='%s'
			     )   
";

$sql['get_id_struk']="
SELECT 
   jabstrukKompgajidtId
FROM 
   sdm_ref_jabatan_struktural
WHERE 
   jabstrukrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO sdm_pak_kumulatif(
    pakkumPegId,
    pakkumNomor,
    pakkumTanggalPenetapan,
    pakkumPejabat,
    pakkumPeriodeAwal,
    pakkumPeriodeAkhir,
    pakkumNextJabatanId,
	pakkumCreatedUserId,
	pakkumCreatedDate
   )
VALUES('%s','%s','%s','%s','%s','%s','%s','%s',now())  
";

$sql['do_add_unsur'] = "
INSERT INTO sdm_pak_kumulatif_detail(
    pakkumdetPakkumId,
    pakkumdetKegiatanId,
    pakkumdetAngkaKredit,
    pakkumdetKeterangan,
	pakkumdetPeran,
	pakkumdetLokasi,
	pakkumdetWaktu,
	pakkumdetBuktiFisik,
	pakkumdetLampiran
   )
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s')  
";

$sql['do_add_unsur_relasi'] = "
INSERT INTO 
	sdm_pak_kumulatif_detail_relasi
SET 
	pakkumdetrelPakkumdetId=(SELECT MAX(pakkumdetId) FROM sdm_pak_kumulatif_detail WHERE pakkumdetPakkumId='%s'),
    pakkumdetrelReferensi='%s'
";

$sql['do_update'] = "
UPDATE sdm_pak_kumulatif
SET 
	pakkumPegId='%s',
    pakkumNomor='%s',
    pakkumTanggalPenetapan='%s',
    pakkumPejabat='%s',
    pakkumPeriodeAwal='%s',
    pakkumPeriodeAkhir='%s',
    pakkumNextJabatanId='%s',
	pakkumModifiedUserId='%s',
	pakkumModifiedDate=now()
WHERE 
	pakkumId = '%s'
";  

$sql['do_approved'] = "
UPDATE sdm_pak_kumulatif
SET 
	pakkumPegId='%s',
    pakkumNomor='%s',
	pakkumIsApproved=1,
    pakkumDateApproved='%s',
    pakkumPejabat='%s',
    pakkumPeriodeAwal='%s',
    pakkumPeriodeAkhir='%s',
    pakkumNextJabatanId='%s',
	pakkumModifiedUserId='%s',
	pakkumModifiedDate=now()
WHERE 
	pakkumId = '%s'
"; 

$sql['do_update_unsur'] = "
UPDATE sdm_pak_kumulatif_detail
SET 
	pakkumdetPakkumId='%s',
    pakkumdetKegiatanId='%s',
    pakkumdetAngkaKredit='%s',
    pakkumdetKeterangan='%s',
	pakkumdetPeran='%s',
	pakkumdetLokasi='%s',
	pakkumdetWaktu='%s',
	pakkumdetBuktiFisik='%s'
WHERE 
	pakkumdetId = '%s'
";

$sql['do_delete'] = "
DELETE FROM
   sdm_pak_kumulatif
WHERE 
   pakkumId = %s  
";

$sql['do_delete_unsur'] = "
DELETE FROM
   sdm_pak_kumulatif_detail
WHERE 
   pakkumdetPakkumId = %s  
";

$sql['get_max_id']="
select 
	max(pakkumId) as MAXID
FROM sdm_pak_kumulatif
";

$sql['get_max_id_by_peg_id']="
select 
	min(pakkumId) as MAXID
FROM sdm_pak_kumulatif
WHERE
	pakkumPegId='%s' AND pakkumIsApproved=0
";

$sql['sinkronisasi_sdm_pak_pendidikan']="
INSERT INTO sdm_pak_pendidikan (
	otopakpendReferensi,
	otopakpendPegId,
	otopakpendTingkat,
	otopakpendLevel,
	otopakpendMulai,
	otopakpendSelesai,
	otopakpendLokasi,
	otopakpendKeterangan,
	otopakpendDokumen)
(SELECT
	CONCAT('sdm_pendidikan;',pddkId) AS Referensi,
	pddkPegKode AS pegId,
	UPPER(pendNama) AS tingkat,
	IF (UPPER(satwilNama)='INDONESIA','NASIONAL','INTERNASIONAL') AS LEVEL,
	pddkTglMulaiDinas AS mulai,
	pddkTglSelesaiDinas AS selesai,
	pddkTempat AS lokasi,
	CONCAT(pddkJurusan,', ',pddkInstitusi) AS keterangan,
	pddkUpload as dokumen
FROM
	sdm_pendidikan
	INNER JOIN pub_ref_pendidikan ON pddkTkpddkrId=pendId
	INNER JOIN pub_ref_satuan_wilayah ON satwilId=pddkNegaraId
WHERE
	pddkStatusTamat='Selesai' AND pddkPegKode='%s'
)
ON DUPLICATE KEY UPDATE
	otopakpendTingkat=VALUES(otopakpendTingkat),
	otopakpendLevel=VALUES(otopakpendLevel),
	otopakpendMulai=VALUES(otopakpendMulai),
	otopakpendSelesai=VALUES(otopakpendSelesai),
	otopakpendLokasi=VALUES(otopakpendLokasi),
	otopakpendKeterangan=VALUES(otopakpendKeterangan),
	otopakpendDokumen=VALUES(otopakpendDokumen);
";

$sql['sinkronisasi_sdm_pak_organisasi']="
INSERT INTO sdm_pak_organisasi (
	otopakorgReferensi,
	otopakorgPegId,
	otopakorgBadan,
	otopakorgPeran,
	otopakorgLevel,
	otopakorgMulai,
	otopakorgSelesai,
	otopakorgLokasi,
	otopakorgWaktu,
	otopakorgKeterangan,
	otopakorgDokumen)
(SELECT
	CONCAT('sdm_organisasi;',orgId) AS Referensi,
	orgPegKode AS pegId,
	UPPER(jnsorgNama) AS badan,
	UPPER(orgJabatan) AS jabatan,
	'ALL' AS LEVEL,
	DATE(CONCAT(IF(orgTahunMulai IS NULL OR orgTahunMulai='',YEAR(NOW()),orgTahunMulai),'-01-01')) AS mulai,
	DATE(CONCAT(IF(orgTahunSelesai IS NULL OR orgTahunSelesai='',YEAR(NOW()),orgTahunSelesai),'-12-31')) AS selesai,
	'' AS lokasi,
	CONCAT(orgTahunMulai,' s/d ',IF(orgTahunSelesai IS NULL OR orgTahunSelesai='','Sekarang',orgTahunSelesai)) as waktu,
	orgNama AS keterangan,
	orgUpload as dokumen
FROM
	sdm_organisasi
	LEFT JOIN sdm_ref_jenis_organisasi ON orgJenis=jnsorgId
WHERE
	orgPegKode='%s'
)
ON DUPLICATE KEY UPDATE
	otopakorgBadan=VALUES(otopakorgBadan),
	otopakorgPeran=VALUES(otopakorgPeran),
	otopakorgLevel=VALUES(otopakorgLevel),
	otopakorgMulai=VALUES(otopakorgMulai),
	otopakorgSelesai=VALUES(otopakorgSelesai),
	otopakorgLokasi=VALUES(otopakorgLokasi),
	otopakorgWaktu=VALUES(otopakorgWaktu),
	otopakorgKeterangan=VALUES(otopakorgKeterangan),
	otopakorgDokumen=VALUES(otopakorgDokumen);
";

$sql['sinkronisasi_sdm_pak_penghargaan']="
INSERT INTO sdm_pak_penghargaan (
	otopakpenghReferensi,
	otopakpenghPegId,
	otopakpenghJenis,
	otopakpenghMulai,
	otopakpenghSelesai,
	otopakpenghLokasi,
	otopakpenghWaktu,
	otopakpenghKeterangan,
	otopakpenghDokumen)
(SELECT
	CONCAT('sdm_penghargaan;',phgId) AS Referensi,
	phgPegKode AS pegId,
	UPPER(jnsphgrNama) AS jenis,
	DATE(CONCAT(IF(phgTahun IS NULL OR phgTahun='',YEAR(NOW()),phgTahun),'-01-01')) AS mulai,
	DATE(CONCAT(IF(phgTahun IS NULL OR phgTahun='',YEAR(NOW()),phgTahun),'-12-31')) AS selesai,
	'' AS lokasi,
	CONCAT('Tahun ',phgTahun) as waktu,
	phgNama AS keterangan,
	phgUpload as dokumen
FROM
	sdm_penghargaan
	LEFT JOIN sdm_ref_jenis_penghargaan ON phgJnsphgrId=JnsphgrId
WHERE
	phgPegKode='%s'
)
ON DUPLICATE KEY UPDATE
	otopakpenghJenis=VALUES(otopakpenghJenis),
	otopakpenghMulai=VALUES(otopakpenghMulai),
	otopakpenghSelesai=VALUES(otopakpenghSelesai),
	otopakpenghLokasi=VALUES(otopakpenghLokasi),
	otopakpenghWaktu=VALUES(otopakpenghWaktu),
	otopakpenghKeterangan=VALUES(otopakpenghKeterangan),
	otopakpenghDokumen=VALUES(otopakpenghDokumen);
";

$sql['sinkronisasi_sdm_pak_pengabdian']="
INSERT INTO sdm_pak_pengabdian (
	otopakpengabReferensi,
	otopakpengabPegId,
	otopakpengabTipe,
	otopakpengabMulai,
	otopakpengabSelesai,
	otopakpengabLokasi,
	otopakpengabWaktu,
	otopakpengabKeterangan,
	otopakpengabDokumen)
(SELECT
	CONCAT('sdm_pengabdian;',pemasyId) AS Referensi,
	pemasyPegId AS pegId,
	UPPER(jnspengabNama) AS jenis,
	DATE(CONCAT(IF(pemasyMulai IS NULL OR pemasyMulai='',YEAR(NOW()),pemasyMulai),'-01-01')) AS mulai,
	DATE(CONCAT(IF(pemasySelesai IS NULL OR pemasySelesai='',YEAR(NOW()),pemasySelesai),'-12-31')) AS selesai,
	pemasyTempat AS lokasi,
	CONCAT(pemasyMulai,' s/d ',pemasySelesai) as waktu,
	CONCAT(pemasyNama,' Besar Dana ',pemasyBesarDana,' ',pemasyKet) AS keterangan,
	pemasyUpload as dokumen
FROM
	sdm_pengabdian_masyarakat
	LEFT JOIN sdm_ref_jenis_pengabdian ON pemasyJenis=jnspengabId
WHERE
	pemasyPegId='%s'
)
ON DUPLICATE KEY UPDATE
	otopakpengabTipe=VALUES(otopakpengabTipe),
	otopakpengabMulai=VALUES(otopakpengabMulai),
	otopakpengabSelesai=VALUES(otopakpengabSelesai),
	otopakpengabLokasi=VALUES(otopakpengabLokasi),
	otopakpengabWaktu=VALUES(otopakpengabWaktu),
	otopakpengabKeterangan=VALUES(otopakpengabKeterangan),
	otopakpengabDokumen=VALUES(otopakpengabDokumen);
";

$sql['sinkronisasi_sdm_pak_seminar']="
INSERT INTO sdm_pak_seminar (
	otopaksemReferensi,
	otopaksemPegId,
	otopaksemPeran,
	otopaksemLevel,
	otopaksemMulai,
	otopaksemSelesai,
	otopaksemLokasi,
	otopaksemKeterangan,
	otopaksemDokumen)
(SELECT
	CONCAT('sdm_seminar;',smnrId) AS Referensi,
	smnrPegKode AS pegId,
	UPPER(smnrPeranan) AS peran,
	UPPER(tksmnrNama) AS level,
	smnrTgl AS mulai,
	smnrTgl AS selesai,
	smnrTempat AS lokasi,
	CONCAT(smnrNama,' oleh ',smnrPenyelenggara) AS keterangan,
	smnrUpload as dokumen
FROM
	sdm_seminar
	INNER JOIN sdm_ref_tingkat_seminar ON smnrTksmnrId=tksmnrId
WHERE
	smnrPegKode='%s'
)
ON DUPLICATE KEY UPDATE
	otopaksemPeran=VALUES(otopaksemPeran),
	otopaksemLevel=VALUES(otopaksemLevel),
	otopaksemMulai=VALUES(otopaksemMulai),
	otopaksemSelesai=VALUES(otopaksemSelesai),
	otopaksemLokasi=VALUES(otopaksemLokasi),
	otopaksemKeterangan=VALUES(otopaksemKeterangan),
	otopaksemDokumen=VALUES(otopaksemDokumen);
";

$sql['sinkronisasi_sdm_pak_produk']="
INSERT INTO sdm_pak_produk (
	otopakprodReferensi,
	otopakprodPegId,
	otopakprodTipe,
	otopakprodJenis,
	otopakprodLevel,
	otopakprodAkreditasi,
	otopakprodMulai,
	otopakprodSelesai,
	otopakprodLokasi,
	otopakprodKeterangan,
	otopakprodWaktu,
	otopakprodDokumen)
(SELECT
	CONCAT('sdm_penelitian;',pnltnId) AS Referensi,
	pnltnPegKode AS pegId,
	CASE 
	WHEN pnltnTipePenelitianId=1 THEN 'BUKU'
	WHEN pnltnTipePenelitianId=2 THEN 'ARTIKEL'
	WHEN pnltnTipePenelitianId=3 THEN 'KARYA ILMIAH'
	WHEN pnltnTipePenelitianId=4 THEN 'PUBLIKASI'
	END AS tipe,
	
	CASE 
	WHEN pnltnTipePenelitianId=1 THEN UPPER(jnsbukuNama)
	WHEN pnltnTipePenelitianId=2 THEN UPPER(jnsbukuNama)
	WHEN pnltnTipePenelitianId=3 THEN UPPER(jnskryrNama)
	WHEN pnltnTipePenelitianId=4 THEN UPPER(jnspublikasiNama)
	END AS jenis,
	
	'ALL' AS LEVEL,
	'ALL' AS akreditasi,
	DATE(CONCAT(pnltnTahun,'-01-01')) AS mulai,
	DATE(CONCAT(pnltnTahun,'-12-31')) AS selesai,
	'' AS lokasi,
	CONCAT(CASE 
	WHEN pnltnTipePenelitianId=1 THEN pnltnJudulBuku
	WHEN pnltnTipePenelitianId=2 THEN pnltnJudulArtikel
	WHEN pnltnTipePenelitianId=3 THEN pnltnJudulKaryaIlmiah
	WHEN pnltnTipePenelitianId=4 THEN pnltnJudulPublikasi
	END,' diterbitkan oleh ',pnltnPenerbit,' ',pnltnKeterangan) AS keterangan,
	CONCAT('Tahun ',pnltnTahun) AS waktu,
	pnltnUpload as upload
FROM
	sdm_penelitian
	LEFT JOIN sdm_ref_jenis_buku ON jnsbukuId=pnltnJnsbukuId
	LEFT JOIN sdm_ref_jenis_penelitian ON jnsPenelitianId=pnltnJnspenelitianId
	LEFT JOIN sdm_ref_jenis_karya ON pnltnJnskryrId=jnskryrId
	LEFT JOIN sdm_ref_jenis_publikasi ON jnspublikasiId=pnltnJnspublikasiId
WHERE
	pnltnPegKode='%s'
)
ON DUPLICATE KEY UPDATE
	otopakprodTipe=VALUES(otopakprodTipe),
	otopakprodJenis=VALUES(otopakprodJenis),
	otopakprodLevel=VALUES(otopakprodLevel),
	otopakprodAkreditasi=VALUES(otopakprodAkreditasi),
	otopakprodMulai=VALUES(otopakprodMulai),
	otopakprodSelesai=VALUES(otopakprodSelesai),
	otopakprodLokasi=VALUES(otopakprodLokasi),
	otopakprodKeterangan=VALUES(otopakprodKeterangan),
	otopakprodWaktu=VALUES(otopakprodWaktu),
	otopakprodDokumen=VALUES(otopakprodDokumen);
";

$sql['sinkronisasi_sdm_pak_pimpinan']="
INSERT INTO sdm_pak_pimpinan (
	otopakpimReferensi,
	otopakpimPegId,
	otopakpimJabatan,
	otopakpimMulai,
	otopakpimSelesai,
	otopakpimKeterangan,
	otopakpimLokasi,
	otopakpimWaktu,
	otopakpimDokumen)
(SELECT
	CONCAT('sdm_jabatan_struktural;',jbtnId) AS otopakpimReferensi,
	jbtnPegKode AS otopakpimPegId,
	UPPER(jabstrukrNama) AS otopakpimJabatan,
	jbtnTglMulai AS otopakpimMulai,
	jbtnTglSelesai AS otopakpimSelesai,
	CONCAT('Surat Keputusan Oleh ',jbtnSkPjb,' nomor SK:',jbtnSkNmr,' tertanggal ',jbtnSkTgl) AS otopakpimKeterangan,
	'' AS otopakpimLokasi,
	CONCAT(MONTHNAME(jbtnTglMulai),' ',YEAR(jbtnTglMulai),' s/d ',MONTHNAME(jbtnTglSelesai),' ',YEAR(jbtnTglSelesai)) AS otopakpimWaktu,
	jbtnSkUpload AS otopakpimDokumen
FROM
	sdm_jabatan_struktural
	LEFT JOIN sdm_ref_jabatan_struktural ON jbtnJabstrukrId=jabstrukrId	
WHERE
	jbtnPegKode='%s'
)
ON DUPLICATE KEY UPDATE
	otopakpimJabatan=VALUES(otopakpimJabatan),
	otopakpimMulai=VALUES(otopakpimMulai),
	otopakpimSelesai=VALUES(otopakpimSelesai),
	otopakpimKeterangan=VALUES(otopakpimKeterangan),
	otopakpimLokasi=VALUES(otopakpimLokasi),
	otopakpimWaktu=VALUES(otopakpimWaktu),
	otopakpimDokumen=VALUES(otopakpimDokumen);
";

$sql['sinkronisasi_sdm_pak_pengajaran']="
INSERT INTO sdm_pak_pengajaran (
	otopakpengReferensi,
	otopakpengPegId,
	otopakpengJabatan,
	otopakpengSks,
	otopakpengSemester,
	otopakpengMulai,
	otopakpengSelesai,
	otopakpengKeterangan,
	otopakpengLokasi,
	otopakpengWaktu)
VALUE ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')
ON DUPLICATE KEY UPDATE
	otopakpengJabatan=VALUES(otopakpengJabatan),
	otopakpengSks=VALUES(otopakpengSks),
	otopakpengSemester=VALUES(otopakpengSemester),
	otopakpengMulai=VALUES(otopakpengMulai),
	otopakpengSelesai=VALUES(otopakpengSelesai),
	otopakpengKeterangan=VALUES(otopakpengKeterangan),
	otopakpengLokasi=VALUES(otopakpengLokasi),
	otopakpengWaktu=VALUES(otopakpengWaktu);
";

$sql['sinkronisasi_sdm_pak_bimbingan']="
INSERT INTO sdm_pak_bimbingan (
	otopakbimReferensi,
	otopakbimPegId,
	otopakbimPeran,
	otopakbimLevelPeran,
	otopakbimJenis,
	otopakbimSemester,
	otopakbimMulai,
	otopakbimSelesai,
	otopakbimKeterangan,
	otopakbimLokasi,
	otopakbimWaktu)
VALUE ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')
ON DUPLICATE KEY UPDATE
	otopakbimPeran=VALUES(otopakbimPeran),
	otopakbimLevelPeran=VALUES(otopakbimLevelPeran),
	otopakbimJenis=VALUES(otopakbimJenis),
	otopakbimSemester=VALUES(otopakbimSemester),
	otopakbimMulai=VALUES(otopakbimMulai),
	otopakbimSelesai=VALUES(otopakbimSelesai),
	otopakbimKeterangan=VALUES(otopakbimKeterangan),
	otopakbimLokasi=VALUES(otopakbimLokasi),
	otopakbimWaktu=VALUES(otopakbimWaktu);
";

$sql['get_sinkronisasi_sdm_pak_pengajaran_integrasi_akademik']="
SELECT
	CONCAT(klsSemId,';','%s') AS otopakpengReferensi,
	'%s' AS otopakpengPegId,
	UPPER('%s') AS otopakpengJabatan,
	SUM(mkkurJumlahSksKurikulum) AS otopakpengSks,
	CONCAT(nmsemrNama,' ',semTahun,'/',semTahun+1) AS otopakpengSemester,
	semTanggalMulai AS otopakpengMulai,
	semTanggalSelesai AS otopakpengSelesai,
	GROUP_CONCAT(CONCAT('[',mkkurKode,'] ',mkkurNamaResmi)) AS otopakpengKeterangan,
	'' AS otopakpengLokasi,
	CONCAT(nmsemrNama,' ',semTahun,'/',semTahun+1) AS otopakpengWaktu
FROM
	s_dosen_kelas
	INNER JOIN s_kelas ON dsnkKlsId=klsId
	INNER JOIN s_semester ON semId=klsSemId
	INNER JOIN s_nama_semester_ref ON nmsemrId=semNmsemrId
	INNER JOIN s_matakuliah_kurikulum ON klsMkkurId=mkkurId
	LEFT JOIN program_studi ON mkkurProdiKode=prodiKode
WHERE 
    dsnkDsnPegNip='%s' AND NOT (klsIsBatal IS NULL)
GROUP BY klsSemId
ORDER BY klsSemId
";

$sql['get_sinkronisasi_sdm_pak_bimbingan_integrasi_akademik']="
(SELECT DISTINCT 
	CONCAT(taId,';TA;','%s',';',mhsNiu) AS otopakbimReferensi,
	'%s' AS otopakbimPegId,
	'PEMBIMBING' AS otopakbimPeran,
	IF(UPPER(dsnprntaNama) IN ('PEMBIMBING','PEMBIMBING UTAMA','PEMBIMBING I'),'UTAMA','PENDAMPING') AS otopakbimLevelPeran,
	CASE
		WHEN prodiJjarKode='D3' THEN 'SKRIPSI'
		WHEN prodiJjarKode='S1' THEN 'SKRIPSI'
		WHEN prodiJjarKode='S2' THEN 'THESIS'
		WHEN prodiJjarKode='S3' THEN 'DISERTASI'
	END AS otopakbimJenis,
	CONCAT(nmsemrNama,' ',semTahun,'/',semTahun+1) AS otopakbimSemester,
	taTanggalMulai AS otopakbimMulai,
	IFNULL(taTanggalUjian,now()) AS otopakbimSelesai,
	CONCAT('[',mhsNiu,'] ',mhsNama,' dengan judul ',taJudul) AS otopakbimKeterangan,
	'' AS otopakbimLokasi,
	CONCAT(nmsemrNama,' ',semTahun,'/',semTahun+1) AS otopakbimWaktu
FROM
	s_dosen_tugas_akhir
	LEFT JOIN s_tugas_akhir ON dsntaTaId=taId
	LEFT JOIN mahasiswa ON mhsNiu=taMhsNiu
	LEFT JOIN s_dosen_peran_ta_ref ON dsnprntaId=dsntaDsnprntaId
	LEFT JOIN s_semester_prodi ON sempId=taSempIdMulai
	LEFT JOIN program_studi ON sempProdiKode=prodiKode
	LEFT JOIN s_semester ON semId=sempSemId
	LEFT JOIN s_nama_semester_ref ON nmsemrId=semNmsemrId
WHERE 
	dsntaPegNip='%s'
ORDER BY semId)
UNION
(SELECT DISTINCT 
	CONCAT(dadarId,';DADAR;','%s',';',mhsNiu) AS otopakbimReferensi,
	'%s' AS otopakbimPegId,
	'PENGUJI' AS otopakbimPeran,
	IF(UPPER(perandsnNama) IN ('PENGUJI','PENGUJI UTAMA','PENGUJI I'),'KETUA','ANGGOTA') AS otopakbimLevelPeran,
	'ALL' AS otopakbimJenis,
	CONCAT(nmsemrNama,' ',semTahun,'/',semTahun+1) AS otopakbimSemester,
	dadarTanggalPelaksanaan AS otopakbimMulai,
	dadarTanggalPelaksanaan AS otopakbimSelesai,
	CONCAT('[',mhsNiu,'] ',mhsNama,' dengan judul ',taJudul) AS otopakbimKeterangan,
	'' AS otopakbimLokasi,
	CONCAT(nmsemrNama,' ',semTahun,'/',semTahun+1) AS otopakbimWaktu
FROM
	s_dosen_pendadaran
	LEFT JOIN s_pendadaran ON dsndadarDadarId=dadarId
	LEFT JOIN s_tugas_akhir ON dadarTaId=taId
	LEFT JOIN mahasiswa ON mhsNiu=taMhsNiu
	LEFT JOIN s_peran_dosen_pendadaran_ref ON dsndadarPerandsnId=perandsnId
	LEFT JOIN s_semester_prodi ON sempId=taSempIdMulai
	LEFT JOIN program_studi ON sempProdiKode=prodiKode
	LEFT JOIN s_semester ON semId=sempSemId
	LEFT JOIN s_nama_semester_ref ON nmsemrId=semNmsemrId
WHERE 
	dsndadarDsnPegNip='%s' AND dadarIsLulus=1 AND dadarIsSudahPendadaran=1)
";

?>
