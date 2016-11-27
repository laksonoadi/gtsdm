<?php

//===GET===

$sql['get_data_pegawai']="
SELECT 
	pegId as id,
	pegNama as name,
	pegKelamin as kelamin,
	pegKodeResmi as kode,
	pegStatusNPWP as statusnpwp,
	pegNoNPWP as npwp,
	pegJnspegrId as jenis,
	pegTglMasukInstitusi as tgl_masuk,
	pegTglKeluarInstitusi as tgl_keluar,
	pegdtKategori as kategori
FROM
	pub_pegawai
	LEFT JOIN sdm_pegawai_detail ON pegdtPegId = pegId
WHERE pegId='%s' 
"; 

$sql['get_count_data_pphrp'] = 
   "SELECT 
      count(*) AS total
	FROM 
		pub_pegawai
	LEFT JOIN
	pph_pegawai_potongan ON pphpegpotPegId=pegId
	WHERE 
		pegKodeResmi LIKE '%s' AND pegNama LIKE '%s'
";

$sql['get_data_komp_peg'] = 
   "SELECT 
     pegId			AS pegawai_id,
	  pegNama		AS nama_pegawai,
	  pegKodeResmi	AS nip_pegawai,
	  pegNoNPWP	AS npwp_pegawai,
	  pphpegpotId 	AS pot_id,
	  IFNULL(pphpegpotNilai,'-')	AS potongan_perbl,
	  IFNULL((SELECT pphpegpotNilai FROM pph_pegawai_potongan WHERE pphpegpotPegId=pegId AND EXTRACT(YEAR_MONTH FROM pphpegpotPeriode)='%s'),'-') AS nominal_januari,
	  IFNULL((SELECT pphpegpotNilai FROM pph_pegawai_potongan WHERE pphpegpotPegId=pegId AND EXTRACT(YEAR_MONTH FROM pphpegpotPeriode)='%s'),'-') AS nominal_februari,
	  IFNULL((SELECT pphpegpotNilai FROM pph_pegawai_potongan WHERE pphpegpotPegId=pegId AND EXTRACT(YEAR_MONTH FROM pphpegpotPeriode)='%s'),'-') AS nominal_maret,
	  IFNULL((SELECT pphpegpotNilai FROM pph_pegawai_potongan WHERE pphpegpotPegId=pegId AND EXTRACT(YEAR_MONTH FROM pphpegpotPeriode)='%s'),'-') AS nominal_april,
	  IFNULL((SELECT pphpegpotNilai FROM pph_pegawai_potongan WHERE pphpegpotPegId=pegId AND EXTRACT(YEAR_MONTH FROM pphpegpotPeriode)='%s'),'-') AS nominal_mei,
	  IFNULL((SELECT pphpegpotNilai FROM pph_pegawai_potongan WHERE pphpegpotPegId=pegId AND EXTRACT(YEAR_MONTH FROM pphpegpotPeriode)='%s'),'-') AS nominal_juni,
	  IFNULL((SELECT pphpegpotNilai FROM pph_pegawai_potongan WHERE pphpegpotPegId=pegId AND EXTRACT(YEAR_MONTH FROM pphpegpotPeriode)='%s'),'-') AS nominal_juli,
	  IFNULL((SELECT pphpegpotNilai FROM pph_pegawai_potongan WHERE pphpegpotPegId=pegId AND EXTRACT(YEAR_MONTH FROM pphpegpotPeriode)='%s'),'-') AS nominal_agustus,
	  IFNULL((SELECT pphpegpotNilai FROM pph_pegawai_potongan WHERE pphpegpotPegId=pegId AND EXTRACT(YEAR_MONTH FROM pphpegpotPeriode)='%s'),'-') AS nominal_september,
	  IFNULL((SELECT pphpegpotNilai FROM pph_pegawai_potongan WHERE pphpegpotPegId=pegId AND EXTRACT(YEAR_MONTH FROM pphpegpotPeriode)='%s'),'-') AS nominal_oktober,
	  IFNULL((SELECT pphpegpotNilai FROM pph_pegawai_potongan WHERE pphpegpotPegId=pegId AND EXTRACT(YEAR_MONTH FROM pphpegpotPeriode)='%s'),'-') AS nominal_november,
	  IFNULL((SELECT pphpegpotNilai FROM pph_pegawai_potongan WHERE pphpegpotPegId=pegId AND EXTRACT(YEAR_MONTH FROM pphpegpotPeriode)='%s'),'-') AS nominal_desember
	FROM 
		pub_pegawai 
	LEFT JOIN
	pph_pegawai_potongan ON pphpegpotPegId=pegId
	WHERE 
		pegKodeResmi LIKE '%s' AND pegNama LIKE '%s'
   GROUP BY
	   pegId
   ORDER BY 
	   pegKodeResmi ASC
   LIMIT %s, %s";

$sql['get_data_komp_ptkp_by_peg_id'] ="
  SELECT
    pphptkpNama as nama, 
    pphptkpKode as kode, 
    pphptkpNominal as nominal, 
    pphptkpNominalTahunan as nominal_tahunan
  FROM
    pph_ptkp_ref
  LEFT JOIN pph_ptkp_nikah ON pphptkpnikahPphptkpId=pphptkpId
  LEFT JOIN sdm_ref_kode_nikah ON kodenikahId=pphptkpnikahKodenikahId
  LEFT JOIN sdm_pegawai_detail ON pegdtKodenikahId=kodenikahId
  LEFT JOIN pub_pegawai ON pegId=pegdtPegId
  WHERE
    pegId=%s 
    AND pegKelamin='%s'
  GROUP BY pphptkpKode
";

$sql['get_data_komp_peg_by_id'] = 
   "SELECT  
      c.kompformId as `id_formula`,
      c.kompformNama as `nama_formula`, 
      EXTRACT(YEAR_MONTH FROM a.gajipegPeriode) as `periode`,  
      b.gajipegdtNominalKomponen as `nominal_komponen`, 
      a.gajipegTotalgaji as `total`,
      a.gajipegPegId as `id_pegawai`,
		d.pegNama as `nama_pegawai`,
      SUM(p.pndptnlainNominal) AS `plain`,
		kompformFormula 			AS `komponen_formula`
   FROM 
      sdm_gaji_pegawai a
      LEFT JOIN sdm_gaji_pegawai_detail b ON (b.gajipegdtGajipegId = a.gajipegId)
      LEFT JOIN sdm_komponen_formula c ON (c.kompformId = b.gajipegdtKompformId)
      LEFT JOIN pub_pegawai d ON (a.gajipegPegId = d.pegId)
      LEFT JOIN sdm_pendapatan_lain p ON (p.pndptnlainGajiPegId=a.gajipegId)
	WHERE
      a.gajipegPegId='%s' 
      AND EXTRACT(YEAR_MONTH FROM a.gajipegPeriode)='%s'
      
  GROUP BY 
      c.kompformId, 
      c.kompformNama, 
      EXTRACT(YEAR_MONTH FROM a.gajipegPeriode), 
      b.gajipegdtNominalKomponen, 
      a.gajipegTotalgaji,
      a.gajipegId
   ORDER BY a.gajipegPegId";
	
$sql['get_data_pph_pegawai_komponen'] = 
   "SELECT
		pphpegkompId 		AS id_komp_peg,
		pphpegkompPegawaiId AS id_pegawai,
		pphkompformNama  	AS komp_form_nama,
		pphpegkompNominal 	AS nominal_komp_peg

	FROM 
		pph_pegawai_komponen

	JOIN pph_komponen_formula ON pphkompformId=pphpegkompFormulaId
	WHERE pphpegkompPegawaiId='%s' 
	AND EXTRACT(YEAR_MONTH FROM pphpegkompPeriode)='%s'
	ORDER BY pphpegkompTanggal DESC";

$sql['get_max_value'] = 
   "SELECT
		pphkompformId 			AS formula_id,
		pphkompformFormula 		AS formula,
		pphkompformMaxNominal 	AS max_value
	FROM 
		pph_komponen_formula
	WHERE pphkompformId='%s'";
	
$sql['get_jumlah_nominal'] = 
   "SELECT 
		SUM(pphpegkompNominal) AS jumlah 
	FROM pph_pegawai_komponen 
	WHERE pphpegkompPegawaiId='%s'
  AND EXTRACT(YEAR_MONTH FROM pphpegkompPeriode)='%s'";

$sql['get_data_potongan'] = 
   "SELECT 
		pphrpNama 		AS persenPotongan,
		pphrpNominalMax AS nominalMax 
	FROM pph_range_potongan_ref 
	ORDER BY pphrpOrder ASC";

$sql['get_data_peg_pot'] = 
   "SELECT 
		pphpegpotId 		AS pot_id,
		pphpegpotNilai 		AS potongan_perbl,
		pphpegpotNilaiNoNPWP 		AS potongan_perbl_no_npwp
	FROM pph_pegawai_potongan
	WHERE pphpegpotPegId='%s'
  AND EXTRACT(YEAR_MONTH FROM pphpegpotPeriode)='%s'";

$sql['get_total_gaji'] = 
   "SELECT
		SUM(gajipegdtNominalKomponen) AS total_gaji
	FROM 
		pub_pegawai
  JOIN sdm_gaji_pegawai ON gajipegPegId=pegId
	JOIN sdm_gaji_pegawai_detail ON gajipegdtGajipegId=gajipegId
	JOIN sdm_komponen_formula ON gajipegdtKompformId=kompformId

	WHERE gajipegId=(SELECT gajipegId FROM sdm_gaji_pegawai 
					WHERE gajipegPegId='%s' AND 
					gajipegPeriode=(SELECT max(gajipegPeriode) FROM sdm_gaji_pegawai where gajipegPegId='%s') group by gajipegPegId)";

$sql['get_gaji_pokok'] =
"SELECT 
	kgbGajiPokokBaru as 'gaji_pokok'
FROM
	sdm_kenaikan_gaji_berkala
WHERE
	kgbPegKode='%s' AND kgbAktif = 'Aktif'"
;

//untuk revisi--
$sql['get_komponen_gaji_peg'] = "
   SELECT 
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
   #JOIN sdm_pegawai_detail ON pegdtId = kompgajipegdtPegId
   #JOIN pub_pegawai ON pegId = pegdtPegId
   JOIN pub_pegawai ON pegId = kompgajipegdtPegId
   JOIN sdm_pegawai_detail ON pegdtPegId = pegId
   WHERE pegId = '%s') b ON a.kompgajiKode = b.kompgajiKode
";

$sql['get_formula_pph']= "
   SELECT
		pphkompformFormula 		AS formula
	FROM 
		pph_komponen_formula
	WHERE pphkompformId='%s'
";

$sql['get_bulan_masa_kerja_per_tahun']="
  SELECT
    (IF(YEAR(pegTglKeluarInstitusi)=YEAR(now()),MONTH(pegTglKeluarInstitusi),12)-IF(YEAR(pegTglMasukInstitusi)=YEAR(now()),MONTH(pegTglMasukInstitusi),1))+1 AS masa_kerja
  FROM
    pub_pegawai
  WHERE
    pegId=%s
";

//-----------
					
//===DO===

$sql['do_add_pphkomp'] = 
   "INSERT INTO pph_pegawai_komponen 
      (pphpegkompPegawaiId, pphpegkompFormulaId, pphpegkompNominal, pphpegkompPeriode, pphpegkompTanggal, pphpegkompUserId )
   VALUES 
      ('%s', '%s', '%s', '%s', NOW() , '%s')";

$sql['do_add_pegawai_potongan'] = 
   "INSERT INTO pph_pegawai_potongan  
      (pphpegpotPegId , pphpegpotNilai, pphpegpotNilaiNoNPWP, pphpegpotPeriode, pphpegpotGanti)
   VALUES 
      ('%s', '%s', '%s', '%s', 'Tidak')";

$sql['do_update_pegawai_potongan'] = 
   "UPDATE pph_pegawai_potongan  
    SET
	  pphpegpotPegId='%s',
	  pphpegpotNilai='%s',
	  pphpegpotNilaiNoNPWP='%s',
	  pphpegpotPeriode='%s',
	  pphpegpotGanti='Tidak'
	WHERE pphpegpotId ='%s'";

$sql['do_delete_pphkomp_by_id'] = 
   "DELETE from pph_pegawai_komponen 
   WHERE 
      pphpegkompId ='%s'";

$sql['get_combo_komponen']="
SELECT
   pphkompformId AS id,
   pphkompformNama AS name
FROM 
   pph_komponen_formula
  
  order by pphkompformNama ASC";
?>