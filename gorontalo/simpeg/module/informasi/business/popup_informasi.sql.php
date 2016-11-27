<?php
//===GET===
$sql['get_user_lengkap'] = "
SELECT 
	gu.realName
FROM 
	gtfw_user gu
WHERE
	gu.userId='%s'
";

$sql['get_list_pegawai_pensiun'] = "
SELECT DISTINCT
	pegKodeResmi AS nip,
	CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,'')) AS nama,
	DATE_ADD(pegTglLahir, INTERVAL pegUsiaPensiun YEAR) AS tanggal,
	satkerNama AS unitkerja
FROM
	pub_pegawai
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
WHERE 
	DATEDIFF(DATE_ADD(pegTglLahir, INTERVAL pegUsiaPensiun YEAR),NOW()) BETWEEN 0 AND 180
    AND (satkerLevel LIKE CONCAT('%s', '.%%') OR satkerId = '%s')
ORDER BY tanggal
";

$sql['get_list_pegawai_naik_pangkat'] = "
SELECT DISTINCT
	pegKodeResmi AS nip,
	CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,'')) AS nama,
	pktgolNaikPktYad AS tanggal,
	satkerNama AS unitkerja
FROM
	pub_pegawai
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
WHERE 
	DATEDIFF(pktgolNaikPktYad,NOW()) BETWEEN 1 AND 90
    AND (satkerLevel LIKE CONCAT('%s', '.%%') OR satkerId = '%s')
ORDER BY tanggal
";

$sql['get_list_pegawai_naik_gaji'] = "
SELECT DISTINCT
	pegKodeResmi AS nip,
	CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,'')) AS nama,
	kgbTanggalAkanDatang AS tanggal,
	satkerNama AS unitkerja
FROM
	pub_pegawai
	LEFT JOIN sdm_kenaikan_gaji_berkala ON kgbPegKode=pegId AND kgbAktif='Aktif'
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
WHERE 
	DATEDIFF(kgbTanggalAkanDatang,NOW()) BETWEEN 1 AND 60
    AND (satkerLevel LIKE CONCAT('%s', '.%%') OR satkerId = '%s')
ORDER BY tanggal
";

$sql['get_list_pegawai_cuti'] = "
SELECT
	a.cutiId as 'dataid',
	a.cutiPegId as 'pegid',
	a.cutiNo as 'nomor',
	pegNama as 'nama',
	pegKodeResmi as 'nip',
	DATEDIFF(a.cutiSelesai, a.cutiMulai)+1 as 'durasi'
FROM
	sdm_cuti a
	LEFT JOIN sdm_ref_tipe_cuti b ON b.tipecutiId = a.cutiTipecutiId
	LEFT JOIN pub_pegawai ON pegId=cutiPegId
WHERE
	a.cutiStatus = 'request'
ORDER BY a.cutiMulai
";

$sql['get_list_pegawai_lembur'] = "
SELECT
	a.lemburId AS 'dataid',
	a.lemburNo AS 'nomor',
	a.lemburPengajuan AS 'tanggal',
	pegId AS pegid,
	pegNama AS nama,
	pegKodeResmi AS nip,
	TIME_FORMAT(TIMEDIFF(a.lemburSelesai, a.lemburMulai), '%s') AS 'durasi'
FROM
	sdm_lembur a
	LEFT JOIN pub_pegawai ON pegId=lemburPegId
WHERE
	a.lemburStatus= 'request'
ORDER BY a.lemburPengajuan
";

$sql['get_list_pegawai_pak'] = "
SELECT
	pakkumId AS dataid,
	pegId AS pegId,
	pegNama AS nama,
	pegKodeResmi AS nip,
	pakkumCreatedDate AS tanggal,
	SUM(pakkumdetAngkaKredit) AS total
FROM
	sdm_pak_kumulatif
	INNER JOIN sdm_pak_kumulatif_detail ON pakkumdetPakkumId=pakkumId
	LEFT JOIN pub_pegawai ON pegId=pakkumPegId
WHERE 
	pakkumIsApproved=0
GROUP BY pakkumId
ORDER BY pakkumCreatedDate
";

$sql['get_list_pegawai_bkd'] = "
SELECT
	bkdId AS dataid,
	pegId AS pegId,
	pegNama AS nama,
	pegKodeResmi AS nip,
	CONCAT('Semester ',bkdSemester,' ',bkdTahunAkademik,'/',bkdTahunAkademik+1) AS semester
FROM
	sdm_bkd
	LEFT JOIN pub_pegawai ON pegId=bkdPegId
WHERE 
	(bkdKesimpulan IS NULL) OR bkdKesimpulan=''
ORDER BY bkdId
";

$sql['cek_akses_by_userid'] = "
SELECT 
	COUNT(gtfw_menu.MenuId) AS total
FROM
	gtfw_menu
	LEFT JOIN gtfw_group_menu ON gtfw_group_menu.menuMenuId=gtfw_menu.MenuId
	LEFT JOIN gtfw_user_group ON gtfw_user_group.GroupId=gtfw_group_menu.GroupId
	LEFT JOIN gtfw_module ON gtfw_module.ModuleId=gtfw_menu.MenuDefaultModuleId
WHERE
	gtfw_user_group.UserId='%s' AND
	gtfw_module.Module='%s'
";


$sql['get_count_daftar_pegawai']="
	SELECT
		COUNT(DISTINCT pegId) as TOTAL
	FROM
		pub_pegawai
		LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
		LEFT JOIN sdm_pangkat_golongan pkt ON pkt.pktgolPegKode=pegId AND pkt.pktgolStatus='Aktif'
		LEFT JOIN sdm_ref_pangkat_golongan pktr ON pktr.pktgolrId=pkt.pktgolPktgolrId 
		LEFT JOIN sdm_jabatan_struktural js ON  js.jbtnPegKode=pegId AND js.jbtnStatus='Aktif'
		LEFT JOIN sdm_ref_jabatan_struktural jsr ON js.jbtnJabstrukrId=jsr.jabstrukrId
		LEFT JOIN sdm_jabatan_fungsional jf ON  jf.jbtnPegKode=pegId AND jf.jbtnStatus='Aktif'
		LEFT JOIN pub_ref_jabatan_fungsional jfr ON jf.jbtnJabfungrId=jfr.jabfungrId
		LEFT JOIN sdm_pelatihan ON pelPegKode=pegId
		LEFT JOIN sdm_ref_jenis_pelatihan ON pelJnspelrId=jnspelrId
		LEFT JOIN sdm_pendidikan ON pddkPegKode=pegId AND pddkStatusTamat='Selesai'
		LEFT JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
		LEFT JOIN sdm_satuan_kerja_pegawai sk ON sk.satkerpegPegId=pegId AND sk.satkerpegAktif='Aktif'
		LEFT JOIN pub_satuan_kerja skr ON skr.satkerId=sk.satkerpegSatkerId
		LEFT JOIN pub_ref_agama ON agmId=pegAgamaId
        LEFT JOIN sdm_verifikasi_data ON verdataValue=pegId
	WHERE
		1=1
        AND MONTH(pegTglLahir) = MONTH(NOW())
        AND verdataStatus = '3' AND verdataVerifikasiId = '19' 
        AND (skr.satkerLevel LIKE CONCAT('%s', '.%%') OR skr.satkerId = '%s')

";

$sql['get_count_daftar_pegawai_satya']="
  SELECT
    COUNT(DISTINCT pegId) as TOTAL,
    DATEDIFF(NOW(),pegCpnsTmt) as hari
  FROM
    pub_pegawai
    LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
    LEFT JOIN sdm_satuan_kerja_pegawai sk ON sk.satkerpegPegId=pegId AND sk.satkerpegAktif='Aktif'
    LEFT JOIN pub_satuan_kerja skr ON skr.satkerId=sk.satkerpegSatkerId
  WHERE
    1=1
    AND (skr.satkerLevel LIKE CONCAT('%s', '.%%') OR skr.satkerId = '%s')
    %filter%
    %search%
";
 
?>
