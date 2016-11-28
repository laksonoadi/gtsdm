<?php
$sql['get_data_detail']="
SELECT 
	pegId,
	pegNama AS nama_pegawai,
	pegKodeResmi AS nip_pegawai,
	pegKodeGateAccess AS karpeg_pegawai,
	pegTmpLahir AS tempat_lahir,
	pegTglLahir AS tanggal_lahir,
			
	CONCAT(pktgolrNama,'/',REPLACE(pktgolPktgolrId,'/','')) AS pangkat_golongan,
	pktgolId AS id_mutasi_pangkat,
	pktgolrNama AS pangkat,
	pktgolPktgolrId AS golongan,
	pktgolTmt AS tmt_pangkat_golongan,
	pktgolrUrut AS urutan_pangkat,
	
	CASE
	    WHEN pktgolPktgolrId LIKE 'IV%%' THEN 'IV'
		WHEN pktgolPktgolrId LIKE 'III%%' THEN 'III'
		WHEN pktgolPktgolrId LIKE 'II%%' THEN 'II'
	    WHEN pktgolPktgolrId LIKE 'I%%' THEN 'I'
	    ELSE '0'
    END as gol,
	
	UPPER(RIGHT(pktgolPktgolrId,1)) as ruang,
		
	satkerNama AS unit_kerja,
		
	jabfungrNama AS jabatan_fungsional,
	jf.jbtnTglMulai AS tmt_fungsional,
	
	jabstrukrNama AS jabatan_struktural,
	js.jbtnTglMulai AS tmt_struktural,
	
	kgbGajiPokokBaru AS gaji_pokok,
	kgbBerlakuTanggal AS tmt_gaji_berkala,
	kgbId AS id_mutasi_gaji_berkala,
		
	CONCAT(pendNama,' Tahun ',pddkThnLulus) AS pendidikan
FROM
	pub_pegawai
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId = satkerId
	
	LEFT JOIN sdm_jabatan_struktural js ON js.jbtnPegKode=pegId AND js.jbtnStatus='Aktif'
	LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=js.jbtnjabstrukrId
	
	LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=pegId
	LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=jf.jbtnJabfungrId
	
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId
	LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
	
	LEFT JOIN sdm_kenaikan_gaji_berkala ON kgbPegKode=pegId
	
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
	pegId='%s'
"; 

$sql['get_data_pangkat_sebelumnya']="
SELECT
	pegNama,
	IFNULL(CONCAT(pktgolrNama,'/',REPLACE(pktgolPktgolrId,'/','')),'Tidak Ada Data') AS pangkat_golongan_l,
	IF(CONCAT(pktgolrNama,'/',REPLACE(pktgolPktgolrId,'/','')) IS NULL,pegTglMasukInstitusi,pktgolTmt) AS tmt_pangkat_golongan_l
FROM
	pub_pegawai	
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId 
	LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId AND pktgolrUrut<'%s'
WHERE 
	pegId='%s'
ORDER BY pktgolrUrut DESC LIMIT 1
";

$sql['get_data_kgb_sebelumnya']="
SELECT
	pegNama,
	IFNULL(kgbGajiPokokBaru,'Tidak Ada Data') AS gaji_pokok_l,
	kgbBerlakuTanggal AS tmt_gaji_berkala_l
FROM
	pub_pegawai	
	LEFT JOIN sdm_kenaikan_gaji_berkala ON kgbPegKode=pegId 
WHERE 
	pegId='%s'
	AND kgbId<>'%s'
ORDER BY kgbId DESC
";

$sql['get_tunjangan_dosen']="
SELECT
	ROUND(kompgajidtNominal) AS tunjangan_dosen
FROM
	sdm_ref_komponen_gaji_detail	
WHERE 
	kompgajidtKode LIKE 'JFU-%%'
	AND kompgajidtNama=UPPER('%s')
";

$sql['get_angka_kredit']="
SELECT
	SUM(pakkumdetAngkaKredit) AS angka_kredit
FROM
	sdm_pak_kumulatif
	INNER JOIN sdm_pak_kumulatif_detail ON pakkumdetPakkumId=pakkumId
WHERE 
	pakkumIsApproved=1
	AND pakkumPegId='%s'
";

$sql['get_masa_kerja']="
SELECT
	FLOOR(MKS_HARI/360) AS MKS_TAHUN,
	ROUND((MKS_HARI-FLOOR(MKS_HARI/360)*360)/30) AS MKS_BULAN,
	CASE
	    WHEN pangkat_pertama='I' AND pangkat_sekarang='II' THEN IF(FLOOR(MKS_HARI/360)<6,0,FLOOR(MKS_HARI/360)-6)
	    WHEN pangkat_pertama='I' AND pangkat_sekarang='III' THEN IF(FLOOR(MKS_HARI/360)<11,0,FLOOR(MKS_HARI/360)-11)
	    WHEN pangkat_pertama='I' AND pangkat_sekarang='IV' THEN IF(FLOOR(MKS_HARI/360)<11,0,FLOOR(MKS_HARI/360)-11)
	    WHEN pangkat_pertama='II' AND pangkat_sekarang='III' THEN IF(FLOOR(MKS_HARI/360)<5,0,FLOOR(MKS_HARI/360)-5)
	    WHEN pangkat_pertama='II' AND pangkat_sekarang='IV' THEN IF(FLOOR(MKS_HARI/360)<5,0,FLOOR(MKS_HARI/360)-5)
	    ELSE FLOOR(MKS_HARI/360)
	END AS MKG_TAHUN,
	
	CASE
	    WHEN pangkat_pertama='I' AND pangkat_sekarang='II' THEN IF(FLOOR(MKS_HARI/360)<6,0,ROUND((MKS_HARI-FLOOR(MKS_HARI/360)*360)/30))
	    WHEN pangkat_pertama='I' AND pangkat_sekarang='III' THEN IF(FLOOR(MKS_HARI/360)<11,0,ROUND((MKS_HARI-FLOOR(MKS_HARI/360)*360)/30))
	    WHEN pangkat_pertama='I' AND pangkat_sekarang='IV' THEN IF(FLOOR(MKS_HARI/360)<11,0,ROUND((MKS_HARI-FLOOR(MKS_HARI/360)*360)/30))
	    WHEN pangkat_pertama='II' AND pangkat_sekarang='III' THEN IF(FLOOR(MKS_HARI/360)<5,0,ROUND((MKS_HARI-FLOOR(MKS_HARI/360)*360)/30))
	    WHEN pangkat_pertama='II' AND pangkat_sekarang='IV' THEN IF(FLOOR(MKS_HARI/360)<5,0,ROUND((MKS_HARI-FLOOR(MKS_HARI/360)*360)/30))
	    ELSE ROUND((MKS_HARI-FLOOR(MKS_HARI/360)*360)/30)
	END AS MKG_BULAN,
	
	pangkat_pertama,
	pangkat_sekarang
FROM
	(SELECT
		MKS_HARI,
		CASE
		    WHEN pangkat_pertama LIKE 'IV%%' THEN 'IV'
		    WHEN pangkat_pertama LIKE 'III%%' THEN 'III'
		    WHEN pangkat_pertama LIKE 'II%%' THEN 'II'
		    WHEN pangkat_pertama LIKE 'I%%' THEN 'I'
		    ELSE '0'
		END AS pangkat_pertama,
		
		CASE
		    WHEN pangkat_sekarang LIKE 'IV%%' THEN 'IV'
		    WHEN pangkat_sekarang LIKE 'III%%' THEN 'III'
		    WHEN pangkat_sekarang LIKE 'II%%' THEN 'II'
		    WHEN pangkat_sekarang LIKE 'I%%' THEN 'I'
		    ELSE '0'
		END AS pangkat_sekarang
		
	FROM
		(SELECT
			IF(YEAR(pegCpnsTmt)='0000' OR MONTH(pegCpnsTmt)='00' OR DATE(pegCpnsTmt)='00',0,DATEDIFF(NOW(),pegCpnsTmt)) AS MKS_HARI,
			PKTGOL1.pktgolPktgolrId AS pangkat_sekarang,
			IFNULL(PKTGOL2.pktgolPktgolrId,PKTGOL1.pktgolPktgolrId) AS pangkat_pertama
		FROM
			pub_pegawai
			LEFT JOIN (
				SELECT
					*
				FROM
					sdm_pangkat_golongan
				WHERE pktgolStatus='Aktif'
				) AS PKTGOL1 ON PKTGOL1.pktgolPegKode=pegId
				
			LEFT JOIN (
				SELECT 
					* 
				FROM 
					(SELECT
						*
					FROM
						sdm_pangkat_golongan
						INNER JOIN sdm_ref_pangkat_golongan ON pktgolPktgolrId=pktgolrId
					WHERE pktgolStatus<>'Aktif' ORDER BY pktgolrUrut) AS WHY 
				GROUP BY pktgolPegKode) AS PKTGOL2 ON PKTGOL2.pktgolPegKode=pegId
				
		WHERE
			pegId='%s') AS MASA_KERJA) AS HASIL
";

?>
