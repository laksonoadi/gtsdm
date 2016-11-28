<?php

// GET DATA COMBO START ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

$sql['get_combo_fungsional'] = "
SELECT
  jabfungrId as id,
  jabfungrNama as name
FROM 
  pub_ref_jabatan_fungsional
ORDER BY jabfungrId
";

$sql['get_combo_pendidikan'] = "
SELECT
  pendId as id,
  pendNama as name
FROM 
  pub_ref_pendidikan
ORDER BY pendId
";

$sql['get_fakultas'] = "
SELECT
  satkerId as idFak,
  satkerNama as nameFak
FROM 
  pub_satuan_kerja
  %fakultas%
ORDER BY satkerId
";

// GET DATA COMBO END ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// GET DATA SKS GANJIL START ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql['get_sks_pend_ganjil'] = "
	SELECT SUM(sdm_bkd_pendidikan.bkdpendKinerjaSks) AS sum_pend_ganjil
	FROM unijoyo_gtsdm_devel.sdm_bkd_pendidikan INNER JOIN unijoyo_gtsdm_devel.sdm_bkd ON (sdm_bkd_pendidikan.bkdpendBkdId = sdm_bkd.bkdId)        
	WHERE bkdPegId = '%s'
	AND bkdpendRekomendasi != -1
	AND sdm_bkd.bkdSemester = 'Ganjil' ";

$sql['get_sks_penl_ganjil'] = "
	SELECT SUM(sdm_bkd_penelitian.bkdpenKinerjaSks) AS sum_penl_ganjil
	FROM unijoyo_gtsdm_devel.sdm_bkd_penelitian INNER JOIN unijoyo_gtsdm_devel.sdm_bkd ON (sdm_bkd_penelitian.bkdpenBkdId = sdm_bkd.bkdId)        
	WHERE bkdPegId = '%s'
	AND bkdpenRekomendasi != -1
	AND sdm_bkd.bkdSemester = 'Ganjil' ";

$sql['get_sks_peng_ganjil'] = "
	SELECT SUM(sdm_bkd_pengabdian.bkdpengKinerjaSks) AS sum_peng_ganjil
	FROM unijoyo_gtsdm_devel.sdm_bkd_pengabdian INNER JOIN unijoyo_gtsdm_devel.sdm_bkd ON (sdm_bkd_pengabdian.bkdpengBkdId = sdm_bkd.bkdId)        
	WHERE bkdPegId = '%s'
	AND bkdpengRekomendasi != -1
	AND sdm_bkd.bkdSemester = 'Ganjil' ";

$sql['get_sks_penu_ganjil'] = "
	SELECT SUM(sdm_bkd_penunjang.bkdpenuKinerjaSks) AS sum_penu_ganjil
	FROM unijoyo_gtsdm_devel.sdm_bkd_penunjang INNER JOIN unijoyo_gtsdm_devel.sdm_bkd ON (sdm_bkd_penunjang.bkdpenuBkdId = sdm_bkd.bkdId)        
	WHERE bkdPegId = '%s'
	AND bkdpenuRekomendasi != -1
	AND sdm_bkd.bkdSemester = 'Ganjil' ";
// GET DATA SKS GANJIL END ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// GET DATA SKS GENAP START ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql['get_sks_pend_genap'] = "
	SELECT SUM(sdm_bkd_pendidikan.bkdpendKinerjaSks) AS sum_pend_genap
	FROM unijoyo_gtsdm_devel.sdm_bkd_pendidikan INNER JOIN unijoyo_gtsdm_devel.sdm_bkd ON (sdm_bkd_pendidikan.bkdpendBkdId = sdm_bkd.bkdId)        
	WHERE bkdPegId = '%s'
	AND bkdpendRekomendasi != -1
	AND sdm_bkd.bkdSemester = 'Genap' ";

$sql['get_sks_penl_genap'] = "
	SELECT SUM(sdm_bkd_penelitian.bkdpenKinerjaSks) AS sum_penl_genap
	FROM unijoyo_gtsdm_devel.sdm_bkd_penelitian INNER JOIN unijoyo_gtsdm_devel.sdm_bkd ON (sdm_bkd_penelitian.bkdpenBkdId = sdm_bkd.bkdId)        
	WHERE bkdPegId = '%s'
	AND bkdpenRekomendasi != -1
	AND sdm_bkd.bkdSemester = 'Genap' ";

$sql['get_sks_peng_genap'] = "
	SELECT SUM(sdm_bkd_pengabdian.bkdpengKinerjaSks) AS sum_peng_genap
	FROM unijoyo_gtsdm_devel.sdm_bkd_pengabdian INNER JOIN unijoyo_gtsdm_devel.sdm_bkd ON (sdm_bkd_pengabdian.bkdpengBkdId = sdm_bkd.bkdId)        
	WHERE bkdPegId = '%s'
	AND bkdpengRekomendasi != -1
	AND sdm_bkd.bkdSemester = 'Genap' ";

$sql['get_sks_penu_genap'] = "
	SELECT SUM(sdm_bkd_penunjang.bkdpenuKinerjaSks) AS sum_penu_genap
	FROM unijoyo_gtsdm_devel.sdm_bkd_penunjang INNER JOIN unijoyo_gtsdm_devel.sdm_bkd ON (sdm_bkd_penunjang.bkdpenuBkdId = sdm_bkd.bkdId)        
	WHERE bkdPegId = '%s'
	AND bkdpenuRekomendasi != -1
	AND sdm_bkd.bkdSemester = 'Genap' ";
// GET DATA SKS GENAP END ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$sql['get_sks_prof'] = "
	SELECT SUM(sdm_bkd_profesor.bkdprofKinerjaSks) AS sum_prof
	FROM unijoyo_gtsdm_devel.sdm_bkd_profesor INNER JOIN unijoyo_gtsdm_devel.sdm_bkd ON (sdm_bkd_profesor.bkdprofBkdId = sdm_bkd.bkdId)        
	WHERE bkdPegId = '%s'
	AND bkdprofRekomendasi != -1";




// BUAT LAPORAN REKAPITULASI ---------------------------------------------------------------------------------------------------------------------------
$sql['get_data_rekapitulasi_bkd']="
SELECT
    pub_pegawai.pegId AS id
    , sdm_bkd.bkdId AS bkd_id
    , pub_pegawai.pegKodeResmi AS nip
    , sdm_bkd.bkdNoSertifikasi AS no_sertifikasi
    , CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,'')) AS nama
    , CONCAT(sdm_bkd.bkdTahunAkademik,'/',(sdm_bkd.bkdTahunAkademik + 1)) AS tahun_akademik
    , sdm_bkd.bkdTahunAkademik AS thnAkd
	, (sdm_bkd.bkdTahunAkademik + 1) as thnakd_
    , sdm_bkd.bkdSemester AS semester
    , sdm_bkd.bkdKesimpulan AS kesimpulan
    ,
		CASE
			WHEN
				bkdJenis = 'DS' THEN 'Dosen Biasa'
			WHEN 
				bkdJenis = 'PR' THEN 'Profesor'
			WHEN 
				bkdJenis = 'DT' THEN 'Dosen Dengan Tugas Tambahan'
			WHEN 
				bkdJenis = 'PT' THEN 'Profesor Dengan Tugas  Tambahan'
		END AS bkdJenis
FROM
    unijoyo_gtsdm_devel.pub_pegawai
    INNER JOIN unijoyo_gtsdm_devel.sdm_bkd 
        ON (pub_pegawai.pegId = sdm_bkd.bkdPegId)
    INNER JOIN unijoyo_gtsdm_devel.sdm_ref_jenis_pegawai 
        ON (pub_pegawai.pegJnspegrId = sdm_ref_jenis_pegawai.jnspegrId)
    INNER JOIN unijoyo_gtsdm_devel.pub_ref_jabatan_fungsional 
        ON (sdm_bkd.bkdJabfungrId = pub_ref_jabatan_fungsional.jabfungrId)
	INNER JOIN unijoyo_gtsdm_devel.sdm_jabatan_fungsional 
        ON (pub_ref_jabatan_fungsional.jabfungrId = sdm_jabatan_fungsional.jbtnJabfungrId)
	INNER JOIN unijoyo_gtsdm_devel.sdm_pangkat_golongan 
        ON (sdm_pangkat_golongan.pktgolPegKode = pub_pegawai.pegId)
    INNER JOIN unijoyo_gtsdm_devel.sdm_satuan_kerja_pegawai 
        ON (sdm_satuan_kerja_pegawai.satkerpegPegId = pub_pegawai.pegId)
    INNER JOIN unijoyo_gtsdm_devel.pub_satuan_kerja 
        ON (sdm_satuan_kerja_pegawai.satkerpegSatkerId = pub_satuan_kerja.satkerId)
	LEFT JOIN 
		(SELECT * FROM (
				SELECT * FROM 
					sdm_pendidikan 
					INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
				WHERE pddkStatusTamat='Selesai'
				ORDER BY pendPendkelId DESC
		) AS b GROUP BY pddkPegKode ) AS b
		ON pddkPegKode=pegId
		
	WHERE
		pegId<>-1
		%pendidikan%
		%fungsional%
		%pangkat_golongan%
		%unit_kerja%
		%fakultas%
	GROUP BY 
		sdm_bkd.bkdPegId
	ORDER BY 
		sdm_bkd.bkdPegId DESC
";


// BUAT LAPORAN BKD ----------------------------------------------------------------------------------------------------------------------------------
$sql['get_data_bkd']="
SELECT
    pub_pegawai.pegId AS id
    , pub_pegawai.pegKodeResmi AS nip
    , CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,'')) AS nama
    , pub_pegawai.pegKelamin AS jenis_kelamin
    , sdm_ref_jenis_pegawai.jnspegrNama AS jenis_pegawai
    , sdm_pangkat_golongan.pktgolPktgolrId AS golongan
    , sdm_pangkat_golongan.pktgolTmt AS golongan_tmt
    , pub_ref_jabatan_fungsional.jabfungrNama AS jabfung
	, sdm_jabatan_fungsional.jbtnTglMulai AS jabatan_tmt
    , pub_pegawai.pegTglLahir AS tanggal_lahir
    , pub_satuan_kerja.satkerNama AS unit_kerja
	, pddkInstitusi AS pendidikan_nama
	, pddkJurusan AS pendidikan_jurusan
	, pddkThnLulus AS pendidikan_lulus
	, pendNama AS pendidikan_tingkat    
FROM
    unijoyo_gtsdm_devel.pub_pegawai
    INNER JOIN unijoyo_gtsdm_devel.sdm_bkd 
        ON (pub_pegawai.pegId = sdm_bkd.bkdPegId)
    INNER JOIN unijoyo_gtsdm_devel.sdm_ref_jenis_pegawai 
        ON (pub_pegawai.pegJnspegrId = sdm_ref_jenis_pegawai.jnspegrId)
    INNER JOIN unijoyo_gtsdm_devel.pub_ref_jabatan_fungsional 
        ON (sdm_bkd.bkdJabfungrId = pub_ref_jabatan_fungsional.jabfungrId)
	INNER JOIN unijoyo_gtsdm_devel.sdm_jabatan_fungsional 
        ON (pub_ref_jabatan_fungsional.jabfungrId = sdm_jabatan_fungsional.jbtnJabfungrId)
    INNER JOIN unijoyo_gtsdm_devel.sdm_pangkat_golongan 
        ON (sdm_pangkat_golongan.pktgolPegKode = pub_pegawai.pegId)
    INNER JOIN unijoyo_gtsdm_devel.sdm_satuan_kerja_pegawai 
        ON (sdm_satuan_kerja_pegawai.satkerpegPegId = pub_pegawai.pegId)
    INNER JOIN unijoyo_gtsdm_devel.pub_satuan_kerja 
        ON (sdm_satuan_kerja_pegawai.satkerpegSatkerId = pub_satuan_kerja.satkerId)
	LEFT JOIN 
		(SELECT * FROM (
				SELECT * FROM 
					sdm_pendidikan 
					INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
				WHERE pddkStatusTamat='Selesai'
				ORDER BY pendPendkelId DESC
		) AS b GROUP BY pddkPegKode ) AS b
		ON pddkPegKode=pegId
		
	WHERE
		pegId<>-1
		%pendidikan%
		%fungsional%
		%pangkat_golongan%
		%unit_kerja%
	GROUP BY 
		sdm_bkd.bkdPegId
	ORDER BY 
		sdm_bkd.bkdPegId DESC
	%limit%
";


// BUAT DETAIL LAPORAN ----------------------------------------------------------------------------------------------------------------------------------
$sql['get_data_bkd_detail']="
SELECT
    pub_pegawai.pegId AS id
    , sdm_bkd.bkdId AS bkd_id
    , pub_pegawai.pegKodeResmi AS nip
    , sdm_bkd.bkdNoSertifikasi AS no_sertifikasi
    , CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,'')) AS nama
    , CONCAT(sdm_bkd.bkdTahunAkademik,'/',(sdm_bkd.bkdTahunAkademik + 1)) AS tahun_akademik
    , sdm_bkd.bkdTahunAkademik AS thnAkd
	, (sdm_bkd.bkdTahunAkademik + 1) as thnakd_
    , sdm_bkd.bkdSemester AS semester
    ,
		(SELECT 
			CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,''))
			FROM pub_pegawai WHERE pegId = bkdPegIdAsesor1
		) AS asesor_1
    ,
    	(SELECT 
			CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,''))
			FROM pub_pegawai WHERE pegId = bkdPegIdAsesor2
		) AS asesor_2
    
    , DATE_FORMAT(sdm_bkd.bkdTglPengajuan, '%d/%m/%Y') AS tgl_pengajuan
    , DATE_FORMAT(sdm_bkd.bkdTglPenilaian, '%d/%m/%Y') AS tgl_penilaian
    , sdm_bkd.bkdTglPengajuan AS tanggal_pengajuan
    , sdm_bkd.bkdTglPenilaian AS tanggal_penilaian
    ,
		CASE
			WHEN 
				bkdJenis = 'DS' THEN 'Dosen Biasa'
			WHEN 
				bkdJenis = 'PR' THEN 'Profesor'
			WHEN 
				bkdJenis = 'DT' THEN 'Dosen Dengan Tugas Tambahan'
			WHEN 
				bkdJenis = 'PT' THEN 'Profesor Dengan Tugas  Tambahan'
		END AS bkdJenis

    , pub_pegawai.pegKelamin AS jenis_kelamin
    , sdm_ref_jenis_pegawai.jnspegrNama AS jenis_pegawai
    , sdm_pangkat_golongan.pktgolPktgolrId AS golongan
    , sdm_pangkat_golongan.pktgolTmt AS golongan_tmt
	, DATE_FORMAT(sdm_pangkat_golongan.pktgolTmt, '%d/%m/%Y') AS gol_tmt
    , pub_ref_jabatan_fungsional.jabfungrNama AS jabfung
    , sdm_jabatan_fungsional.jbtnTglMulai AS jabatan_tmt
	, DATE_FORMAT(sdm_jabatan_fungsional.jbtnTglMulai, '%d/%m/%Y') AS jab_tmt
    , pub_pegawai.pegTglLahir AS tanggal_lahir
    , pub_satuan_kerja.satkerNama AS unit_kerja
	, pddkInstitusi AS pendidikan_nama
	, pddkJurusan AS pendidikan_jurusan
	, pddkThnLulus AS pendidikan_lulus
	, pendNama AS pendidikan_tingkat    
FROM
    unijoyo_gtsdm_devel.pub_pegawai
    INNER JOIN unijoyo_gtsdm_devel.sdm_bkd 
        ON (pub_pegawai.pegId = sdm_bkd.bkdPegId)
    INNER JOIN unijoyo_gtsdm_devel.sdm_ref_jenis_pegawai 
        ON (pub_pegawai.pegJnspegrId = sdm_ref_jenis_pegawai.jnspegrId)
    INNER JOIN unijoyo_gtsdm_devel.pub_ref_jabatan_fungsional 
        ON (sdm_bkd.bkdJabfungrId = pub_ref_jabatan_fungsional.jabfungrId)
	INNER JOIN unijoyo_gtsdm_devel.sdm_jabatan_fungsional 
        ON (pub_ref_jabatan_fungsional.jabfungrId = sdm_jabatan_fungsional.jbtnJabfungrId)
	INNER JOIN unijoyo_gtsdm_devel.sdm_pangkat_golongan 
        ON (sdm_pangkat_golongan.pktgolPegKode = pub_pegawai.pegId)
    INNER JOIN unijoyo_gtsdm_devel.sdm_satuan_kerja_pegawai 
        ON (sdm_satuan_kerja_pegawai.satkerpegPegId = pub_pegawai.pegId)
    INNER JOIN unijoyo_gtsdm_devel.pub_satuan_kerja 
        ON (sdm_satuan_kerja_pegawai.satkerpegSatkerId = pub_satuan_kerja.satkerId)
	LEFT JOIN 
		(SELECT * FROM (
				SELECT * FROM 
					sdm_pendidikan 
					INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
				WHERE pddkStatusTamat='Selesai'
				ORDER BY pendPendkelId DESC
		) AS b GROUP BY pddkPegKode ) AS b
		ON pddkPegKode=pegId
		
	WHERE
		pegId<>-1
		%pendidikan%
		%fungsional%
		%pangkat_golongan%
		%unit_kerja%
		%jenis%
		%tahun%
		%semester%
		%idpegawai%
	GROUP BY 
		sdm_bkd.bkdId	
	ORDER BY 
		sdm_bkd.bkdId DESC
	%limit%
";



// BUAT DETAIL LAPORAN INDIVIDU ------------------------------------------------------------------------------------------------------------------------
$sql['get_data_bkd_detail_individu']="
SELECT
    pub_pegawai.pegId AS id
    , sdm_bkd.bkdId AS bkd_id
    , pub_pegawai.pegKodeResmi AS nip
    , sdm_bkd.bkdNoSertifikasi AS no_sertifikasi
    , sdm_bkd.bkdNamaPT AS nm_pt
    , sdm_bkd.bkdAlamatPT AS almt_pt
    , sdm_bkd.bkdFakultas AS fak
    , sdm_bkd.bkdProdi AS prodi
    , sdm_bkd.bkdS1 AS s1
    , sdm_bkd.bkdS2 AS s2
    , sdm_bkd.bkdS3 AS s3
    , sdm_bkd.bkdBidang AS bid_ilmu
    , sdm_bkd.bkdNoHp AS hp
	
    , CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,'')) AS nama
    , CONCAT(sdm_bkd.bkdTahunAkademik,'/',(sdm_bkd.bkdTahunAkademik + 1)) AS tahun_akademik
    , sdm_bkd.bkdTahunAkademik AS thnAkd
	, (sdm_bkd.bkdTahunAkademik + 1) as thnakd_
    , sdm_bkd.bkdSemester AS semester
    ,
		(SELECT 
			CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,''))
			FROM pub_pegawai WHERE pegId = bkdPegIdAsesor1
		) AS asesor_1
    ,
    	(SELECT 
			CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,''))
			FROM pub_pegawai WHERE pegId = bkdPegIdAsesor2
		) AS asesor_2
    
    , DATE_FORMAT(sdm_bkd.bkdTglPengajuan, '%d/%m/%Y') AS tgl_pengajuan
    , DATE_FORMAT(sdm_bkd.bkdTglPenilaian, '%d/%m/%Y') AS tgl_penilaian
    , sdm_bkd.bkdTglPengajuan AS tanggal_pengajuan
    , sdm_bkd.bkdTglPenilaian AS tanggal_penilaian
    ,
		CASE
			WHEN 
				bkdJenis = 'DS' THEN 'Dosen Biasa'
			WHEN 
				bkdJenis = 'PR' THEN 'Profesor'
			WHEN 
				bkdJenis = 'DT' THEN 'Dosen Dengan Tugas Tambahan'
			WHEN 
				bkdJenis = 'PT' THEN 'Profesor Dengan Tugas  Tambahan'
		END AS bkdJenis

    , pub_pegawai.pegKelamin AS jenis_kelamin
    , sdm_ref_jenis_pegawai.jnspegrNama AS jenis_pegawai
    , sdm_pangkat_golongan.pktgolPktgolrId AS golongan
    , sdm_pangkat_golongan.pktgolTmt AS golongan_tmt
	, DATE_FORMAT(sdm_pangkat_golongan.pktgolTmt, '%d/%m/%Y') AS gol_tmt
    , pub_ref_jabatan_fungsional.jabfungrNama AS jabfung
    , sdm_jabatan_fungsional.jbtnTglMulai AS jabatan_tmt
	, DATE_FORMAT(sdm_jabatan_fungsional.jbtnTglMulai, '%d/%m/%Y') AS jab_tmt
    , pub_pegawai.pegTglLahir AS tanggal_lahir
    , pub_satuan_kerja.satkerNama AS unit_kerja
	, pddkInstitusi AS pendidikan_nama
	, pddkJurusan AS pendidikan_jurusan
	, pddkThnLulus AS pendidikan_lulus
	, pendNama AS pendidikan_tingkat    
FROM
    unijoyo_gtsdm_devel.pub_pegawai
    INNER JOIN unijoyo_gtsdm_devel.sdm_bkd 
        ON (pub_pegawai.pegId = sdm_bkd.bkdPegId)
    INNER JOIN unijoyo_gtsdm_devel.sdm_ref_jenis_pegawai 
        ON (pub_pegawai.pegJnspegrId = sdm_ref_jenis_pegawai.jnspegrId)
    INNER JOIN unijoyo_gtsdm_devel.pub_ref_jabatan_fungsional 
        ON (sdm_bkd.bkdJabfungrId = pub_ref_jabatan_fungsional.jabfungrId)
	INNER JOIN unijoyo_gtsdm_devel.sdm_jabatan_fungsional 
        ON (pub_ref_jabatan_fungsional.jabfungrId = sdm_jabatan_fungsional.jbtnJabfungrId)
	INNER JOIN unijoyo_gtsdm_devel.sdm_pangkat_golongan 
        ON (sdm_pangkat_golongan.pktgolPegKode = pub_pegawai.pegId)
    INNER JOIN unijoyo_gtsdm_devel.sdm_satuan_kerja_pegawai 
        ON (sdm_satuan_kerja_pegawai.satkerpegPegId = pub_pegawai.pegId)
    INNER JOIN unijoyo_gtsdm_devel.pub_satuan_kerja 
        ON (sdm_satuan_kerja_pegawai.satkerpegSatkerId = pub_satuan_kerja.satkerId)
	LEFT JOIN 
		(SELECT * FROM (
				SELECT * FROM 
					sdm_pendidikan 
					INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
				WHERE pddkStatusTamat='Selesai'
				ORDER BY pendPendkelId DESC
		) AS b GROUP BY pddkPegKode ) AS b
		ON pddkPegKode=pegId
		
	WHERE
		pegId<>-1
		%idbkd%
	ORDER BY 
		sdm_bkd.bkdId DESC
	%limit%
";


// GET LIST PENDIDIKAN START ----------------------------------------------------------------------------------------------------------------------
$sql['get_data_bkd_pendidikan']="
	SELECT 
		a.bkdpendId AS idFix,
		a.bkdpendBkdId AS bkdId,
		a.bkdpendJenisKegiatan AS nmKeg,
		a.bkdpendBebanKerjaBukti AS bkBukti,
		a.bkdpendBebanKerjaSks AS bkSks,
		a.bkdpendMasaPenugasan AS masa,
		a.bkdpendKinerjaBukti AS kBukti,
		a.bkdpendKinerjaSks AS bksks,
		a.bkdpendRekomendasi AS rekomen,
		a.bkdpendFile AS FILE,
		b.bkdjnsrekomenNama AS nmrekomen
	FROM 
		sdm_bkd_pendidikan a,sdm_bkd_ref_rekomendasi b
	WHERE
		a.bkdpendRekomendasi = b.bkdjnsrekomenId
		%idbkd%
	ORDER BY
		a.bkdpendId
		%limit%
";
// GET LIST PENDIDIKAN END -------------------------------------------------------------------------------------------------------------------------

// GET LIST PENELITIAN START -----------------------------------------------------------------------------------------------------------------------
$sql['get_data_bkd_penelitian'] = "
	SELECT 
		a.bkdpenId as idFix,
		a.bkdpenBkdId as bkdId,
		a.bkdpenJenisKegiatan as nmKeg,
		a.bkdpenBebanKerjaBukti as bkBukti,
		a.bkdpenBebanKerjaSks as bkSks,
		a.bkdpenMasaPenugasan as masa,
		a.bkdpenKinerjaBukti as kBukti,
		a.bkdpenKinerjaSks as bksks,
		a.bkdpenRekomendasi as rekomen,
		a.bkdpenltFile as file,
		b.bkdjnsrekomenNama AS nmrekomen
	FROM 
		sdm_bkd_penelitian a,sdm_bkd_ref_rekomendasi b
	WHERE
		a.bkdpenRekomendasi = b.bkdjnsrekomenId
		%idbkd%
	ORDER BY
		a.bkdpenId
";
// GET LIST PENELITIAN BEGIN ----------------------------------------------------------------------------------------------------------------------

// GET LIST PENGABDIAN START ----------------------------------------------------------------------------------------------------------------------
$sql['get_data_bkd_pengabdian'] = "
	SELECT 
		a.bkdpengId as idFix,
		a.bkdpengBkdId as bkdId,
		a.bkdpengJenisKegiatan as nmKeg,
		a.bkdpengBebanKerjaBukti as bkBukti,
		a.bkdpengBebanKerjaSks as bkSks,
		a.bkdpengMasaPenugasan as masa,
		a.bkdpengKinerjaBukti as kBukti,
		a.bkdpengKinerjaSks as bksks,
		a.bkdpengRekomendasi as rekomen,
		a.bkdpengbFile as file,
		b.bkdjnsrekomenNama AS nmrekomen
	FROM 
		sdm_bkd_pengabdian a,sdm_bkd_ref_rekomendasi b
	WHERE
		a.bkdpengRekomendasi = b.bkdjnsrekomenId
		%idbkd%
	ORDER BY
		a.bkdpengId
";
// GET LIST PENGABDIAN BEGIN ----------------------------------------------------------------------------------------------------------------------

// GET LIST PENUNJANG START ----------------------------------------------------------------------------------------------------------------------
$sql['get_data_bkd_penunjang'] = "
	SELECT 
		a.bkdpenuId as idFix,
		a.bkdpenuBkdId as bkdId,
		a.bkdpenuJenisKegiatan as nmKeg,
		a.bkdpenuBebanKerjaBukti as bkBukti,
		a.bkdpenuBebanKerjaSks as bkSks,
		a.bkdpenuMasaPenugasan as masa,
		a.bkdpenuKinerjaBukti as kBukti,
		a.bkdpenuKinerjaSks as bksks,
		a.bkdpenuRekomendasi as rekomen,
		a.bkdpenunjgFile as file,
		b.bkdjnsrekomenNama AS nmrekomen
	FROM 
		sdm_bkd_penunjang a,sdm_bkd_ref_rekomendasi b
	WHERE
		a.bkdpenuRekomendasi = b.bkdjnsrekomenId
		%idbkd%
	ORDER BY
		a.bkdpenuId
";
// GET LIST PENUNJANG BEGIN ----------------------------------------------------------------------------------------------------------------------

// GET LIST PROFESOR START ----------------------------------------------------------------------------------------------------------------------
$sql['get_data_bkd_profesor'] = "
SELECT 
	a.bkdprofId as idFix,
	a.bkdprofBkdId as bkdId,
	a.bkdprofJenisKegiatan as nmKeg,
	a.bkdprofBebanKerjaBukti as bkBukti,
	a.bkdprofBebanKerjaSks as bkSks,
	a.bkdprofMasaPenugasan as masa,
	a.bkdprofKinerjaBukti as kBukti,
	a.bkdprofKinerjaSks as bksks,
	a.bkdprofRekomendasi as rekomen,
	a.bkdprofFile as file,
	b.bkdjnsrekomenNama AS nmrekomen
FROM 
	sdm_bkd_profesor a,sdm_bkd_ref_rekomendasi b
WHERE
	a.bkdprofRekomendasi = b.bkdjnsrekomenId
	%idbkd%
ORDER BY
	a.bkdprofId
";
// GET LIST PROFESOR BEGIN ----------------------------------------------------------------------------------------------------------------------











// BACK UP ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$sql['get_data_bkd_detail_individu_bu']="
SELECT
    pub_pegawai.pegId AS id
    , sdm_bkd.bkdId AS bkd_id
    , pub_pegawai.pegKodeResmi AS nip
    , CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,'')) AS nama
    , CONCAT(sdm_bkd.bkdTahunAkademik,'/',(sdm_bkd.bkdTahunAkademik + 1)) AS tahun_akademik
    , sdm_bkd.bkdTahunAkademik AS thnAkd
	, (sdm_bkd.bkdTahunAkademik + 1) as thnakd_
    , sdm_bkd.bkdSemester AS semester
    ,
		(SELECT 
			CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,''))
			FROM pub_pegawai WHERE pegId = bkdPegIdAsesor1
		) AS asesor_1
    ,
    	(SELECT 
			CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,''))
			FROM pub_pegawai WHERE pegId = bkdPegIdAsesor2
		) AS asesor_2
    
    , DATE_FORMAT(sdm_bkd.bkdTglPengajuan, '%d/%m/%Y') AS tgl_pengajuan
    , DATE_FORMAT(sdm_bkd.bkdTglPenilaian, '%d/%m/%Y') AS tgl_penilaian
    , sdm_bkd.bkdTglPengajuan AS tanggal_pengajuan
    , sdm_bkd.bkdTglPenilaian AS tanggal_penilaian
    ,
		CASE
			WHEN 
				bkdJenis = 'DS' THEN 'Dosen Biasa'
			WHEN 
				bkdJenis = 'PR' THEN 'Profesor'
			WHEN 
				bkdJenis = 'DT' THEN 'Dosen Dengan Tugas Tambahan'
			WHEN 
				bkdJenis = 'PT' THEN 'Profesor Dengan Tugas  Tambahan'
		END AS bkdJenis

    , pub_pegawai.pegKelamin AS jenis_kelamin
    , sdm_ref_jenis_pegawai.jnspegrNama AS jenis_pegawai
    , sdm_pangkat_golongan.pktgolPktgolrId AS golongan
    , sdm_pangkat_golongan.pktgolTmt AS golongan_tmt
	, DATE_FORMAT(sdm_pangkat_golongan.pktgolTmt, '%d/%m/%Y') AS gol_tmt
    , pub_ref_jabatan_fungsional.jabfungrNama AS jabfung
    , sdm_jabatan_fungsional.jbtnTglMulai AS jabatan_tmt
	, DATE_FORMAT(sdm_jabatan_fungsional.jbtnTglMulai, '%d/%m/%Y') AS jab_tmt
    , pub_pegawai.pegTglLahir AS tanggal_lahir
    , pub_satuan_kerja.satkerNama AS unit_kerja
	, pddkInstitusi AS pendidikan_nama
	, pddkJurusan AS pendidikan_jurusan
	, pddkThnLulus AS pendidikan_lulus
	, pendNama AS pendidikan_tingkat    
FROM
    unijoyo_gtsdm_devel.pub_pegawai
    INNER JOIN unijoyo_gtsdm_devel.sdm_bkd 
        ON (pub_pegawai.pegId = sdm_bkd.bkdPegId)
    INNER JOIN unijoyo_gtsdm_devel.sdm_ref_jenis_pegawai 
        ON (pub_pegawai.pegJnspegrId = sdm_ref_jenis_pegawai.jnspegrId)
    INNER JOIN unijoyo_gtsdm_devel.pub_ref_jabatan_fungsional 
        ON (sdm_bkd.bkdJabfungrId = pub_ref_jabatan_fungsional.jabfungrId)
	INNER JOIN unijoyo_gtsdm_devel.sdm_jabatan_fungsional 
        ON (pub_ref_jabatan_fungsional.jabfungrId = sdm_jabatan_fungsional.jbtnJabfungrId)
	INNER JOIN unijoyo_gtsdm_devel.sdm_pangkat_golongan 
        ON (sdm_pangkat_golongan.pktgolPegKode = pub_pegawai.pegId)
    INNER JOIN unijoyo_gtsdm_devel.sdm_satuan_kerja_pegawai 
        ON (sdm_satuan_kerja_pegawai.satkerpegPegId = pub_pegawai.pegId)
    INNER JOIN unijoyo_gtsdm_devel.pub_satuan_kerja 
        ON (sdm_satuan_kerja_pegawai.satkerpegSatkerId = pub_satuan_kerja.satkerId)
	LEFT JOIN 
		(SELECT * FROM (
				SELECT * FROM 
					sdm_pendidikan 
					INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
				WHERE pddkStatusTamat='Selesai'
				ORDER BY pendPendkelId DESC
		) AS b GROUP BY pddkPegKode ) AS b
		ON pddkPegKode=pegId
		
	WHERE
		pegId<>-1
	AND
	   sdm_bkd.bkdId='%s'
	ORDER BY
		sdm_bkd.bkdId DESC
";

?>
