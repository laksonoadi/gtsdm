<?php

//===GET===
$sql['get_count_data'] = "
SELECT 
      count(*) AS total
   FROM 
      pub_pegawai
      LEFT JOIN sdm_gaji_pegawai ON (gajipegPegId = pegId)
      LEFT JOIN sdm_satuan_kerja_pegawai ON (satkerpegPegId = pegId)
	 %s%s
   GROUP BY pegId
";

$sql['get_data'] = "
   SELECT 
      a.pegId as `id`,
      a.pegKodeResmi as `nip`,
      a.pegNama as `nama`,
      a.pegAlamat as `alamat`,
      b.pegrekRekening as `rekening`,     
      if(c.mstgajiIsAktif='Ya',(SELECT gajipegTotalGaji FROM sdm_gaji_pegawai WHERE gajipegPegId = pegId AND EXTRACT(YEAR_MONTH FROM gajipegPeriode)='%s' ORDER BY gajipegId DESC LIMIT 0,1),0) AS `gaji`,      
      c.mstgajiIsAktif as `is_aktif`,
      if(c.mstgajiIsAktif='Ya',(SELECT gajipegStatus FROM sdm_gaji_pegawai WHERE gajipegPegId = pegId AND EXTRACT(YEAR_MONTH FROM gajipegPeriode)='%s' ORDER BY gajipegId DESC LIMIT 0,1),0) AS `status`,
      if(c.mstgajiIsAktif='Ya',(SELECT gajipegId FROM sdm_gaji_pegawai WHERE gajipegPegId = pegId AND EXTRACT(YEAR_MONTH FROM gajipegPeriode)='%s' ORDER BY gajipegId DESC LIMIT 0,1),0) AS `idGaji`
   FROM 
      pub_pegawai a
      LEFT JOIN pub_pegawai_rekening b ON (b.pegrekPegId = a.pegId)
      LEFT JOIN sdm_ref_master_gaji c ON (c.mstgajiPegId = a.pegId)
      LEFT JOIN sdm_satuan_kerja_pegawai d ON (d.satkerpegPegId = a.pegId)
      LEFT JOIN sdm_gaji_pegawai e ON (e.gajipegPegId = a.pegId)
   %s%s
   GROUP BY a.pegId
   ORDER BY 
      a.pegKodeResmi
   LIMIT %s, %s
";

$sql['get_data_cetak'] = "
   SELECT 
      a.pegId AS `id`,
      a.pegKodeResmi AS `nip`,
      a.pegNama AS `nama`,
      a.pegAlamat AS `alamat`,
	  a.pegNoNPWP AS npwp,
      IFNULL(jsr.jabstrukrNama,jfrs.jabfungJenis) AS jabatan,
      CONCAT(UPPER(REPLACE(pktr.pktgolrId,'/','')),'.',IF((COUNT(DISTINCT f.kgbId)-1)<0,0,(COUNT(DISTINCT f.kgbId)-1))+IFNULL(thpNilai,0)) AS gol,									
      IF((COUNT(DISTINCT f.kgbId)-1)<0,0,(COUNT(DISTINCT f.kgbId)-1))+IFNULL(thpNilai,0) AS thp,
	  f.kgbGajiPokokBaru AS gaji_pokok,
	  EXTRACT(YEAR_MONTH FROM e.gajipegPeriode) AS periode,
	  e.gajipegPeriode AS periode_id,
      b.pegrekRekening AS `rekening`, 
      IF(c.mstgajiIsAktif='Ya',(SELECT gajipegTotalGaji FROM sdm_gaji_pegawai WHERE gajipegPegId = pegId AND EXTRACT(YEAR_MONTH FROM gajipegPeriode)='%s' ORDER BY gajipegId DESC LIMIT 0,1),0) AS `gaji`,      
      c.mstgajiIsAktif AS `is_aktif`,
      IF(c.mstgajiIsAktif='Ya',(SELECT gajipegStatus FROM sdm_gaji_pegawai WHERE gajipegPegId = pegId AND EXTRACT(YEAR_MONTH FROM gajipegPeriode)='%s' ORDER BY gajipegId DESC LIMIT 0,1),0) AS `status`,
      IF(c.mstgajiIsAktif='Ya',(SELECT gajipegId FROM sdm_gaji_pegawai WHERE gajipegPegId = pegId AND EXTRACT(YEAR_MONTH FROM gajipegPeriode)='%s' ORDER BY gajipegId DESC LIMIT 0,1),0) AS `idGaji`,
	  IFNULL((SELECT kompgajidtNominal FROM sdm_ref_komponen_gaji_detail LEFT JOIN sdm_komponen_gaji_pegawai_detail ON kompgajipegdtKompgajidtrId=kompgajidtId AND kompgajidtId IN (48,49,50,51,52) WHERE kompgajipegdtPegId = pegId LIMIT 0,1),0)/12 AS PTKP,
      IFNULL((SELECT kompgajidtPersen FROM sdm_ref_komponen_gaji_detail LEFT JOIN sdm_komponen_gaji_pegawai_detail ON kompgajipegdtKompgajidtrId=kompgajidtId AND kompgajidtId IN (55) WHERE kompgajipegdtPegId = pegId LIMIT 0,1),0) AS BJB,
      IFNULL((SELECT kompgajidtPersen FROM sdm_ref_komponen_gaji_detail LEFT JOIN sdm_komponen_gaji_pegawai_detail ON kompgajipegdtKompgajidtrId=kompgajidtId AND kompgajidtId IN (409,410,411,412) WHERE kompgajipegdtPegId = pegId LIMIT 0,1),0) AS PPH
   FROM 
      pub_pegawai a
	  LEFT JOIN sdm_jabatan_struktural js ON js.jbtnPegKode=a.pegId AND js.jbtnStatus='Aktif'
	  LEFT JOIN sdm_ref_jabatan_struktural jsr ON jsr.jabstrukrId=js.jbtnJabstrukrId
	  LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=a.pegId AND jf.jbtnStatus='Aktif'
	  LEFT JOIN pub_ref_jabatan_fungsional jfr ON jfr.jabfungrId=jf.jbtnJabfungrId
	  LEFT JOIN pub_ref_jabatan_fungsional_jenis jfrs ON jfr.jabfungrJenisrId=jfrs.jabfungjenisrId
	  LEFT JOIN sdm_pangkat_golongan pkt ON pkt.pktgolPegKode=a.pegId AND pkt.pktgolStatus='Aktif'
	  LEFT JOIN sdm_ref_pangkat_golongan pktr ON pktr.pktgolrId=pkt.pktgolPktgolrId
	  LEFT JOIN sdm_thp ON thpPegKode=a.pegId
      LEFT JOIN pub_pegawai_rekening b ON (b.pegrekPegId = a.pegId)
      LEFT JOIN sdm_ref_master_gaji c ON (c.mstgajiPegId = a.pegId)
      LEFT JOIN sdm_satuan_kerja_pegawai d ON (d.satkerpegPegId = a.pegId)
      LEFT JOIN sdm_gaji_pegawai e ON (e.gajipegPegId = a.pegId)
	  LEFT JOIN sdm_kenaikan_gaji_berkala f ON (f.kgbPegKode = a.pegId)
	  LEFT JOIN sdm_gaji_pegawai_detail g ON e.gajipegId= g.gajipegdtGajipegId
   %s%s AND EXTRACT(YEAR_MONTH FROM e.gajipegPeriode)='%s'And NOT (g.gajipegdtKompformId IS NULL)
   GROUP BY a.pegId
   ORDER BY 
      a.pegKodeResmi
";

$sql['get_data_by_id'] = "
SELECT 
      a.pegId as `id`,
      a.pegKodeResmi as `nip`,
      a.pegNama as `nama`,
      a.pegAlamat as `alamat`,
      a.pegNoHp as `hp`,
      a.pegNoTelp as `telp`,
      b.pegrekRekening as `rekening`,
      b.pegrekResipien as `resipien`,
      b.pegrekBankId as `bank`,
      c.bankNama as `bank_label`,
		  e1.satkerNama AS `satker_unit`,
      d.mstgajiIsCash as cash,
      d.mstgajiIsAktif as aktif,
      d.mstgajiTanggalGaji as tgl_gajian
   FROM 
      pub_pegawai a
      LEFT JOIN pub_pegawai_rekening b ON (b.pegrekPegId = a.pegId)
      LEFT JOIN pub_ref_bank c ON (c.bankId = b.pegrekBankId)
      LEFT JOIN sdm_ref_master_gaji d ON (d.mstgajiPegId = a.pegId)
      LEFT JOIN sdm_satuan_kerja_pegawai e ON (e.satkerpegPegId = a.pegId)
      LEFT JOIN pub_satuan_kerja e1 ON (e1.satkerId = e.satkerpegSatkerId)
   WHERE
      a.pegId='%s'
";

$sql['get_total_pegawai_aktif'] = "
   SELECT 
      COUNT(*) as total
   FROM 
      pub_pegawai
      LEFT JOIN sdm_ref_master_gaji ON (mstgajiPegId = pegId)
   WHERE mstgajiIsAktif='Ya'
      ";
      

$sql['get_total_seluruh'] = "
   SELECT SUM(gaji) AS total FROM(
SELECT  
      (SELECT gajipegTotalGaji FROM sdm_gaji_pegawai WHERE gajipegPegId = pegId AND gajipegPeriode BETWEEN '%s' AND '%s' ORDER BY gajipegId DESC LIMIT 0,1) AS gaji      
   FROM 
      pub_pegawai 
	    LEFT JOIN sdm_ref_master_gaji ON (mstgajiPegId = pegId)
   WHERE 
      mstgajiIsAktif='Ya'
      ) a
      ";
      
$sql['get_komponen_by_id'] = "
SELECT 
      kompgajidtId as id,
	    kompgajidtKode as kode,
	    kompgajidtNama as nama
   FROM 
      sdm_ref_komponen_gaji_detail
	    JOIN sdm_komponen_gaji_pegawai_detail ON (kompgajipegdtKompgajidtrId = kompgajidtId)
   WHERE
      kompgajipegdtPegId='%s'
";

$sql['get_data_komponen'] = "
SELECT
	kompformId AS id,
	kompformNama  AS nama
FROM 
	sdm_komponen_formula 
";

$sql['get_data_komponen_isi'] = "
SELECT  
      c.kompformId as `kolom_id`, 
      c.kompformNama as `kolom`, 
      EXTRACT(YEAR_MONTH FROM a.gajipegPeriode) as `periode`,  
      b.gajipegdtNominalKomponen as `nominal`, 
      a.gajipegTotalgaji as `total`,
      a.gajipegId as `id`,
      SUM(p.pndptnlainNominal) AS `plain`
   FROM 
      sdm_gaji_pegawai a
      LEFT JOIN sdm_gaji_pegawai_detail b ON (b.gajipegdtGajipegId = a.gajipegId)
      LEFT JOIN sdm_komponen_formula c ON (c.kompformId = b.gajipegdtKompformId)
      LEFT JOIN pub_pegawai d ON (a.gajipegPegId = d.pegId)
      LEFT JOIN sdm_pendapatan_lain p ON (p.pndptnlainGajiPegId=a.gajipegId)
	WHERE
      a.gajipegPegId= %s  AND NOT (gajipegdtKompformId IS NULL)
      %s
  GROUP BY 
      c.kompformId, 
      c.kompformNama, 
      EXTRACT(YEAR_MONTH FROM a.gajipegPeriode), 
      b.gajipegdtNominalKomponen, 
      a.gajipegTotalgaji,
      a.gajipegId
   ORDER BY a.gajipegPegId
";

$sql['get_data_nominal_pendapatan_lain'] = "
SELECT 
	pndptnlainJnsId AS id,
	pndptnlainNominal AS nominal
FROM 
	sdm_pendapatan_lain
WHERE	 
	pndptnlainPegId ='%s' AND pndptnlainTanggal = '%s'
";

$sql['cek_data'] = "
SELECT 
      pegrekId
   FROM 
      pub_pegawai_rekening
   WHERE
      pegrekPegId='%s'
";

$sql['get_gaji_pegawai_det_by_id'] = "
SELECT
	pegKodeResmi AS nip,
	pegNama AS nama,
	gajipegPeriode AS periode,
	gajipegTotalGaji AS nominal
FROM
	sdm_gaji_pegawai
	LEFT JOIN pub_pegawai ON pegId=gajipegPegId
WHERE
	gajipegId='%s'
";


//===DO===

$sql['do_up_status_by_array_id'] ="
   UPDATE 
      sdm_gaji_pegawai
   SET
      gajipegStatus = '1'
   WHERE 
      gajipegPegId IN ('%s') AND EXTRACT(YEAR_MONTH FROM gajipegPeriode) IN ('%s')
";

$sql['do_up_status'] ="
   UPDATE 
      sdm_gaji_pegawai
   SET
      gajipegStatus = '1'
   WHERE 
      gajipegPegId ='%s' AND EXTRACT(YEAR_MONTH FROM gajipegPeriode) ='%s'
";

$sql['update_pendapatan_pegawai']="
UPDATE 
  sdm_pendapatan_lain 
SET 
  pndptnlainGajiPegId = '%s' 
WHERE 
  pndptnlainPegId = '%s' and pndptnlainGajiPegId is NULL
";

$sql['do_add_data'] = "
INSERT INTO sdm_ref_master_gaji
SET
    mstgajiIsCash = '%s',
    mstgajiTanggalGaji = '%s',
    mstgajiIsAktif = '%s',
    mstgajiPegId = '%s'
";

$sql['do_update_data'] = "
   UPDATE 
      sdm_ref_master_gaji
   SET
      mstgajiIsCash = '%s',
      mstgajiTanggalGaji = '%s',
      mstgajiIsAktif = '%s'
   WHERE 
      mstgajiPegId = '%s'
";

$sql['do_add_data_2'] = "
   INSERT INTO 
      pub_pegawai_rekening
      (pegrekPegId, pegrekBankId, pegrekRekening, pegrekCreationDate, pegrekUserId)
   VALUES 
      (%s, %s, '%s', now(), %s)
";

$sql['do_update_data_2'] = "
   UPDATE 
      pub_pegawai_rekening
   SET
      pegrekRekening = '%s',
      pegrekBankId = '%s',
      pegrekLastUpdate = now(),
      pegrekUserId = '%s'
   WHERE 
      pegrekPegId = '%s'";
      
$sql['do_add_komponen'] ="
   INSERT INTO 
      sdm_komponen_gaji_pegawai_detail
      (kompgajipegdtPegId, kompgajipegdtKompgajidtrId,kompgajipegdtTanggal)
   VALUES 
      (%s, %s, '%s')";

$sql['do_delete_data'] = "
   DELETE FROM 
     sdm_gaji_pegawai
   WHERE 
      gajipegPegId ='%s' AND EXTRACT(YEAR_MONTH FROM gajipegPeriode) = '%s'
";

/*$sql['do_delete_data_by_array_id'] = "
   DELETE FROM 
      biodata_pegawai
   WHERE 
      bdtpegId IN ('%s')";
*/
$sql['do_delete_data_by_array_id'] = "
   DELETE FROM 
      sdm_gaji_pegawai
   WHERE 
      gajipegPegId IN ('%s') AND EXTRACT(YEAR_MONTH FROM gajipegPeriode) IN ('%s')
";
      
$sql['do_delete_komponen'] = "
   DELETE FROM 
      sdm_komponen_gaji_pegawai_detail
   WHERE 
      kompgajipegdtPegId=%s
";
?>
