<?php

//===GET===
$sql['get_data'] = "
   SELECT 
      kompgajidtId as id,
      kompgajidtKode as kode,
      kompgajidtNama as nama,
      kompgajidtStatusSeting as setting,
      kompgajidtNominal as nominal,
      kompgajidtPersen as persen,
      kompgajidtTanggalBerlaku as tanggal_berlaku
   FROM 
      sdm_ref_komponen_gaji_detail
	WHERE 
		kompgajidtKompgajiId=%s
	AND 
		kompgajidtKode like '%s'
	AND
		kompgajidtNama like '%s'
   ORDER BY 
	  kompgajidtId, kompgajidtNama ASC
   LIMIT %s, %s";

$sql['get_count_data'] = "
SELECT 
      count(*) AS total
   FROM 
      sdm_ref_komponen_gaji_detail
	WHERE 
		kompgajidtKompgajiId=%s
	AND 
		kompgajidtKode like '%s'
	AND
		kompgajidtNama like '%s'
";

$sql['get_data_by_id'] ="
   SELECT 
      a.kompgajidtId as id_detil,
      a.kompgajidtKode as kode_detil,
      a.kompgajidtNama as nama_detil,
      a.kompgajidtStatusSeting as setting_detil,
      a.kompgajidtNominal as nominal_detil,
      a.kompgajidtPersen as persen_detil,
      a.kompgajidtTanggalBerlaku as tanggal_berlaku,
      b.kompgajiNama
   FROM 
      sdm_ref_komponen_gaji_detail a
      JOIN sdm_ref_komponen_gaji b ON b.kompgajiId = a.kompgajidtKompgajiId
   WHERE
      a.kompgajidtId='%s'";

$sql['get_info'] ="
   SELECT 
      kompgajiId as id,
      kompgajiKode as kode,
      kompgajiNama as nama,
      kompgajiKeterangan as keterangan,
      kompgajiJenis as jenis,
	  kompgajiIsAuto as otomatis,
	  kompgajiTabelReferensi as arr_table
   FROM 
      sdm_ref_komponen_gaji
   WHERE
      kompgajiId='%s'";
      
$sql['get_kode_by_id'] = "
SELECT
   kompgajidtKode
FROM
   sdm_ref_komponen_gaji_detail
WHERE
   kompgajidtId IN (%s)
";


//===DO===
$sql['do_add_data'] = 
   "INSERT INTO sdm_ref_komponen_gaji_detail
      (kompgajidtKompgajiId, kompgajidtKode, kompgajidtNama, kompgajidtStatusSeting, kompgajidtNominal, kompgajidtPersen, kompgajidtTanggalBerlaku)
   VALUES 
      ('%s', '%s', '%s', '%s', '%s', '%s', '%s')";

$sql['do_update_data'] = "
   UPDATE 
      sdm_ref_komponen_gaji_detail
   SET
      kompgajidtKode = '%s',
      kompgajidtNama = '%s',
      kompgajidtStatusSeting = '%s',
      kompgajidtNominal = '%s',
      kompgajidtPersen = '%s',
      kompgajidtTanggalBerlaku = '%s'
   WHERE 
      kompgajidtId = '%s'";

$sql['do_delete_data'] = 
   "DELETE from 
   sdm_ref_komponen_gaji_detail
   WHERE 
      kompgajidtId='%s'";

$sql['do_delete_data_by_array_id'] = 
   "DELETE from sdm_ref_komponen_gaji_detail
   WHERE 
      kompgajidtId IN ('%s')";
      /**/
/*
@Untuk yang Otomatis Mengisi
@Author : Wahyono
@Version: 1.0
*/

$sql['query_komp']="
SELECT
	kompgajidtId AS id_komponen
FROM 
	sdm_ref_komponen_gaji_detail 
WHERE
	kompgajidtKode='%s'
";

$sql['query_lama']="
SELECT
	kompgajipegdtId AS id,
	kompgajiKode AS kode,
	kompgajipegdtKompgajidtrId AS id_komponen
FROM 
	sdm_komponen_gaji_pegawai_detail 
	LEFT JOIN sdm_ref_komponen_gaji_detail ON kompgajipegdtKompgajidtrId=kompgajidtId
	LEFT JOIN sdm_ref_komponen_gaji ON kompgajidtKompgajiId=kompgajiId
WHERE
	kompgajiKode='%s' AND kompgajipegdtPegId='%s'
";

$sql['query_delete']="
DELETE FROM 
	sdm_komponen_gaji_pegawai_detail 
WHERE 
	kompgajipegdtId='%s'
";

$sql['query_insert']="
INSERT INTO 
	sdm_komponen_gaji_pegawai_detail 
SET
	kompgajipegdtPegId='%s',
	kompgajipegdtKompgajidtrId='%s',
	kompgajipegdtTanggal=now()
";

$sql['get_query_komponen_otomatis']="
SELECT
	formatNumberFormula as query
FROM
	sdm_ref_formula_number
WHERE
	formatNumberCode='%s' AND formatNumberIsAktif='Y'
";

$sql['get_tunjangan_studi'] = "
SELECT
	CONCAT(jenjang,studi,semester) AS bea
FROM
(SELECT
	jenjang,
	pddkAsldnrId AS studi,
	CASE
	 WHEN semester_mulai='GENAP' AND semester_sekarang='GENAP' THEN 1+(tahun_sekarang-tahun_mulai)*2
	 WHEN semester_mulai='GENAP' AND semester_sekarang='GANJIL' THEN (tahun_sekarang-tahun_mulai)*2
	 WHEN semester_mulai='GANJIL' AND semester_sekarang='GANJIL' THEN 1+(tahun_sekarang-tahun_mulai)*2
	 WHEN semester_mulai='GANJIL' AND semester_sekarang='GENAP' THEN (tahun_sekarang-tahun_mulai)*2
	 ELSE 0
	END AS semester
FROM	
(	SELECT 
		UPPER(JnspegrNama) AS jenis,
		IFNULL(js.jbtnJabstrukrId,UPPER(jfrs.jabfungJenis)) AS jabatan,
		pendNama AS jenjang,
		pddkAsldnrId,
		IF(pddkTglMulaiDinas IS NULL,0,IF(MONTH(pddkTglMulaiDinas)<=7,'GENAP','GANJIL')) AS semester_mulai,
		YEAR(IFNULL(pddkTglMulaiDinas,NOW())) AS tahun_mulai,
		IF(MONTH(NOW())<=7,'GENAP','GANJIL') AS semester_sekarang,
		YEAR(NOW()) AS tahun_sekarang
	FROM 
		pub_pegawai
		LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
		LEFT JOIN sdm_jabatan_struktural js ON js.jbtnPegKode=pegId AND js.jbtnStatus='Aktif'
		LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=pegId AND jf.jbtnStatus='Aktif'
		LEFT JOIN pub_ref_jabatan_fungsional jfr ON jfr.jabfungrId=jf.jbtnJabfungrId
		LEFT JOIN pub_ref_jabatan_fungsional_jenis jfrs ON jfr.jabfungrJenisrId=jfrs.jabfungjenisrId
		LEFT JOIN 
		(SELECT * FROM (
  			SELECT * FROM 
  				sdm_pendidikan 
  				INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
  			WHERE pddkStatusTamat='Masa Pendidikan'
  				ORDER BY pendPendkelId DESC
  		) AS b GROUP BY pddkPegKode ) AS b
  		ON pddkPegKode=pegId
  	WHERE
		pegId='%s'
) AS WHY
) AS WHY2   
";

$sql['get_komponen_pegawai_detail'] = "
SELECT
	pegId,
        0 AS idg,
        0 AS tbk,
	jnspegrId AS jenis,
	pktgolrId AS pktgol,
	jabfungrId AS jabfung,
	jsr.jabstrukrId AS jabstruk,
    js2r.jabstrukrId AS jabstruk2,
	'UTAMA' AS jenisjbtn,
	pendId AS pend,
	IFNULL(pendNama,0) AS pendNama,
	IFNULL(IF((COUNT(DISTINCT kgbId)-1)<0,0,(COUNT(DISTINCT kgbId)-1))+thpNilai,0) AS thp
		
FROM
	pub_pegawai
	LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
	
	LEFT JOIN 
		( SELECT *
		  FROM
		    (SELECT * FROM sdm_pangkat_golongan WHERE pktgolTmt<=DATE(CONCAT('%s','-15')) ORDER BY pktgolTmt DESC) AS tpkt 
		  GROUP BY pktgolPegKode
		) AS pkt ON pkt.pktgolPegKode=pegId
	LEFT JOIN sdm_ref_pangkat_golongan pktr ON pktr.pktgolrId=pkt.pktgolPktgolrId 
	
	LEFT JOIN 
	        ( SELECT *
		  FROM
		    (SELECT * FROM sdm_jabatan_struktural WHERE jbtnTglMulai<=DATE(CONCAT('%s','-15')) ORDER BY jbtnTglMulai DESC) AS tjs 
		  WHERE 'UTAMA'<>'RANGKAP'
		  GROUP BY jbtnPegKode
		) AS js ON  js.jbtnPegKode=pegId
	LEFT JOIN sdm_ref_jabatan_struktural jsr ON js.jbtnJabstrukrId=jsr.jabstrukrId
	
	LEFT JOIN 
		( SELECT *
		  FROM
		    (SELECT * FROM sdm_jabatan_struktural WHERE jbtnTglMulai<=DATE(CONCAT('%s','-15')) ORDER BY jbtnTglMulai DESC) AS tjs 
		  WHERE 'UTAMA'='RANGKAP'
		  GROUP BY jbtnPegKode
		) AS js2 ON  js2.jbtnPegKode=pegId
	LEFT JOIN sdm_ref_jabatan_struktural js2r ON js2.jbtnJabstrukrId=js2r.jabstrukrId
		
	LEFT JOIN 
		( SELECT *
		  FROM
		    (SELECT * FROM sdm_jabatan_fungsional WHERE jbtnTglMulai<=DATE(CONCAT('%s','-15')) ORDER BY jbtnTglMulai DESC) AS tjf
		  GROUP BY jbtnPegKode
		) AS jf ON  jf.jbtnPegKode=pegId
	LEFT JOIN pub_ref_jabatan_fungsional jfr ON jf.jbtnJabfungrId=jfr.jabfungrId
		
	LEFT JOIN 
		(SELECT * FROM (SELECT * FROM sdm_pelatihan ORDER BY pelId DESC) AS a GROUP BY pelPegKode ) AS a
		ON pelPegKode=pegId
	LEFT JOIN sdm_ref_jenis_pelatihan ON pelJnspelrId=jnspelrId
	
	LEFT JOIN 
		(SELECT * FROM (
				SELECT * FROM 
					sdm_pendidikan 
					INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
				WHERE pddkStatusTamat='Masa Pendidikan'
				ORDER BY pendPendkelId DESC
		) AS b GROUP BY pddkPegKode ) AS b
		ON pddkPegKode=pegId
	
	LEFT JOIN sdm_satuan_kerja_pegawai sk ON sk.satkerpegPegId=pegId AND sk.satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja skr ON skr.satkerId=sk.satkerpegSatkerId
		
	LEFT JOIN sdm_kenaikan_gaji_berkala kgb ON kgb.kgbPegKode=pegId AND kgbBerlakuTanggal<=DATE(CONCAT('%s','-15'))
	LEFT JOIN sdm_thp thp ON thpPegKode=pegId
		
	LEFT JOIN pub_ref_agama ON agmId=pegAgamaId
WHERE
	pegId='%s'
GROUP BY pegId
";

?>
