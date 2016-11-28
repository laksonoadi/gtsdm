<?php


//===GET===

$sql['get_data_duk_by_id']="
SELECT 
id,nama,nip,jenis_kelamin,jenis_pegawai,golongan, gol_nama, golongan_tmt,jabatan,jabatan_tmt_mulai,jabatan_tmt_selesai, capeg_tmt, 
IF(minimal=1 AND maksimal>=2, 
IF(masa_kerja_bulan>=12,masa_kerja_tahun+1,masa_kerja_tahun)-6, 
IF(minimal<=2 AND maksimal>=3, 
IF(masa_kerja_bulan>=12,masa_kerja_tahun+1,masa_kerja_tahun)-5, 
IF(masa_kerja_bulan>=12,masa_kerja_tahun+1,masa_kerja_tahun) ) ) AS masa_kerja_tahun, 
IF(masa_kerja_bulan>=12,masa_kerja_bulan MOD 12,masa_kerja_bulan) AS masa_kerja_bulan, 
latihan_tahun,latihan_nama,latihan_jam, pendidikan_nama,pendidikan_jurusan,pendidikan_lulus, pendidikan_tingkat, tempat_lahir, tanggal_lahir,unit_kerja,sub_unit_kerja 
FROM 
(SELECT pegId AS id, pegCpnsTmt AS capeg_tmt, CONCAT(IFNULL(pegGelarDepan,''),IF(IFNULL(pegGelarDepan,'')='','',' '),
  pegNama,IF(IFNULL(pegGelarBelakang,'')='','',', '),IFNULL(pegGelarBelakang,'')) AS nama, 
  pegKodeResmi AS nip, pegKelamin AS jenis_kelamin, jnspegrNama AS jenis_pegawai, 
  pktgolPktgolrId AS golongan, pktgolrNama AS gol_nama, pktgolTmt AS golongan_tmt, 
  jabstrukrNama AS jabatan, 
  jbtnTglMulai AS jabatan_tmt_mulai, 
  jbtnTglSelesai AS jabatan_tmt_selesai, IF(jnspegrNama IN ('PNS','CPNS'), 
  pktgolrMasaKerja+FLOOR(DATEDIFF(NOW(),pegCpnsTmt)/365)+IFNULL(mkPenyesuaianTahun,0), 
  pktgolrMasaKerja+FLOOR(DATEDIFF(NOW(),pegTglMasukInstitusi)/365)+IFNULL(mkPenyesuaianTahun,0) )AS masa_kerja_tahun, 
  IF(jnspegrNama IN ('PNS','CPNS'), FLOOR((DATEDIFF(NOW(),pegCpnsTmt) MOD 365)/30)+IFNULL(mkPenyesuaianBulan,0), 
  FLOOR((DATEDIFF(NOW(),pegTglMasukInstitusi) MOD 365)/30)+IFNULL(mkPenyesuaianBulan,0) )AS masa_kerja_bulan, 
  GROUP_CONCAT(YEAR(pelTglMulai)) AS latihan_tahun, 
  GROUP_CONCAT(pelNama) AS latihan_nama, 
  GROUP_CONCAT(pelJmlJam) AS latihan_jam, 
  
  pddkInstitusi AS pendidikan_nama, pddkJurusan AS pendidikan_jurusan, 
  pddkThnLulus AS pendidikan_lulus, pendNama AS pendidikan_tingkat, pegTmpLahir AS tempat_lahir, pegTglLahir AS tanggal_lahir, 
  unit.satkerNama AS unit_kerja, subunit.satkerNama AS sub_unit_kerja, MIN(pktgolrTingkat) AS minimal, 
  MAX(pktgolrTingkat) AS maksimal FROM pub_pegawai LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId 
  LEFT JOIN (SELECT * FROM sdm_pangkat_golongan ORDER BY pktgolStatus ASC, pktgolId DESC) AS why 
  ON pktgolPegKode=pegId AND pktgolStatus='Aktif' 
  LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId 
  LEFT JOIN sdm_jabatan_struktural ON jbtnPegKode=pegId AND jbtnStatus='Aktif' 
  LEFT JOIN sdm_ref_jabatan_struktural ON jbtnJabstrukrId=jabstrukrId 
  LEFT JOIN sdm_pelatihan a ON a.pelPegKode=pub_pegawai.pegId 
  LEFT JOIN sdm_ref_jenis_pelatihan ON pelJnspelrId=jnspelrId 
  LEFT JOIN (SELECT * FROM ( SELECT * FROM sdm_pendidikan 
        INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId 
        WHERE pddkStatusTamat='Selesai' 
        ORDER BY pendPendkelId DESC ) AS b GROUP BY pddkPegKode ) AS b ON pddkPegKode=pegId 
        LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif' 
        LEFT JOIN pub_satuan_kerja subunit ON subunit.satkerId=satkerpegSatkerId 
        LEFT JOIN pub_satuan_kerja unit ON unit.satkerId=subunit.satkerParentId 
        LEFT JOIN sdm_masa_kerja_penyesuaian ON mkPegKode=pegId 
        WHERE 
        pegId<>-1 
    %tampilkan%
  GROUP BY pegId
  ORDER BY pktgolrTingkat DESC,pktgolrUrut DESC ) as temp
%limit%
";

$sql['get_data_jab_by_duk_by_id']="
SELECT  a.jbtnPegKode AS id, 
  a.jbtnJabstrukrId AS jbtn_struktural_id,
  a.jbtnEselon AS eselon,
  a.jbtnPktGolId AS golongan,
  a.jbtnTglMulai AS tmt_mulai,
  a.jbtnTglSelesai AS tmt_selesai,
  a.jbtnSkPjb AS ttd_sk,
  a.jbtnSkNmr AS no_sk_jbtn,
  a.jbtnSkTgl AS tgl_sk_jbtn,
  c.jabstrukrNama AS jbtn_nama,
  e.satkerNama AS unit_kerja
FROM sdm_jabatan_struktural a

JOIN pub_pegawai b ON a.jbtnPegKode = b.pegId
JOIN sdm_satuan_kerja_pegawai d ON d.satkerpegPegId = b.pegId  
JOIN pub_satuan_kerja e ON e.satkerId = d.satkerpegSatkerId 
JOIN sdm_ref_jabatan_struktural c ON a.jbtnJabstrukrId= c.jabstrukrId
WHERE 1=1 %tampilkan% 
";

$sql['get_count_data_absen']="
SELECT COUNT(pegId) as total FROM  pub_pegawai;
";

$sql['get_data_absen']="
SELECT
  a.pegId AS id,
  a.pegNama AS nama,
  a.pegKodeResmi AS nip,
  GROUP_CONCAT(c.pktgolrNama SEPARATOR ', ') as golongan,
  IF(d.jbtnId IS NULL, g.jabfungrNama, e.jabstrukrNama) as jabatan
FROM pub_pegawai a
LEFT JOIN sdm_pangkat_golongan b ON a.pegId = b.pktgolPegKode
LEFT JOIN sdm_ref_pangkat_golongan c ON b.pktgolPktgolrId = c.pktgolrId

LEFT JOIN sdm_jabatan_struktural d ON a.pegId = d.jbtnPegKode
LEFT JOIN sdm_ref_jabatan_struktural e ON d.jbtnJabstrukrId = e.jabstrukrId

LEFT JOIN sdm_jabatan_fungsional f ON a.pegId = f.jbtnPegKode
LEFT JOIN pub_ref_jabatan_fungsional g ON f.jbtnJabfungrId = g.jabfungrId
WHERE 1 = 1
%tampilkan% 
GROUP BY a.pegId
%limit%
";
?>
