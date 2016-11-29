<?php
$sql['get_combo_pangkat_golongan'] = "
SELECT
  pktgolrId as id,
  concat(pktgolrId,' - ',pktgolrNama) as name
FROM 
  sdm_ref_pangkat_golongan
ORDER BY pktgolrUrut
";

$sql['get_combo_jabatan_fungsional'] = "
SELECT
  jabfungrId as id,
  jabfungrNama as name
FROM 
  pub_ref_jabatan_fungsional
ORDER BY jabfungrNama ASC
";

$sql['get_list_pangkat_fungsional']="
SELECT
	id,id_pangkat,nama,nama_gelar,nip,jenis_kelamin,jenis_pegawai,
	tgl_naik,golongan,golongan_nama,golongan_tmt,jabatan,jabatan_tmt,
	
	pegTmpLahir as tempat_lahir,
	date_format(pegTglLahir, '%d-%m-%Y') as tanggal_lahir,
	date_format(pegPnsTmt, '%d-%m-%Y') as pegPnsTmt,
	date_format(pegCpnsTmt, '%d-%m-%Y') as pegCpnsTmt,
	statnkhNama,
	agmNama,
	jbtnEselon,
	statrPegawai,
	usia,
	asal,
	kecamatan,
	status_pangkat,
	status,
	
	IF(MIN(pktgolrTingkat)=1 AND MAX(pktgolrTingkat)>=2,
		CAST(IF(masa_kerja_bulan>=12,masa_kerja_tahun+1,masa_kerja_tahun) AS SIGNED)-6,
		IF(MIN(pktgolrTingkat)<=2 AND MAX(pktgolrTingkat)>=3,
			CAST(IF(masa_kerja_bulan>=12,masa_kerja_tahun+1,masa_kerja_tahun) AS SIGNED)-5,
			IF(masa_kerja_bulan>=12,masa_kerja_tahun+1,masa_kerja_tahun)
		)
	) AS masa_kerja_tahun,
	IF(masa_kerja_bulan>=12,masa_kerja_bulan MOD 12,masa_kerja_bulan) AS masa_kerja_bulan,
		GROUP_CONCAT(DISTINCT pelThnIjazah SEPARATOR '|') AS latihan_tahun,
		GROUP_CONCAT(DISTINCT pelNama SEPARATOR '|') AS latihan_nama,
		GROUP_CONCAT(DISTINCT pelJmlJam SEPARATOR '|') AS latihan_jam,
	
	pendidikan_nama,pendidikan_jurusan,pendidikan_lulus,pendidikan_tingkat,
	unit_kerja,sub_unit_kerja,
	
	(
		select kgbBerlakuTanggal
		from sdm_kenaikan_gaji_berkala
		where kgbPegKode = id and kgbAktif = 'Aktif'
		order by kgbBerlakuTanggal desc
		limit 0,1
	) as berkala
FROM
	(SELECT
		pegId AS id,
		pegNama AS nama,
		CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,'')) AS nama_gelar,
		pegKodeResmi AS nip,
		pegKelamin AS jenis_kelamin,
		
		pegTmpLahir,
		pegTglLahir,
		pegPnsTmt,
		pegCpnsTmt,
		statnkhNama,
		agmNama,
		pegAsalDesa as asal,
		pegKecamatan as kecamatan,
		ROUND(IF(YEAR(pegTglLahir)='0000' OR MONTH(pegTglLahir)='00' OR DATE(pegTglLahir)='00',0,DATEDIFF(NOW(),pegTglLahir))/365) as usia,
		
		jnspegrNama AS jenis_pegawai,
	
		pktgolId as id_pangkat,
		pktgolNaikPktYad as tgl_naik,
		pktgolPktgolrId AS golongan,
		pktref.pktgolrNama AS golongan_nama,
		pktgolTmt AS golongan_tmt,
	
		jabstrukrNama AS jabatan,
		jbtnTglMulai AS jabatan_tmt,
		jbtnEselon,
	
		IF(jnspegrNama IN ('PNS','CPNS'),
			pktref.pktgolrMasaKerja+FLOOR(DATEDIFF(NOW(),pegCpnsTmt)/365)+IFNULL(mkPenyesuaianTahun,0),
			pktref.pktgolrMasaKerja+FLOOR(DATEDIFF(NOW(),pegTglMasukInstitusi)/365)+IFNULL(mkPenyesuaianTahun,0)
		)AS masa_kerja_tahun,
		IF(jnspegrNama IN ('PNS','CPNS'),
			FLOOR((DATEDIFF(NOW(),pegCpnsTmt) MOD 365)/30)+IFNULL(mkPenyesuaianBulan,0),
			FLOOR((DATEDIFF(NOW(),pegTglMasukInstitusi) MOD 365)/30)+IFNULL(mkPenyesuaianBulan,0)
		)AS masa_kerja_bulan,
	
		pelThnIjazah,
		pelNama,
		pelJmlJam,
	
		pddkInstitusi AS pendidikan_nama,
		pddkJurusan AS pendidikan_jurusan,
		pddkThnLulus AS pendidikan_lulus,
		pendNama AS pendidikan_tingkat,
	
		unit.satkerNama AS unit_kerja,
		subunit.satkerNama AS sub_unit_kerja,
		(LENGTH(subunit.satkerLevel) - LENGTH(REPLACE(subunit.satkerLevel, '.', ''))) AS satker_lvl,
	
		pktref.pktgolrTingkat,
		pktref.pktgolrUrut,
		statrPegawai,
		
		(pkt.pktgolStatus = 'Aktif') AS status_pangkat,
		(id_pktgol IS NULL) AS status
		
	FROM
		pub_pegawai
		LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
	
		LEFT JOIN sdm_pangkat_golongan pkt ON pkt.pktgolPegKode=pegId
		/*
		LEFT JOIN 
			(SELECT * FROM sdm_pangkat_golongan ORDER BY pktgolStatus ASC, pktgolId DESC) AS why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
		*/
		LEFT JOIN sdm_ref_pangkat_golongan pktref ON pktgolrId=pktgolPktgolrId 
		INNER JOIN sdm_ref_pangkat_golongan zz ON zz.pktgolrUrut > pktref.pktgolrUrut 
	
		LEFT JOIN sdm_jabatan_struktural ON  jbtnPegKode=pegId AND jbtnStatus='Aktif'
		LEFT JOIN sdm_ref_jabatan_struktural ON jbtnJabstrukrId=jabstrukrId
	
		LEFT JOIN 
			/*(select * from (select * from sdm_pelatihan ORDER BY pelId DESC) as a GROUP by pelPegKode ) as a */
			sdm_pelatihan a ON a.pelPegKode=pub_pegawai.pegId
			/*ON pelPegKode=pegId */
		LEFT JOIN sdm_ref_jenis_pelatihan ON pelJnspelrId=jnspelrId
	
		LEFT JOIN sdm_pendidikan ON pddkPegKode=pegId AND pddkStatusTamat='Selesai'
		LEFT JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
		/*
		LEFT JOIN 
			(SELECT * FROM (
					SELECT * FROM 
						sdm_pendidikan 
						INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
					WHERE pddkStatusTamat='Selesai'
					ORDER BY pendPendkelId DESC
			) AS b GROUP BY pddkPegKode ) AS b
			ON pddkPegKode=pegId
		*/
	
		LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
		LEFT JOIN pub_satuan_kerja subunit ON subunit.satkerId=satkerpegSatkerId
		LEFT JOIN pub_satuan_kerja unit ON unit.satkerId=subunit.satkerParentId
		LEFT JOIN sdm_masa_kerja_penyesuaian ON mkPegKode=pegId
		
		LEFT JOIN sdm_ref_status_pegawai ON statrId=pegStatrId
		LEFT JOIN pub_ref_status_nikah ON statnkhId=pegStatnikahId
		LEFT JOIN pub_ref_agama ON agmId=pegAgamaId
        %status1%
	WHERE 1=1
        %awal_akhir%
        %pangkat_golongan%
        %unit_kerja%
        %status2%
	ORDER BY pktgolNaikPktYad DESC, pktgolrTingkat DESC, pktgolrUrut DESC, pelThnIjazah DESC, pddkThnLulus DESC, satker_lvl ASC ) as temp
GROUP BY id, id_pangkat
ORDER BY tgl_naik DESC, pktgolrTingkat DESC, pktgolrUrut DESC, CAST(pegPnsTmt AS DATE) ASC
%limit%
";

$sql['get_data_pegawai'] = "
SELECT *,
    a.idDafGaji AS old_ref_gaji,
    a.mkDafGaji AS old_ref_gaji_mk,
    a.nominalDafGaji AS old_gaji,
    b.idDafGaji AS new_ref_gaji,
    b.mkDafGaji AS new_ref_gaji_mk,
    b.nominalDafGaji AS new_gaji
FROM (
SELECT *,
    IF(MIN(pktgolrTingkat) = 1 AND MAX(pktgolrTingkat) >= 2,
        CAST(IF(mk_bulan >= 12, mk_tahun + 1, mk_tahun) AS SIGNED) - 6,
        IF(MIN(pktgolrTingkat) <= 2 AND MAX(pktgolrTingkat) >= 3,
            CAST(IF(mk_bulan >= 12, mk_tahun + 1, mk_tahun) AS SIGNED) - 5,
            IF(mk_bulan >= 12, mk_tahun + 1, mk_tahun)
        )
    ) AS masa_kerja_tahun,
    IF(mk_bulan >= 12, mk_bulan MOD 12, mk_bulan) AS masa_kerja_bulan
FROM (
SELECT
    pegId as id,
    pegKodeResmi as nip,
    pegNama as nama,
    CONCAT(IFNULL(pegGelarDepan, ''), IF(IFNULL(pegGelarDepan, '') = '', '', ' '), pegNama, IF(IFNULL(pegGelarBelakang, '') = '', '', ', '), IFNULL(pegGelarBelakang, '')) AS nama_gelar,
    pegTglLahir as tgl_lahir,
    pegTmpLahir as tmp_lahir,
    pddkId as pend_id,
    pendNama as pend_tingkat,
    pddkJurusan as pend_jurusan,
    CONCAT(IFNULL(pendNama, ''), ' ', IFNULL(pddkJurusan, '')) as pendidikan,
    a.pktgolrId as old_pngkt,
    a.pktgolrNama as old_pngkt_nama,
    pktgolTmt as old_pngkt_tmt,
    pktgolNaikPktYad as tgl_naik,
    a.pktgolrUrut as old_pngkt_urut,
    b.pktgolrId as new_pngkt,
    b.pktgolrNama as new_pngkt_nama,
    b.pktgolrUrut as new_pngkt_urut,
    jabfung.jbtnId as old_jab_id,
    jabfungrNama as old_jab_nama,
    jabfung.jbtnTglMulai as old_jab_tmt,
    satkerId as satker_id,
    satkerNama as satker,
    (LENGTH(satkerLevel) - LENGTH(REPLACE(satkerLevel, '.', ''))) AS satker_lvl,
    IF(jnspegrNama IN ('PNS','CPNS'),
        a.pktgolrMasaKerja+FLOOR(DATEDIFF(NOW(),pegCpnsTmt)/365)+IFNULL(mkPenyesuaianTahun,0),
        a.pktgolrMasaKerja+FLOOR(DATEDIFF(NOW(),pegTglMasukInstitusi)/365)+IFNULL(mkPenyesuaianTahun,0)
    ) AS mk_tahun,
    IF(jnspegrNama IN ('PNS','CPNS'),
        FLOOR((DATEDIFF(NOW(),pegCpnsTmt) MOD 365)/30)+IFNULL(mkPenyesuaianBulan,0),
        FLOOR((DATEDIFF(NOW(),pegTglMasukInstitusi) MOD 365)/30)+IFNULL(mkPenyesuaianBulan,0)
    ) AS mk_bulan,
    a.pktgolrTingkat,
    pktgolId
FROM sdm_pangkat_golongan
    INNER JOIN pub_pegawai ON pegId = pktgolPegKode
    LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId = pegId AND satkerpegAktif = 'Aktif'
    LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId = satkerId
    LEFT JOIN sdm_ref_pangkat_golongan a ON a.pktgolrId = pktgolPktgolrId
    LEFT JOIN sdm_ref_pangkat_golongan b ON b.pktgolrUrut > a.pktgolrUrut
    LEFT JOIN sdm_jabatan_fungsional jabfung ON jabfung.jbtnPegKode = pegId AND jabfung.jbtnStatus = 'Aktif'
    LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId = jabfung.jbtnJabfungrId
    LEFT JOIN sdm_pendidikan ON pddkPegKode = pegId AND pddkStatusTamat = 'Selesai'
    LEFT JOIN pub_ref_pendidikan ON pendId = pddkTkpddkrId
    LEFT JOIN pub_ref_satuan_wilayah ON pddkNegaraId = satwilId
    LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
    LEFT JOIN sdm_masa_kerja_penyesuaian ON mkPegKode=pegId
WHERE 1=1
    AND pktgolId = '%s'
    AND (satkerId = '%s' OR satkerLevel LIKE CONCAT('%s', '.%%') OR satkerId IS NULL)
ORDER BY pktgolNaikPktYad DESC, pddkThnLulus DESC, jabfung.jbtnTglMulai DESC, b.pktgolrUrut ASC, satker_lvl ASC
) tmp
LIMIT 0, 1
) pegawai
    LEFT JOIN sdm_ref_daftar_gaji a ON a.pktGolGaji = old_pngkt AND a.mkDafGaji < masa_kerja_tahun
    LEFT JOIN sdm_ref_daftar_gaji b ON b.pktGolGaji = new_pngkt AND b.mkDafGaji <= masa_kerja_tahun
ORDER BY b.mkDafGaji DESC, a.mkDafGaji DESC
LIMIT 0, 1
";

$sql['get_pangkat_pegawai']="
SELECT
	pegId,
	pegKodeResmi AS nip,
	CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,'')) AS nama,
	pktgolNaikPktYad AS tanggal,
	satkerNama AS satker,
	(LENGTH(satkerLevel) - LENGTH(REPLACE(satkerLevel, '.', ''))) AS satker_lvl,
	pktgolrId as pktgol,
	pktgolrNama as pktgolrNama
FROM
	pub_pegawai
	INNER JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan ON pktgolPktgolrId=pktgolrId
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
WHERE 1=1
    AND pegId = '%s'
    AND (satkerId = '%s' OR satkerLevel LIKE CONCAT('%s', '.%%') OR satkerId IS NULL)
ORDER BY tanggal DESC, satker_lvl ASC
";

$sql['count_sk_pangkat_fungsional_by_pktgol_id'] = "
SELECT COUNT(*) AS `total`
FROM sdm_sk_pangkat_fungsional
WHERE id_pktgol = '%s'
";

$sql['get_sk_pangkat_fungsional'] = "
SELECT
    id_sk as id,
    id_pktgol,
    no_sk,
    agree_no,
    agree_date,
    id_pend,
    id_old_jab,
    old_ak,
    id_satker,
    start_sk,
    new_pngkt_name,
    id_pngkt,
    id_new_jab,
    name_new_jab,
    new_ak,
    mk_thn,
    mk_bln,
    GjPokok,
    id_ref_gjPokok,
    issue_place,
    issue_date,
    official_sk,
    barcode_sk,
    tembusan_sk,
    
    pegNama as nama,
    CONCAT(IFNULL(pegGelarDepan, ''), IF(IFNULL(pegGelarDepan, '') = '', '', ' '), pegNama, IF(IFNULL(pegGelarBelakang, '') = '', '', ', '), IFNULL(pegGelarBelakang, '')) AS nama_gelar,
    pegKodeResmi as nip,
    pegTglLahir as tgl_lahir,
    pegTmpLahir as tmp_lahir,
    pendNama as pend_tingkat,
    pddkJurusan as pend_jurusan,
    CONCAT(IFNULL(pendNama, ''), ' ', IFNULL(pddkJurusan, '')) as pendidikan,
    pktgolNaikPktYad as tgl_naik,
    oldPngkt.pktgolrId as old_pngkt,
    oldPngkt.pktgolrNama as old_pngkt_nama,
    oldPngkt.pktgolrTingkat as old_pngkt_tingkat,
    pktgolTmt as old_pngkt_tmt,
    newPngkt.pktgolrId as new_pngkt,
    newPngkt.pktgolrNama as new_pngkt_nama,
    newPngkt.pktgolrTingkat as new_pngkt_tingkat,
    oldJabfung.jabfungrNama as old_jab_nama,
    newJabfung.jabfungrNama as new_jab,
    satkerNama as satker,
    mk_thn as masa_kerja_tahun,
    mk_bln as masa_kerja_bulan,
    oldGaji.nominalDafGaji as old_gaji,
    newGaji.nominalDafGaji as new_gaji_ref,
    GjPokok as new_gaji
FROM sdm_sk_pangkat_fungsional
    LEFT JOIN sdm_pangkat_golongan ON pktgolId = id_pktgol
    LEFT JOIN sdm_ref_pangkat_golongan oldPngkt ON oldPngkt.pktgolrId = pktgolPktgolrId
    LEFT JOIN pub_pegawai ON pegId = pktgolPegKode
    LEFT JOIN sdm_ref_pangkat_golongan newPngkt on newPngkt.pktgolrId = id_pngkt
    LEFT JOIN pub_ref_jabatan_fungsional newJabfung ON newJabfung.jabfungrId = id_new_jab
    LEFT JOIN sdm_ref_daftar_gaji newGaji ON newGaji.idDafGaji = id_ref_gjPokok
    LEFT JOIN sdm_pendidikan ON pddkId = id_pend
    LEFT JOIN pub_ref_pendidikan ON pendId = pddkTkpddkrId
    LEFT JOIN sdm_jabatan_fungsional ON jbtnId = id_old_jab
    LEFT JOIN pub_ref_jabatan_fungsional oldJabfung ON oldJabfung.jabfungrId = jbtnJabfungrId
    LEFT JOIN pub_satuan_kerja ON satkerId = id_satker
    LEFT JOIN sdm_ref_daftar_gaji oldGaji ON oldGaji.pktGolGaji = oldPngkt.pktgolrId AND oldGaji.mkDafGaji < mk_thn
WHERE 1=1
    --where--
ORDER BY id_sk DESC, oldGaji.mkDafGaji DESC
LIMIT 0, 1
";

$sql['do_add'] = "
INSERT INTO
    sdm_sk_pangkat_fungsional
(id_pktgol, no_sk, agree_no, agree_date, id_pend, id_old_jab, old_ak, id_satker,
start_sk, new_pngkt_name, id_pngkt, id_new_jab, name_new_jab, new_ak, mk_thn, mk_bln,
GjPokok, id_ref_gjPokok, issue_place, issue_date, official_sk, barcode_sk, tembusan_sk)
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
    sdm_sk_pangkat_fungsional
SET
    no_sk = '%s',
    agree_no = '%s',
    agree_date = '%s',
    id_pend = '%s',
    id_old_jab = '%s',
    old_ak = '%s',
    id_satker = '%s',
    start_sk = '%s',
    new_pngkt_name = '%s',
    id_pngkt = '%s',
    id_new_jab = '%s',
    name_new_jab = '%s',
    new_ak = '%s',
    mk_thn = '%s',
    mk_bln = '%s',
    GjPokok = '%s',
    id_ref_gjPokok = '%s',
    issue_place = '%s',
    issue_date = '%s',
    official_sk = '%s',
    barcode_sk = '%s',
    tembusan_sk = '%s'
WHERE id_sk = '%s'
";

?>
