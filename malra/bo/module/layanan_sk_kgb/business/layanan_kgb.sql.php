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
SELECT *
FROM (
SELECT
	pegId as peg_id,
	pegKodeResmi as nip,
	concat(ifnull(pegGelarDepan,''),if(ifnull(pegGelarDepan,'')='','',' '),pegNama,if(ifnull(pegGelarBelakang,'')='','',', '),ifnull(pegGelarBelakang,'')) as nama,
	kgbId as id_kgb,
	kgbBerlakuTanggal as tanggal_gaji,
	kgbTanggalAkanDatang as tanggal_gaji_yad,
	satkerNama as unit_kerja,
	(kgbAktif = 'Aktif') as status_kgb,
	(id_sk IS NULL) as status
FROM
	pub_pegawai
	INNER JOIN sdm_kenaikan_gaji_berkala ON kgbPegKode=pegId
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
	LEFT JOIN 
		(select * from sdm_pangkat_golongan order by pktgolStatus, pktgolId ASC) as why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan a ON a.pktgolrId=pktgolPktgolrId
	%status1%
WHERE 1=1
	%awal_akhir%
	%unit_kerja%
	%pangkat_golongan%
	%status2%
ORDER BY kgbTanggalAkanDatang DESC, pktgolNaikPktYad DESC
) a
GROUP BY peg_id, id_kgb
ORDER BY tanggal_gaji_yad ASC
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


$sql['get_data_pegawai'] = "
SELECT
    peg_id,
    id_kgb,
    nip,
    nama, 
    nama_gelar, 
    masa_kerja_tahun, 
    masa_kerja_bulan,
    pngkt,
    pngkt_nama,
    pngkt_tmt,
    tgl_up_pngkt,
    a.idDafGaji AS ref_gaji,
    a.mkDafGaji AS ref_gaji_mk,
    a.nominalDafGaji AS new_gaji,
    mk_thn_old,
    mk_bln_old,
    mk_bln_kgb_old,
    mk_thn_kgb_old,
    next_kgb,
    gaji_kgb,
    no_kgb_old,
    kgb_berlaku_old,
    tgl_kgb_lalu,
    satker,
    satker_id,
    pjbt_kgb_old
FROM (
SELECT *,
    /*count all masa kerja*/    
    IF(MIN(pktgolrTingkat) = 1 AND MAX(pktgolrTingkat) >= 2,
        CAST(IF(all_mk_bulan >= 12, all_mk_tahun + 1, all_mk_tahun) AS SIGNED) - 6,
        IF(MIN(pktgolrTingkat) <= 2 AND MAX(pktgolrTingkat) >= 3,
            CAST(IF(all_mk_bulan >= 12, all_mk_tahun + 1, all_mk_tahun) AS SIGNED) - 5,
            IF(all_mk_bulan >= 12, all_mk_tahun + 1, all_mk_tahun)
        )
    ) AS masa_kerja_tahun,
    
    IF(all_mk_bulan >= 12, all_mk_bulan MOD 12, all_mk_bulan) AS masa_kerja_bulan,
    /*count masa kerja pangkat*/
        IF(MIN(pktgolrTingkat) = 1 AND MAX(pktgolrTingkat) >= 2,
        CAST(IF(mk_bln_old_gol >= 12, mk_thn_old_gol + 1, mk_thn_old_gol) AS SIGNED) - 6,
        IF(MIN(pktgolrTingkat) <= 2 AND MAX(pktgolrTingkat) >= 3,
            CAST(IF(mk_bln_old_gol >= 12, mk_thn_old_gol + 1, mk_thn_old_gol) AS SIGNED) - 5,
            IF(mk_bln_old_gol >= 12, mk_thn_old_gol + 1, mk_thn_old_gol)
        )
    ) AS mk_thn_old,
    
    IF(mk_bln_old_gol >= 12, mk_bln_old_gol MOD 12, mk_bln_old_gol) AS mk_bln_old
    
FROM (
SELECT
    pegId AS peg_id,
    pegKodeResmi AS nip,
    pegNama AS nama,
    CONCAT(IFNULL(pegGelarDepan, ''), IF(IFNULL(pegGelarDepan, '') = '', '', ' '), pegNama, IF(IFNULL(pegGelarBelakang, '') = '', '', ', '), IFNULL(pegGelarBelakang, '')) AS nama_gelar,
    pegTglLahir AS tgl_lahir,
    pegTmpLahir AS tmp_lahir,
    a.pktgolrId AS pngkt,
    a.pktgolrNama AS pngkt_nama,
    pktgolTmt AS pngkt_tmt,
    pktgolNaikPktYad AS tgl_up_pngkt,
    a.pktgolrUrut AS old_pngkt_urut,
    satkerId AS satker_id,
    satkerNama AS satker,
    (LENGTH(satkerLevel) - LENGTH(REPLACE(satkerLevel, '.', ''))) AS satker_lvl,
    
    IF(jnspegrNama IN ('PNS','CPNS'),
        a.pktgolrMasaKerja+FLOOR(DATEDIFF(NOW(),pktgolTmt)/365)+IFNULL(mkPenyesuaianTahun,0),
        a.pktgolrMasaKerja+FLOOR(DATEDIFF(NOW(),pktgolTmt)/365)+IFNULL(mkPenyesuaianTahun,0)
    ) AS mk_thn_old_gol,
    IF(jnspegrNama IN ('PNS','CPNS'),
        FLOOR((DATEDIFF(NOW(),pktgolTmt) MOD 365)/30)+IFNULL(mkPenyesuaianBulan,0),
        FLOOR((DATEDIFF(NOW(),pktgolTmt) MOD 365)/30)+IFNULL(mkPenyesuaianBulan,0)
    ) AS mk_bln_old_gol,
    
    /*set digit masa kerja all*/
    IF(jnspegrNama IN ('PNS','CPNS'),
        a.pktgolrMasaKerja+FLOOR(DATEDIFF(NOW(),pegCpnsTmt)/365)+IFNULL(mkPenyesuaianTahun,0),
        a.pktgolrMasaKerja+FLOOR(DATEDIFF(NOW(),pegTglMasukInstitusi)/365)+IFNULL(mkPenyesuaianTahun,0)
    ) AS all_mk_tahun,
    IF(jnspegrNama IN ('PNS','CPNS'),
        FLOOR((DATEDIFF(NOW(),pegCpnsTmt) MOD 365)/30)+IFNULL(mkPenyesuaianBulan,0),
        FLOOR((DATEDIFF(NOW(),pegTglMasukInstitusi) MOD 365)/30)+IFNULL(mkPenyesuaianBulan,0)
    ) AS all_mk_bulan,
    
    a.pktgolrTingkat,
    c.kgbId AS id_kgb,
    c.kgbBlnMasaKerja AS mk_bln_kgb_old,
    c.kgbThnMasaKerja AS mk_thn_kgb_old,
    c.kgbTanggalAkanDatang AS next_kgb,
    c.kgbGajiPokokBaru AS gaji_kgb,
    c.kgbNomorPenetap AS no_kgb_old,
    c.kgbBerlakuTanggal AS kgb_berlaku_old,
    c.kgbTanggalPenetap AS tgl_kgb_lalu,
    c.kgbPejabatPenetap AS pjbt_kgb_old
    
FROM sdm_pangkat_golongan
    INNER JOIN pub_pegawai ON pegId = pktgolPegKode
    LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId = pegId AND satkerpegAktif = 'Aktif'
    LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId = satkerId
    LEFT JOIN sdm_ref_pangkat_golongan a ON a.pktgolrId = pktgolPktgolrId
    LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
    LEFT JOIN sdm_masa_kerja_penyesuaian ON mkPegKode=pegId
    LEFT JOIN sdm_kenaikan_gaji_berkala c ON c.kgbPegKode=pegId AND c.kgbAktif = 'Aktif'
WHERE 1=1
    AND c.kgbId = '%s'
    AND (satkerId = '%s' OR satkerLevel LIKE CONCAT('%s', '.%%') OR satkerId IS NULL)
ORDER BY pktgolrTingkat DESC,pktgolNaikPktYad DESC,satker_lvl ASC
) tmp
LIMIT 0, 1
) pegawai
    LEFT JOIN sdm_ref_daftar_gaji a ON a.pktGolGaji = pngkt AND a.mkDafGaji <= masa_kerja_tahun
ORDER BY a.mkDafGaji DESC
LIMIT 0, 1
";

$sql['count_sk_kgb_by_kgb_id'] = "
SELECT COUNT(*) AS `total`
FROM sdm_sk_kgb
WHERE id_kgb = '%s'
";

$sql['get_sk_kenaikan_gaji'] = "
SELECT 
    id_sk as id,
    id_kgb,
    id_peg,
    no_sk,
    tgl_sk,
    id_pngkt,
    mk_thn_old_kgb,
    mk_bln_old_kgb,
    new_id_gapok,
    new_gaji,
    mk_thn_new_kgb,
    mk_bln_new_kgb,
    tgl_mulai_kgb,
    tgl_yakd_kgb,
    pejabat_jbtn_sk,
    pejabat_sk,
    pejabat_pngkt_sk,
    pejabat_nip_sk,
    tembusan_sk,
    
    mk_thn_old_kgb AS mk_thn_kgb_old,
    mk_bln_old_kgb AS mk_bln_kgb_old,
    kgbTanggalAkanDatang AS next_kgb,
    kgbGajiPokokBaru AS gaji_kgb,
    mk_thn_new_kgb AS masa_kerja_tahun,
    mk_bln_new_kgb AS masa_kerja_bulan,
    tgl_mulai_kgb AS start_sk,
    tgl_yakd_kgb AS next_sk,
    
    kgbGajiPokokBaru AS old_gaji,
    kgbNomorPenetap AS no_kgb_old,
    kgbBerlakuTanggal AS kgb_berlaku_old,
    kgbTanggalPenetap AS tgl_kgb_lalu,
    kgbPejabatPenetap AS pjbt_kgb_old,
    
    pegKodeResmi AS nip,
    pegNama AS nama,
    CONCAT(IFNULL(pegGelarDepan, ''), IF(IFNULL(pegGelarDepan, '') = '', '', ' '), pegNama, IF(IFNULL(pegGelarBelakang, '') = '', '', ', '), IFNULL(pegGelarBelakang, '')) AS nama_gelar,
    pegTglLahir AS tgl_lahir,
    pegTmpLahir AS tmp_lahir,
    pktgolrId AS pngkt,
    pktgolrNama AS pngkt_nama,
    satkerId AS satker_id,
    satkerNama AS satker,
    (LENGTH(satkerLevel) - LENGTH(REPLACE(satkerLevel, '.', ''))) AS satker_lvl
FROM sdm_sk_kgb
    LEFT JOIN sdm_kenaikan_gaji_berkala ON kgbId=id_kgb AND kgbAktif = 'Aktif'
    LEFT JOIN pub_pegawai ON pegId=kgbPegKode
    LEFT JOIN sdm_ref_pangkat_golongan a ON pktgolrId = id_pngkt
    LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId = pegId AND satkerpegAktif = 'Aktif'
    LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId = satkerId
WHERE 1=1
    --where--
ORDER BY id_sk DESC
LIMIT 0, 1
";

$sql['get_satker_and_level']="
SELECT 
a.satkerId,
a.satkerLevel
FROM 
pub_satuan_kerja AS a
INNER JOIN gtfw_user_satuan_kerja ON 
userunitSatuanKerjaId=satkerId 
WHERE userunitUserId='%s' 
";

$sql['do_add'] = "
INSERT INTO
    sdm_sk_kgb
(id_kgb, id_peg, no_sk, tgl_sk, id_pngkt, mk_thn_old_kgb, mk_bln_old_kgb,
new_id_gapok, new_gaji, mk_thn_new_kgb, mk_bln_new_kgb, tgl_mulai_kgb, tgl_yakd_kgb,
pejabat_jbtn_sk, pejabat_sk, pejabat_pngkt_sk, pejabat_nip_sk, tembusan_sk)
VALUES (
    '%s',
    '%s',
    '%s',
    '%s',
    '%s',
    '%s',
    '%s',
    '%s',
    '%s',
    '%s',
    '%s',
    '%s',
    '%s',
    '%s',
    '%s',
    '%s',
    '%s',
    '%s'
)
";

$sql['do_update'] = "
UPDATE
    sdm_sk_kgb
SET
    id_peg = '%s',
    no_sk = '%s',
    tgl_sk = '%s',
    id_pngkt = '%s',
    mk_thn_old_kgb = '%s',
    mk_bln_old_kgb = '%s',
    new_id_gapok = '%s',
    new_gaji = '%s',
    mk_thn_new_kgb = '%s',
    mk_bln_new_kgb = '%s',
    tgl_mulai_kgb = '%s',
    tgl_yakd_kgb = '%s',
    pejabat_jbtn_sk = '%s',
    pejabat_sk = '%s',
    pejabat_pngkt_sk = '%s',
    pejabat_nip_sk = '%s',
    tembusan_sk = '%s'
WHERE id_sk = '%s'
";
?>
