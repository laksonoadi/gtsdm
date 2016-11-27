<?php
$sql['get_biodata_pegawai_by_id'] = "
   SELECT 
			nip,
			nama,
			alamat,
			no_rekening,
			nama_bank,
			unit,
			kompformId AS id_formula,
			kompformNama AS nama_formula,
			kompformFormula,
			IFNULL(gajipegdtNominalKomponen,0) AS nominal,
			isManual
		FROM 
			sdm_komponen_formula
  		LEFT JOIN (
  		SELECT 	
  			gajipegdtIsManual AS isManual,
  			gajipegdtKompformId,
  			gajipegdtNominalKomponen
  		FROM
  		  sdm_gaji_pegawai_detail 
        LEFT JOIN(
      		SELECT 
            gajipegId,
      			gajipegPegId,
      			gajipegTotalGaji,
      			gajipegPeriode
      		FROM(
      		SELECT
      			gajipegId,
      			gajipegPegId,
      			gajipegTotalGaji,
      			gajipegPeriode
      		FROM 
      		  sdm_gaji_pegawai 
      		ORDER BY 
            gajipegId DESC
    		) a 
        GROUP BY 
          gajipegPegId
  		)a ON gajipegId = gajipegdtGajipegId
  		
  		WHERE gajipegPegId = '%s'
  		)a ON gajipegdtKompformId = kompformId,
  		
      (SELECT 
  			pegKodeResmi AS nip,
  			pegNama AS nama,
  			pegAlamat AS alamat, 
        pegrekRekening AS no_rekening,
  			bankNama AS nama_bank,
        satkerNama AS unit 			
  		FROM 
  			pub_pegawai
      LEFT JOIN 
  			pub_pegawai_rekening ON pegrekPegId = pegId   
  		LEFT JOIN 
  			pub_ref_bank ON bankId = pegrekBankId 
  		LEFT JOIN 
  			sdm_satuan_kerja_pegawai ON satkerpegPegId = pegId
  		LEFT JOIN
  			pub_satuan_kerja ON satkerId = satkerpegSatkerId
  		WHERE 
        pegId = '%s'
      GROUP BY 
        pegId) b
";

$sql['get_id_gaji_pegawai_by_periode'] = "
   SELECT 
      gajipegId as id,
      gajipegPeriode as periode
   FROM 
      sdm_gaji_pegawai 
   WHERE 
      gajipegPegId='%s'
";

$sql['get_data_pendapatan'] = "
SELECT 
   b.jnspndptnlainrNama AS 'jenis',
   SUM(a.pndptnlainNominal) AS 'nominal'
FROM 
   sdm_pendapatan_lain a
   LEFT JOIN sdm_ref_jenis_pendapatan_lain b ON b.jnspndptnlainrId=a.pndptnlainJnsId  
   LEFT JOIN pub_pegawai c ON a.pndptnlainPegId = c.pegId 
WHERE c.pegId = '%s' and a.pndptnlainGajiPegId is NULL and MONTH(a.pndptnlainTanggal) = '%s' and YEAR(a.pndptnlainTanggal) = '%s'
GROUP BY a.pndptnlainJnsId
";

$sql['get_data_pendapatan_total'] = "
SELECT 
   SUM(a.pndptnlainNominal) AS 'nominal'
FROM 
   sdm_pendapatan_lain a  
   LEFT JOIN pub_pegawai c ON a.pndptnlainPegId = c.pegId 
WHERE c.pegId = '%s' and a.pndptnlainGajiPegId is NULL and MONTH(a.pndptnlainTanggal) = '%s' and YEAR(a.pndptnlainTanggal) = '%s'
";

$sql['get_data_tunjangan_thr'] = "
SELECT 
   SUM(a.tunjthrNominal) AS 'nominal'
FROM 
   sdm_tunjangan_thr a  
   LEFT JOIN pub_pegawai c ON a.tunjthrPegId = c.pegId 
WHERE c.pegId = '%s' and a.tunjthrGajiPegId is NULL and MONTH(a.tunjthrTgl) = '%s' and YEAR(a.tunjthrTgl) = '%s'
";

$sql['get_data_potongan_gaji'] = "
SELECT 
   b.jnspotgajiNama AS 'jenis',
   SUM(a.potgajiNominal) AS 'nominal'
FROM 
   sdm_potongan_gaji a
   LEFT JOIN sdm_ref_jenis_potongan_gaji b ON b.jnspotgajiId=a.potgajiJnspotgajiId  
   LEFT JOIN pub_pegawai c ON a.potgajiPegId = c.pegId 
WHERE c.pegId = '%s' and a.potgajiGajiPegId is NULL and MONTH(a.potgajiTanggal) = '%s' and YEAR(a.potgajiTanggal) = '%s'
GROUP BY a.potgajiJnspotgajiId
";

$sql['get_data_potongan_gaji_total'] = "
SELECT 
   SUM(a.potgajiNominal) AS 'nominal'
FROM 
   sdm_potongan_gaji a 
   LEFT JOIN pub_pegawai c ON a.potgajiPegId = c.pegId 
WHERE c.pegId = '%s' and a.potgajiGajiPegId is NULL and MONTH(a.potgajiTanggal) = '%s' and YEAR(a.potgajiTanggal) = '%s'
";

$sql['get_data_tunjangan_pph'] = "
SELECT 
   SUM(a.tunjpphNominal) AS 'nominal'
FROM 
   sdm_tunjangan_pph a  
   LEFT JOIN pub_pegawai c ON a.tunjpphPegId = c.pegId 
WHERE c.pegId = '%s' and a.tunjpphGajiPegId is NULL and MONTH(a.tunjpphTgl) = '%s' and YEAR(a.tunjpphTgl) = '%s'
";

$sql['get_gapok_swasta'] = "
SELECT 
   kgbGajiPokokBaru AS 'gapok'
FROM 
   sdm_kenaikan_gaji_berkala
WHERE 
   kgbPegKode = '%s' and kgbAktif = 'Aktif'
";

$sql['get_gapok_negeri_'] = "
SELECT 
   b.kompgajidtNominal AS 'gapok'
FROM 
   sdm_ref_gaji_pokok a
   LEFT JOIN sdm_ref_komponen_gaji_detail b ON b.kompgajidtId = a.gapokKompGajiDetId
   LEFT JOIN sdm_kenaikan_gaji_berkala c ON c.kgbPktgolId = a.gapokPktgolrId  AND c.kgbMasaKerja = a.gapokMasaKerja
WHERE 
   c.kgbPegKode = '%s' and c.kgbAktif = 'Aktif'
";

$sql['get_gapok_negeri'] = "
SELECT 
   kgbGajiPokokBaru AS 'gapok'
FROM 
   sdm_kenaikan_gaji_berkala
WHERE 
   kgbPegKode = '%s' and kgbAktif = 'Aktif'
";


//===============DO===============
$sql['insert_gaji_pegwai_mst'] = "
INSERT INTO sdm_gaji_pegawai
   (gajipegPegId, gajipegTotalGaji, gajipegPeriode)
VALUES
   ('%s', '%s', '%s')
";

$sql['update_gaji_pegawai_mst'] = "
UPDATE 
  sdm_gaji_pegawai 
SET 
  gajipegTotalGaji = '%s' 
WHERE 
  gajipegId = '%s'
";

$sql['get_max_id_gaji_pegwai_mst'] = "
SELECT
   MAX(gajipegId) AS id
FROM
   sdm_gaji_pegawai
";

$sql['get_id_detil_gaji_pegawai_by_periode']="
SELECT 
    gajipegdtId as id2,
    gajipegdtTanggalMulaiPeriode as periode2
FROM 
    sdm_gaji_pegawai_detail 
WHERE 
    gajipegdtGajipegId = '%s' AND gajipegdtKompformId = '%s'
";

$sql['add_detail_gaji_pegawai'] = "
   INSERT INTO sdm_gaji_pegawai_detail
   (
      gajipegdtGajipegId,
      gajipegdtKompformId,
      gajipegdtNominalKomponen,
      gajipegdtIsManual,
      gajipegdtTanggalMulaiPeriode
   )VALUES(
      '%s',
      '%s',
      '%s',
      '%s',
      '%s'
   )
";

$sql['update_gaji_pegawai_det']="
UPDATE 
   sdm_gaji_pegawai_detail 
SET 
   gajipegdtNominalKomponen = '%s', 
   gajipegdtIsManual='%s',
   gajipegdtTanggalMulaiPeriode = '%s'
WHERE 
   gajipegdtGajipegId='%s' AND gajipegdtKompformId='%s' AND gajipegdtId='%s'
";

$sql['delete_detail_gaji_pegawai'] = "
   DELETE FROM 
      sdm_gaji_pegawai_detail 
   WHERE  
      gajipegdtGajipegId='%s'
";

//FORMULA
$sql['get_komponen_gaji_peg'] = "
   SELECT 
      a.kompgajiId AS id,
      a.kompgajiKode,
      ifnull(nominal,0) as nominal
   FROM
      sdm_ref_komponen_gaji a
   LEFT JOIN(
   SELECT 
      kompgajiKode,
      CASE WHEN kompgajidtStatusSeting = 'persen' THEN kompgajidtPersen/100
         WHEN kompgajidtStatusSeting != 'persen' THEN kompgajidtNominal END AS nominal
   FROM 
      sdm_ref_komponen_gaji 
   LEFT JOIN sdm_ref_komponen_gaji_detail ON kompgajidtKompgajiId = kompgajiId
   JOIN sdm_komponen_gaji_pegawai_detail ON kompgajipegdtKompgajidtrId = kompgajidtId
   JOIN pub_pegawai ON kompgajipegdtPegId = pegId
   WHERE pegKodeResmi = '%s') b ON a.kompgajiKode = b.kompgajiKode
";

$sql['get_formula_gaji']= "
SELECT 
	CONCAT((IFNULL(kompjenisNilai,0)/100),'*(',kompformFormula,')') AS kompformFormula
FROM
	sdm_komponen_formula
	LEFT JOIN sdm_komponen_jenis_pegawai ON kompjenisKompformId=kompformId
	LEFT JOIN pub_pegawai ON pegJnspegrId=kompjenisJnspegId
WHERE
    kompformId = '%s' AND pegKodeResmi='%s'
";

$sql['cek_formula_gaji']= "
SELECT 
	gajipegdtIsManual as manual,
	gajipegdtNominalKomponen as nominal
FROM
	sdm_gaji_pegawai_detail
	LEFT JOIN sdm_gaji_pegawai ON gajipegdtGajipegId=gajipegId
	LEFT JOIN pub_pegawai ON gajipegPegId = pegId
WHERE
    gajipegdtKompformId = '%s' AND pegKodeResmi='%s' AND gajipegdtTanggalMulaiPeriode='%s'
";

$sql['cek_gaji']= "
SELECT 
	gajipegStatus as sudah
FROM
	sdm_gaji_pegawai
	LEFT JOIN pub_pegawai ON gajipegPegId = pegId
WHERE
    pegKodeResmi='%s' AND gajipegPeriode='%s'
";

$sql['get_formula_gaji_']= "
   SELECT 
      kompformFormula
   FROM
      sdm_komponen_formula
   WHERE
      kompformId = '%s'
";

$sql['get_komponen_gaji'] = "
   SELECT 
      kompgajiKode
   FROM 
      sdm_ref_komponen_gaji
";

$sql['get_variabel_pegawai'] = "
SELECT
	jenis,
	jabatan,
	jenjang,
	jabatan_label,
	pddkAsldnrId as studi,
	dosen,
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
		IFNULL(jsr.jabstrukrNama,UPPER(jfrs.jabfungJenis)) AS jabatan_label,
		IF(UPPER(jfrs.jabfungJenis)<>'DOSEN','KARYAWAN',UPPER(jfrs.jabfungJenis)) AS dosen,
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
		LEFT JOIN sdm_ref_jabatan_struktural jsr ON jsr.jabstrukrId=js.jbtnJabstrukrId
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
		pegKodeResmi='%s'
) AS WHY
   
";


//old sql
$sql['get_biodata_pegawai_by_id_old'] = "
   SELECT 
      bdtpegNip AS nip,
      CONCAT((CASE WHEN b.unitkerjaNama IS NULL THEN ''
      WHEN b.unitkerjaNama IS NOT NULL THEN CONCAT(b.unitkerjaNama,' - ') END),
      a.unitkerjaNama) AS unit,
      bdtpegNama AS nama,
      bdtpegAlamat AS alamat,
      bdtpegNoRekening AS no_rekening,
      bankNama AS nama_bank,
      kompgajidtId AS id_komponen,
      kompgajidtNama AS nama_komponen
   FROM 
      biodata_pegawai
   LEFT JOIN unit_kerja_ref a ON bdtpegUnitkerjaId = a.unitkerjaId
   LEFT JOIN unit_kerja_ref b ON a.unitkerjaParentId = b.unitkerjaId
   LEFT JOIN bank_ref ON bdtpegBankId = bankId
   LEFT JOIN detail_pegawai ON pegdtBdtpegId = bdtpegId
   LEFT JOIN detail_komponen_gaji_ref ON pegdtKompgajidtId = kompgajidtId
   WHERE bdtpegId = '%s'
";

$sql['get_tanggal_awal'] = "
SELECT 
	DATE_ADD(CONCAT('%s','-',mstgajiTanggalGaji),INTERVAL -1 MONTH) AS awal
FROM
	sdm_ref_master_gaji
WHERE
	mstgajiPegId='%s'
";

$sql['get_tanggal_akhir'] = "
SELECT 
	DATE(CONCAT('%s','-',mstgajiTanggalGaji-1)) akhir
FROM
	sdm_ref_master_gaji
WHERE
	mstgajiPegId='%s'
";

$sql['next_tanggal'] = "
SELECT DATE_ADD(DATE('%s'),INTERVAL 1 DAY) AS tgl
";

$sql['is_lewat'] = "
SELECT  DATEDIFF(DATE('%s'),DATE('%s')) AS isLewat
";

$sql['is_libur'] = "
SELECT IF(UPPER(DAYNAME(DATE('%s'))) IN ('SUNDAY') OR DATE('%s') IN (SELECT hariliburTgl FROM sdm_ref_hari_libur),1,0) AS isLibur
";

$sql['is_masuk'] = "
SELECT 
	COUNT(*) AS IsMasuk,
	absensiKode AS IsIzin,
	IF((absensiTerlambat>30)OR(absensiPulangCepat>30),2,
	(IF((absensiTerlambat BETWEEN 1 AND 30)OR(absensiPulangCepat BETWEEN 1 AND 30),1,0))) as kategori
FROM
	sdm_absensi
	INNER JOIN pub_pegawai ON pegKodeGateAccess=absensiPegKodeGateAccess
WHERE
	pegId='%s' AND DATE(absensiTglMasuk)=DATE('%s') AND FLOOR(absensiLamaWaktu/60)>=4
";

?>
