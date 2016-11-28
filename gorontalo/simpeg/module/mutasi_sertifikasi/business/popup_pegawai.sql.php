<?php
//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(DISTINCT pegId) AS TOTAL
FROM 
   pub_pegawai
   INNER JOIN sdm_jabatan_fungsional ON jbtnPegKode=pegId AND jbtnStatus='Aktif'
   INNER JOIN pub_ref_jabatan_fungsional ON jbtnJabfungrId=jabfungrId AND jabfungrJenisrId=7
   LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
   LEFT JOIN sdm_ref_pangkat_golongan ON pktgolPktgolrId=pktgolrId
   
   LEFT JOIN sdm_pendidikan s1 ON s1.pddkPegKode=pegId AND s1.pddkStatusTamat='Selesai' AND s1.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S1')
   LEFT JOIN sdm_pendidikan s2 ON s2.pddkPegKode=pegId AND s2.pddkStatusTamat='Selesai' AND s2.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S2')
   LEFT JOIN sdm_pendidikan s3 ON s3.pddkPegKode=pegId AND s3.pddkStatusTamat='Selesai' AND s3.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S3')
   
   LEFT JOIN sdm_dosen_kepakaran ON dosenPakarPegKode=pegId
   LEFT JOIN sdm_ref_kepakaran ON dosenKepakaranId=kepakaranrId
WHERE (pegKodeResmi LIKE '%s' OR pegNama LIKE '%s') AND pegIsCalon=0
";   

$sql['get_data']="
SELECT 
   pegId AS srtfkdetPegId,
   pegKodeResmi AS srtfkdetNip,
   pegNama AS srtfkdetNama,
   CONCAT(pegGelarDepan,' ',pegNama,' ',pegGelarBelakang) AS srtfkdetNamaGelar,
   pegGelarDepan AS srtfkdetGelarDepan,
   pegGelarBelakang AS srtfkdetGelarBelakang,
   pegTmpLahir AS srtfkdetTempatLahir,
   pegTglLahir AS srtfkdetTanggalLahir,
   pegKelamin AS srtfkdetJenisKelamin,
   pegAlamat AS srtfkdetAlamat,
   CONCAT(pegNoHp,' ', pegEmail) AS srtfkdetKontak,
   jabfungrId AS srtfkdetJabfungrId,
   jabfungrNama AS srtfkdetJabfungrNama,
   pktgolrId AS srtfkdetPktgolrId,
   pktgolrNama AS srtfkdetPktgolrNama,
   kepakaranrKode AS srtfkdetBidangKode,
   kepakaranrNama AS srtfkdetBidangNama,
   IFNULL(CONCAT(s1.pddkJurusan,', ',s1.pddkInstitusi),'') AS srtfkdetS1,
   IFNULL(CONCAT(s2.pddkJurusan,', ',s2.pddkInstitusi),'') AS srtfkdetS2,
   IFNULL(CONCAT(s3.pddkJurusan,', ',s3.pddkInstitusi),'') AS srtfkdetS3
FROM 
   pub_pegawai
   INNER JOIN sdm_jabatan_fungsional ON jbtnPegKode=pegId AND jbtnStatus='Aktif'
   INNER JOIN pub_ref_jabatan_fungsional ON jbtnJabfungrId=jabfungrId AND jabfungrJenisrId=7
   LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
   LEFT JOIN sdm_ref_pangkat_golongan ON pktgolPktgolrId=pktgolrId
   
   LEFT JOIN sdm_pendidikan s1 ON s1.pddkPegKode=pegId AND s1.pddkStatusTamat='Selesai' AND s1.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S1')
   LEFT JOIN sdm_pendidikan s2 ON s2.pddkPegKode=pegId AND s2.pddkStatusTamat='Selesai' AND s2.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S2')
   LEFT JOIN sdm_pendidikan s3 ON s3.pddkPegKode=pegId AND s3.pddkStatusTamat='Selesai' AND s3.pddkTkpddkrId IN (SELECT pendId FROM pub_ref_pendidikan WHERE pendNama='S3')
   
   LEFT JOIN sdm_dosen_kepakaran ON dosenPakarPegKode=pegId
   LEFT JOIN sdm_ref_kepakaran ON dosenKepakaranId=kepakaranrId
WHERE (pegKodeResmi LIKE '%s' OR pegNama LIKE '%s') AND pegIsCalon=0
LIMIT %s,%s
";
 
?>
