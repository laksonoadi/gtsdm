<?php
$sql['get_combo_unit_kerja'] = "
SELECT
  satkerId as id,
  satkerNama as name
FROM 
  pub_satuan_kerja
";

$sql["count_data_nominatif"] = "
SELECT FOUND_ROWS() AS total
";

$sql['get_data_nominatif']="
SELECT SQL_CALC_FOUND_ROWS 
	nama,nip,jenis_kelamin,jenis_pegawai,golongan,golongan_tmt,jabatan,
	date_format(jabatan_tmt, '%d/%m/%Y') as jabatan_tmt,
	
	pegTmpLahir,
	date_format(pegTglLahir, '%d/%m/%Y') as tanggal_lahir,
	date_format(pegPnsTmt, '%d/%m/%Y') as pegPnsTmt,
	date_format(pegCpnsTmt, '%d/%m/%Y') as pegCpnsTmt,
	statnkhNama,
	agmNama,
	nameEselon,
	statrPegawai,
	
	IF(MIN(pktgolrTingkat)=1 AND MAX(pktgolrTingkat)>=2,
		CAST(IF(masa_kerja_bulan>=12,masa_kerja_tahun+1,masa_kerja_tahun) AS SIGNED)-6,
		IF(MIN(pktgolrTingkat)<=2 AND MAX(pktgolrTingkat)>=3,
			CAST(IF(masa_kerja_bulan>=12,masa_kerja_tahun+1,masa_kerja_tahun) AS SIGNED)-5,
			IF(masa_kerja_bulan>=12,masa_kerja_tahun+1,masa_kerja_tahun)
		)
	) AS masa_kerja_tahun,
	IF(masa_kerja_bulan>=12,masa_kerja_bulan MOD 12,masa_kerja_bulan) AS masa_kerja_bulan,
		GROUP_CONCAT(DISTINCT pelThnIjazah) AS latihan_tahun,
		GROUP_CONCAT(DISTINCT pelNama) AS latihan_nama,
		GROUP_CONCAT(DISTINCT pelJmlJam) AS latihan_jam,
	
	pendidikan_nama,pendidikan_jurusan,pendidikan_lulus,pendidikan_tingkat,
	unit_kerja,sub_unit_kerja,
	
	(
		select kgbBerlakuTanggal
		from sdm_kenaikan_gaji_berkala
		where kgbPegKode = id and kgbAktif = 'Aktif'
		order by kgbBerlakuTanggal desc
		limit 0,1
	) as berkala,
	
	(
		select 
		ysubunit.satkerNama
		from sdm_satuan_kerja_pegawai y
		LEFT JOIN pub_satuan_kerja ysubunit ON ysubunit.satkerId=y.satkerpegSatkerId
		/* LEFT JOIN pub_satuan_kerja yunit ON yunit.satkerId=subunit.satkerParentId */
		where y.satkerpegPegId=id AND y.satkerpegAktif='Tidak Aktif'
		order by satkerpegId desc
		limit 0,1
	) as instansi_lama
FROM
	(SELECT
		pegId AS id,
		CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,'')) AS nama,
		pegKodeResmi AS nip,
		pegKelamin AS jenis_kelamin,
		
		pegTmpLahir,
		pegTglLahir,
		pegPnsTmt,
		pegCpnsTmt,
		statnkhNama,
		agmNama,
		
		jnspegrNama AS jenis_pegawai,
	
		pktgolPktgolrId AS golongan,
		pktgolTmt AS golongan_tmt,
	
		jabstrukrNama AS jabatan,
		jbtnTglMulai AS jabatan_tmt,
		nameEselon,
	
		IF(jnspegrNama IN ('PNS','CPNS'),
			pktgolrMasaKerja+FLOOR(DATEDIFF(NOW(),pegCpnsTmt)/365)+IFNULL(mkPenyesuaianTahun,0),
			pktgolrMasaKerja+FLOOR(DATEDIFF(NOW(),pegTglMasukInstitusi)/365)+IFNULL(mkPenyesuaianTahun,0)
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
	
		pktgolrTingkat,
		statrPegawai
		
	FROM
		pub_pegawai
		LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
	
		LEFT JOIN sdm_pangkat_golongan pkt ON pkt.pktgolPegKode=pegId AND pkt.pktgolStatus='Aktif'
		/*
		LEFT JOIN 
			(SELECT * FROM sdm_pangkat_golongan ORDER BY pktgolStatus ASC, pktgolId DESC) AS why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
		*/
		LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId 
	
		LEFT JOIN sdm_jabatan_struktural ON  jbtnPegKode=pegId AND jbtnStatus='Aktif'
		LEFT JOIN sdm_ref_jabatan_struktural ON jbtnJabstrukrId=jabstrukrId
		LEFT JOIN sdm_ref_eselon es ON sdm_jabatan_struktural.jbtnEselon=es.idEselon
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
	
		LEFT JOIN sdm_satuan_kerja_pegawai x ON x.satkerpegPegId=pegId AND x.satkerpegAktif='Aktif'
		LEFT JOIN pub_satuan_kerja subunit ON subunit.satkerId=x.satkerpegSatkerId
		LEFT JOIN pub_satuan_kerja unit ON unit.satkerId=subunit.satkerParentId
		LEFT JOIN sdm_masa_kerja_penyesuaian ON mkPegKode=pegId
		
		LEFT JOIN sdm_ref_status_pegawai ON statrId=pegStatrId
		LEFT JOIN pub_ref_status_nikah ON statnkhId=pegStatnikahId
		LEFT JOIN pub_ref_agama ON agmId=pegAgamaId
	WHERE
		1=1 %user_unit_kerja%
  	%jenis_pegawai%
  	%unit_kerja%
	ORDER BY pegTglMasukInstitusi ASC, pktgolrTingkat DESC,pktgolrUrut DESC, pelThnIjazah DESC, pddkThnLulus DESC ) as temp
GROUP BY id
%limit%
"; 

$sql['get_combo_jenis'] = "
SELECT
  jnspegrId as ids,
  jnspegrNama as `id`,
  jnspegrNama as `name`
FROM 
  sdm_ref_jenis_pegawai
WHERE 1=1
";


?>
