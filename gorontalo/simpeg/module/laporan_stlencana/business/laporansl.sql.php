<?php
$sql['get_combo_unit_kerja'] = "
SELECT
  satkerId as id,
  satkerNama as name
FROM 
  pub_satuan_kerja
";

$sql['get_combo_status'] = "
SELECT
  statrId AS id,
  statrPegawai AS name
FROM 
  sdm_ref_status_pegawai
WHERE 1=1 %filter%
";

$sql['get_combo_jenisfungsional'] = "
SELECT
  jabfungjenisrId as id,
  jabfungJenis as name
FROM 
  pub_ref_jabatan_fungsional_jenis jfrs
WHERE 1=1 %filter%
";

$sql['get_combo_golongan'] = "
SELECT
  *
FROM
  (SELECT
    pktgolrId as id,
    pktgolrId,
    if(pktgolrTingkat=0,'0',pktgolrId) as name,
    pktgolrTingkat as tingkat,
    pktgolrUrut as urut
  FROM 
    sdm_ref_pangkat_golongan
  ORDER BY pktgolrTingkat) as a
WHERE 1=1 %filter%
GROUP BY name
ORDER BY urut DESC
";

$sql['get_combo_fungsional'] = "
SELECT
  jabfungrId as id,
  jabfungrNama as name
FROM 
  pub_ref_jabatan_fungsional jfr
WHERE 1=1 %filter%
ORDER BY jabfungrTingkat DESC
";

$sql['get_combo_struktural'] = "
SELECT
    jabstrukrId AS id,
    jabstrukrNama AS name
FROM 
    sdm_ref_jabatan_struktural
ORDER BY jabstrukrTingkat
";

$sql['get_combo_eselon'] = "
SELECT '0' as id, '0' as name UNION
SELECT 'IA' as id, 'IA' as name UNION
SELECT 'IB' as id, 'IB' as name UNION
SELECT 'IIA' as id, 'IIA' as name UNION
SELECT 'IIB' as id, 'IIB' as name UNION
SELECT 'IIIA' as id, 'IIIA' as name UNION
SELECT 'IIIB' as id, 'IIIB' as name UNION
SELECT 'IVA' as id, 'IVA' as name UNION
SELECT 'IVB' as id, 'IVB' as name
";

$sql['get_combo_pendidikan'] = "
SELECT
    pendId as id,
    pendNama as name
FROM 
    pub_ref_pendidikan
WHERE 1=1 %filter%
ORDER BY pendPendkelId DESC, pendId DESC
";

$sql['get_combo_jenis'] = "
SELECT
  jnspegrId as id,
  jnspegrNama as name
FROM 
  sdm_ref_jenis_pegawai
WHERE 1=1 %filter%
";

$sql['get_combo_agama'] = "
SELECT
  agmId as id,
  agmNama as name
FROM 
  pub_ref_agama
";

$sql['get_combo_statnikah'] = "
SELECT
  statnkhId as id,
  statnkhNama as name
FROM 
  pub_ref_status_nikah
";

$sql['get_combo_usia'] = "
SELECT 'A' as id, '20 ke bawah' as name UNION
SELECT 'B' as id, '21 s/d 30' as name UNION
SELECT 'C' as id, '31 s/d 40' as name UNION
SELECT 'D' as id, '41 s/d 50' as name UNION
SELECT 'E' as id, '51 s/d 60' as name UNION
SELECT 'F' as id, '61 ke atas' as name
";

$sql['get_combo_masakerja'] = "
SELECT 'A' as id, '0 s/d 10' as name UNION
SELECT 'B' as id, '11 s/d 20' as name UNION
SELECT 'C' as id, '21 s/d 30' as name UNION
SELECT 'D' as id, '31 s/d 40' as name UNION
SELECT 'E' as id, '41 s/d 50' as name UNION
SELECT 'F' as id, '51 s/d 60' as name UNION
SELECT 'G' as id, '61 ke atas' as name
";

$sql['get_combo_unit'] = "
SELECT
  satkerId AS id,
  ROUND((LENGTH(satkerLevel)-LENGTH(REPLACE(satkerLevel, '.', '')))/LENGTH('.')) AS level,
  satkerNama AS name
FROM 
  pub_satuan_kerja
WHERE 1=1 %filter%
ORDER BY satkerLevel
";

$sql['get_combo_jabatan_fungsional'] = "
SELECT
  jabfungjenisrId as id,
  jabfungJenis as name
FROM 
  pub_ref_jabatan_fungsional_jenis
";

            
$sql['get_daftar_pegawai']="
SELECT
  *
FROM
  (SELECT
    pegId,
    CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,'')) AS pegNama,
    pegKodeResmi,
    pegNoKarpeg,
    pegAlamat,
    pegKelurahan,
    pegKecamatan,
    
    pegNoTaspen,
    pegNoNPWP,
    pegPnsSK,
    pegPnsTmt,
    pktgolNoSk AS pegKenpaSK,
    
    pegTmpLahir,
    pegTglLahir,
    
    pegKelamin,
    jnspegrNama,
    
    satkerId,
    satkerNama,
  
    pktgolrNama,
    pktgolrId,
    pktgolTmt,
    
    jabfungrId,
    jabfungrNama,
    jf.jbtnTglMulai AS tmt_fungsional,
    jf.jbtnSkNmr AS sk_fungsional,
    
    jabstrukrId,
    jabstrukrNama,
    js.jbtnTglMulai AS tmt_struktural,
    js.jbtnSkNmr AS sk_struktural,
  
    pendId,
    IF(pendNama IS NULL,NULL,IF(UPPER(pendNama)='S3','Doktor',IF(UPPER(pendNama)='S2','Magister',IF(UPPER(pendNama)='S1','Sarjana',pendNama)))) as pendNama,
    pddkInstitusi,
    pddkJurusan,
    pddkThnLulus,
    
    YEAR(pelTglMulai) as jnspelrTahun,
    jnspelrNama,
    pelJmlJam,
    DATEDIFF(NOW(),pegCpnsTmt) as hari,
    jbtnEselon,
    
    ROUND(IF(YEAR(pegTglLahir)='0000' OR MONTH(pegTglLahir)='00' OR DATE(pegTglLahir)='00',0,DATEDIFF(NOW(),pegTglLahir))/365) as usia,
    
    ROUND(IF(YEAR(pegTglMasukInstitusi)='0000' OR MONTH(pegTglMasukInstitusi)='00' OR DATE(pegTglMasukInstitusi)='00',0,DATEDIFF(NOW(),pegTglMasukInstitusi))) as mks,
    
    agmNama,
    sutriNoKartu
    
  FROM
    pub_pegawai
    LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
  
    LEFT JOIN sdm_pangkat_golongan pkt ON pkt.pktgolPegKode=pegId AND pkt.pktgolStatus='Aktif'
    LEFT JOIN sdm_ref_pangkat_golongan pktr ON pktr.pktgolrId=pkt.pktgolPktgolrId 
  
    LEFT JOIN sdm_jabatan_struktural js ON  js.jbtnPegKode=pegId AND js.jbtnStatus='Aktif'
    LEFT JOIN sdm_ref_jabatan_struktural jsr ON js.jbtnJabstrukrId=jsr.jabstrukrId
    
    LEFT JOIN sdm_jabatan_fungsional jf ON  jf.jbtnPegKode=pegId AND jf.jbtnStatus='Aktif'
    LEFT JOIN pub_ref_jabatan_fungsional jfr ON jf.jbtnJabfungrId=jfr.jabfungrId
    
    LEFT JOIN sdm_pelatihan ON pelPegKode=pegId
    LEFT JOIN sdm_ref_jenis_pelatihan ON pelJnspelrId=jnspelrId
  
    LEFT JOIN sdm_pendidikan ON pddkPegKode=pegId AND pddkStatusTamat='Selesai'
    LEFT JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
      
  
    LEFT JOIN sdm_satuan_kerja_pegawai sk ON sk.satkerpegPegId=pegId AND sk.satkerpegAktif='Aktif'
    LEFT JOIN pub_satuan_kerja skr ON skr.satkerId=sk.satkerpegSatkerId
    
    LEFT JOIN pub_ref_agama ON agmId=pegAgamaId
    LEFT JOIN sdm_istri_suami ON sutriPegId=pegId
    LEFT JOIN sdm_verifikasi_data ON pegId=verdataValue 
  WHERE
    1=1 AND verdataStatus='3' AND verdataVerifikasiId='19'
    AND (skr.satkerLevel LIKE CONCAT('%s', '.%%') OR skr.satkerId = '%s')
    %filter%
  ORDER BY pktgolrTingkat DESC,pktgolrUrut DESC, pddkThnLulus DESC ) AS temp
WHERE 1=1  %search%
GROUP BY pegId

%LIMIT%
";

$sql['get_count_daftar_pegawai']="
  SELECT
      COUNT(DISTINCT pegId) as TOTAL,
      mks,
      pegTglMasukInstitusi
FROM
  (SELECT
    pegId,
    CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,'')) AS pegNama,
    pegKodeResmi,
    pegNoKarpeg,
    pegAlamat,
    pegKelurahan,
    pegKecamatan,
    pegTglMasukInstitusi,
    pegNoTaspen,
    pegNoNPWP,
    pegPnsSK,
    pegPnsTmt,
    pktgolNoSk AS pegKenpaSK,
    
    pegTmpLahir,
    pegTglLahir,
    
    pegKelamin,
    jnspegrNama,
    
    satkerId,
    satkerNama,
  
    pktgolrNama,
    pktgolrId,
    pktgolTmt,
    
    jabfungrId,
    jabfungrNama,
    jf.jbtnTglMulai AS tmt_fungsional,
    jf.jbtnSkNmr AS sk_fungsional,
    
    jabstrukrId,
    jabstrukrNama,
    js.jbtnTglMulai AS tmt_struktural,
    js.jbtnSkNmr AS sk_struktural,
  
    pendId,
    IF(pendNama IS NULL,NULL,IF(UPPER(pendNama)='S3','Doktor',IF(UPPER(pendNama)='S2','Magister',IF(UPPER(pendNama)='S1','Sarjana',pendNama)))) as pendNama,
    pddkInstitusi,
    pddkJurusan,
    pddkThnLulus,
    
    YEAR(pelTglMulai) as jnspelrTahun,
    jnspelrNama,
    pelJmlJam,
    DATEDIFF(NOW(),pegCpnsTmt) as hari,
    jbtnEselon,
    
    ROUND(IF(YEAR(pegTglLahir)='0000' OR MONTH(pegTglLahir)='00' OR DATE(pegTglLahir)='00',0,DATEDIFF(NOW(),pegTglLahir))/365) as usia,
    
    ROUND(IF(YEAR(pegTglMasukInstitusi)='0000' OR MONTH(pegTglMasukInstitusi)='00' OR DATE(pegTglMasukInstitusi)='00',0,DATEDIFF(NOW(),pegTglMasukInstitusi))) as mks,
    
    agmNama,
    sutriNoKartu
    
  FROM
    pub_pegawai
    LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
  
    LEFT JOIN sdm_pangkat_golongan pkt ON pkt.pktgolPegKode=pegId AND pkt.pktgolStatus='Aktif'
    LEFT JOIN sdm_ref_pangkat_golongan pktr ON pktr.pktgolrId=pkt.pktgolPktgolrId 
  
    LEFT JOIN sdm_jabatan_struktural js ON  js.jbtnPegKode=pegId AND js.jbtnStatus='Aktif'
    LEFT JOIN sdm_ref_jabatan_struktural jsr ON js.jbtnJabstrukrId=jsr.jabstrukrId
    
    LEFT JOIN sdm_jabatan_fungsional jf ON  jf.jbtnPegKode=pegId AND jf.jbtnStatus='Aktif'
    LEFT JOIN pub_ref_jabatan_fungsional jfr ON jf.jbtnJabfungrId=jfr.jabfungrId
    
    LEFT JOIN sdm_pelatihan ON pelPegKode=pegId
    LEFT JOIN sdm_ref_jenis_pelatihan ON pelJnspelrId=jnspelrId
  
    LEFT JOIN sdm_pendidikan ON pddkPegKode=pegId AND pddkStatusTamat='Selesai'
    LEFT JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
      
  
    LEFT JOIN sdm_satuan_kerja_pegawai sk ON sk.satkerpegPegId=pegId AND sk.satkerpegAktif='Aktif'
    LEFT JOIN pub_satuan_kerja skr ON skr.satkerId=sk.satkerpegSatkerId
    
    LEFT JOIN pub_ref_agama ON agmId=pegAgamaId
    LEFT JOIN sdm_istri_suami ON sutriPegId=pegId
    LEFT JOIN sdm_verifikasi_data ON pegId=verdataValue 
  WHERE
    1=1 AND verdataStatus='3' AND verdataVerifikasiId='19'
    AND (skr.satkerLevel LIKE CONCAT('%s', '.%%') OR skr.satkerId = '%s')
    %filter%
  ORDER BY pktgolrTingkat DESC,pktgolrUrut DESC, pddkThnLulus DESC ) AS temp
WHERE 1=1  %search%

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
