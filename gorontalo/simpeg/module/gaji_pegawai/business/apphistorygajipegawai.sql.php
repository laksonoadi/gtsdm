<?php

$sql['get_data'] = "
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
      a.gajipegPegId=%s AND a.gajipegStatus=1
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

$sql['get_count_data'] = "
   SELECT 
      count(gajipegId) AS total
   FROM 
      sdm_gaji_pegawai
      JOIN sdm_gaji_pegawai_detail ON (gajipegdtGajipegId = gajipegId)
      JOIN komponen_formula ON (kompformId = gajipegdtKompformId)
	WHERE
      gajipegPegId=%s AND a.gajipegStatus=1
      %s
";

$sql['get_periode'] = "
   SELECT
      YEAR(MIN(thanggarBuka)) as `awal`,
      DATE(NOW()) as `selected`,
      YEAR(MAX(thanggarTutup)) as `akhir`
   FROM
      tahun_anggaran
";

$sql['get_gapok_swasta'] = "
SELECT 
   kgbGajiPokokBaru AS 'gapok'
FROM 
   sdm_kenaikan_gaji_berkala
WHERE 
   kgbPegKode = '%s' and kgbAktif = 'Aktif'
";

$sql['get_gapok_negeri'] = "
SELECT 
   b.kompgajidtNominal AS 'gapok'
FROM 
   sdm_ref_gaji_pokok a
   LEFT JOIN sdm_ref_komponen_gaji_detail b ON b.kompgajidtId = a.gapokKompGajiDetId
   LEFT JOIN sdm_kenaikan_gaji_berkala c ON c.kgbPktgolId = a.gapokPktgolrId  AND c.kgbMasaKerja = a.gapokMasaKerja
WHERE 
   c.kgbPegKode = '%s' and c.kgbAktif = 'Aktif'
";

$sql['get_data_by_id']="
SELECT 
   pegKodeResmi as 'nip',
   pegNama as 'nama',
   pegAlamat as 'alamat',
   pegFoto as 'foto'
FROM 
   pub_pegawai
WHERE 
   pegId = %s
";

#tambahan untuk cetak slip gaji
$sql['get_data_cetak_backup'] = "
    SELECT 
      pegKodeResmi AS nip_pegawai,
      pegNama AS nama_pegawai,
      kompformId as `kolom_id`, 
      kompformNama as `kolom`, 
      EXTRACT(YEAR_MONTH FROM gajipegPeriode) as `periode`,   
      gajipegdtNominalKomponen as `nominal`, 
      gajipegTotalgaji as `total`,
      SUM(pndptnlainNominal) AS `plain`
      FROM 
         sdm_gaji_pegawai
         LEFT JOIN sdm_gaji_pegawai_detail ON (gajipegdtGajipegId = gajipegId)
         LEFT JOIN sdm_komponen_formula ON (kompformId = gajipegdtKompformId)
         LEFT JOIN pub_pegawai ON (gajipegPegId = pegId)
	       LEFT JOIN sdm_pendapatan_lain ON (pndptnlainGajiPegId=gajipegId)
      WHERE
         gajipegPegId='%s'
         AND EXTRACT(YEAR_MONTH FROM gajipegPeriode) = '%s'
      GROUP BY 
	    pegKodeResmi,
      pegNama,
      kompformId, 
      kompformNama, 
      EXTRACT(YEAR_MONTH FROM gajipegPeriode),   
      gajipegdtNominalKomponen, 
      gajipegTotalgaji
      ORDER BY kompformId
";

$sql['get_data_cetak_det'] = "
SELECT
	NULL AS kolom_id,
	'GDP' AS kolom,
	gajipegdtNominalKomponen AS nominal,
	'TUNJANGAN' AS jenis
FROM
	sdm_gaji_pegawai
	LEFT JOIN sdm_gaji_pegawai_detail ON (gajipegdtGajipegId = gajipegId)
WHERE
	gajipegPegId='%s' AND EXTRACT(YEAR_MONTH FROM gajipegPeriode) = '%s' AND gajipegdtKompformId IS NULL
UNION
SELECT 
    kompformId AS `kolom_id`, 
    IFNULL(IF(UPPER(kompformNama) LIKE 'POTONGAN %%',REPLACE(kompformNama,'Potongan ',''),kompformNama),'GDP') AS `kolom`, 
    ABS(IFNULL(gajipegdtNominalKomponen,0)) AS `nominal`, 
    IF(UPPER(kompformNama) LIKE 'POTONGAN %%','POTONGAN','TUNJANGAN') AS jenis
FROM 
	sdm_komponen_formula
	LEFT JOIN sdm_gaji_pegawai_detail ON (kompformId = gajipegdtKompformId)
    LEFT JOIN sdm_gaji_pegawai ON (gajipegdtGajipegId = gajipegId)
    LEFT JOIN pub_pegawai ON (gajipegPegId = pegId)
	LEFT JOIN sdm_pendapatan_lain ON (pndptnlainGajiPegId=gajipegId)
WHERE
    (gajipegPegId='%s' OR gajipegPegId IS NULL)
    AND (EXTRACT(YEAR_MONTH FROM gajipegPeriode) = '%s' OR EXTRACT(YEAR_MONTH FROM gajipegPeriode) IS NULL )
GROUP BY 
	pegKodeResmi,
	pegNama,
	kompformId, 
	EXTRACT(YEAR_MONTH FROM gajipegPeriode)
ORDER BY kolom_id
";

$sql['get_data_cetak'] = "
SELECT 
	pegKodeResmi AS nip_pegawai,
	pegNama AS nama_pegawai,
	pktgolPktgolrId AS pangkat_golongan,
	jabstrukrNama AS jabatan_struktural,
	satkerNama AS unit_kerja,
	EXTRACT(YEAR_MONTH FROM gajipegPeriode) AS `periode`,   
	gajipegTotalgaji AS total
FROM 
	sdm_gaji_pegawai
        LEFT JOIN pub_pegawai ON gajipegPegId = pegId
	LEFT JOIN sdm_pendapatan_lain ON (pndptnlainGajiPegId=gajipegId)
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId
	LEFT JOIN sdm_jabatan_struktural ON jbtnPegKode=pegId AND jbtnStatus='Aktif'
	LEFT JOIN sdm_ref_jabatan_struktural ON jbtnJabstrukrId=jabstrukrId
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId	
WHERE
	gajipegPegId='%s' AND EXTRACT(YEAR_MONTH FROM gajipegPeriode) = '%s'
GROUP BY pegId
";

?>
