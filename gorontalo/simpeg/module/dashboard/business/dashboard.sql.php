<?php
$sql['get_count_pegawai_by_user_id'] = "
SELECT 
  COUNT(a.pegId) as total
FROM
  pub_pegawai a
  LEFT JOIN sdm_satuan_kerja_pegawai e ON e.satkerpegPegId=a.pegId AND e.satkerpegAktif='Aktif'
  LEFT JOIN pub_satuan_kerja f ON f.satkerId = e.satkerpegSatkerId
  LEFT JOIN sdm_status_pegawai k ON k.statpegId=a.pegId AND k.statpegAktif='Aktif'
  LEFT JOIN pub_satuan_kerja z ON z.satkerId = %s
WHERE
    (f.satkerId=z.satkerId OR f.satkerLevel LIKE CONCAT(z.satkerLevel, '.%%'))
    AND k.statpegStatrId ='1'
";   

$sql['get_count_all_pegawai'] = '
SELECT
COUNT(a.pegId) AS total
FROM pub_pegawai a
';

$sql['count_all_jabatan_pegawai']="
SELECT
  COUNT(a.jbtnJabstrukrId) AS total
FROM sdm_jabatan_struktural a
JOIN pub_pegawai b ON a.jbtnPegKode = b.pegId
JOIN sdm_satuan_kerja_pegawai c ON c.satkerpegId = b.pegId
JOIN pub_satuan_kerja d ON d.satkerId = c.satkerpegSatkerId
LEFT JOIN pub_satuan_kerja z ON z.satkerId = %s
WHERE a.jbtnStatus = 'Aktif' 
    AND (d.satkerId=z.satkerId OR d.satkerLevel LIKE CONCAT(z.satkerLevel, '.%%'))
";

$sql['get_count_pegawai_by_user_id_verified'] = "
SELECT 
  COUNT(*) as total
FROM
  pub_pegawai a
  LEFT JOIN sdm_pangkat_golongan c ON c.pktgolPegKode=a.pegId AND c.pktgolStatus='Aktif'
  LEFT JOIN sdm_ref_pangkat_golongan d ON d.pktgolrId=c.pktgolPktgolrId
  LEFT JOIN sdm_satuan_kerja_pegawai e ON e.satkerpegPegId=a.pegId AND e.satkerpegAktif='Aktif'
  LEFT JOIN pub_satuan_kerja f ON f.satkerId=e.satkerpegSatkerId
  LEFT JOIN sdm_jabatan_struktural g ON g.jbtnPegKode=a.pegId AND g.jbtnStatus='Aktif'
  LEFT JOIN sdm_ref_jabatan_struktural h ON h.jabstrukrId=g.jbtnJabstrukrId
  LEFT JOIN sdm_jabatan_fungsional i ON i.jbtnPegKode=a.pegId AND i.jbtnStatus='Aktif'
  LEFT JOIN pub_ref_jabatan_fungsional j ON j.jabfungrId=i.jbtnJabfungrId
  LEFT JOIN sdm_pegawai_detail b ON b.pegdtPegId = a.pegId
  LEFT JOIN sdm_status_pegawai k ON k.statpegId=a.pegId AND k.statpegAktif='Aktif'
  LEFT JOIN sdm_ref_status_pegawai l ON l.statrId = k.statpegStatrId
  LEFT JOIN sdm_verifikasi_data ver ON a.pegId=ver.verdataValue 
WHERE
    l.statrId = 1
    AND ver.verdataStatus='3' AND ver.verdataVerifikasiId='19'
    AND (f.satkerLevel LIKE CONCAT('%s', '.%%') OR f.satkerId = '%s')
GROUP BY a.pegId
";

$sql['count_all_jabatan_pegawai_kosong']="
SELECT
  COUNT(a.jabstrukrId) AS `total`
FROM sdm_ref_jabatan_struktural a
LEFT JOIN pub_satuan_kerja b ON a.jabstrukrSatker = b.satkerId
LEFT JOIN pub_satuan_kerja z ON z.satkerId = %s
WHERE 1=1
    AND a.jabstrukrId NOT IN ( SELECT b.jbtnJabstrukrId FROM sdm_jabatan_struktural b WHERE b.jbtnStatus = 'Aktif')
    AND (b.satkerId=z.satkerId OR b.satkerLevel LIKE CONCAT(z.satkerLevel, '.%%'))
";
// $sql['get_data_pegawai_by_user_id']="
// SELECT 
//  pegId as id,
//  pegKodeResmi as nip,
//  pegNipLama as pegKodeInternal,
//  pegNama as name,
//  pegNama as nama,
//  pegKodeResmi as kode,
//  pegAlamat as alamat,
//  pegNoTelp as telp,
//  pegSatwilId as wil,
//  pegFoto as foto,
//  substring(pegTglMasukInstitusi,1,4) as masuk,
//  IF(pktgolStatus='Aktif',CONCAT(pktgolPktgolrId,' - ',pktgolrNama),(select CONCAT(pktgolrId,' - ',pktgolrNama) from sdm_ref_pangkat_golongan where pktgolrId=(select pktgolPktgolrId from sdm_pangkat_golongan where pktgolPegKode=pegId and pktgolStatus='Aktif'))) as pktgol,
//  IF(satkerpegAktif='Aktif',satkerNama,(select satkerNama from pub_satuan_kerja where satkerId=(select satkerpegSatkerId from sdm_satuan_kerja_pegawai where satkerpegPegId=pegId and satkerpegAktif='Aktif'))) as satker,
//  IF(a.jbtnStatus='Aktif',jabstrukrNama,(select jabstrukrNama from sdm_ref_jabatan_struktural where jabstrukrId=(select jbtnJabstrukrId from sdm_jabatan_struktural where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabstruk,
//  IF(b.jbtnStatus='Aktif',jabfungrNama,(select jabfungrNama from pub_ref_jabatan_fungsional where jabfungrId=(select jbtnJabfungrId from sdm_jabatan_fungsional where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabfung,
//  satkerLevel,
//  satkerNama as satker,
//     satkerpegSatkerId as idsatker,
//     satkerLevel as levelSatker,
//     concat(pktgolrId,' ',pktgolrNama) as pangkat,
//     jabstrukrNama as jabatan
// FROM
//  pub_pegawai
//  LEFT JOIN sdm_pegawai_detail ON pegdtPegId = pegId
//  LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
//  LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
//  LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
//  LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
//  LEFT JOIN sdm_jabatan_struktural a ON a.jbtnPegKode=pegId AND a.jbtnStatus='Aktif'
//  LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=a.jbtnJabstrukrId
//  LEFT JOIN sdm_jabatan_fungsional b ON b.jbtnPegKode=pegId AND b.jbtnStatus='Aktif'
//  LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=b.jbtnJabfungrId
// WHERE
//  pegId IS NOT NULL
//  AND (pegKodeResmi LIKE '%s' OR pegNama like '%s')
//     AND (  satkerLevel LIKE CONCAT((SELECT satkerLevel FROM pub_satuan_kerja INNER JOIN gtfw_user_satuan_kerja ON userunitSatuanKerjaId=satkerId WHERE userunitUserId='%s' LIMIT 0,1),'.%%') OR 
//      satkerId LIKE (SELECT satkerId FROM pub_satuan_kerja INNER JOIN gtfw_user_satuan_kerja ON userunitSatuanKerjaId=satkerId WHERE userunitUserId='%s' LIMIT 0,1) OR
//      satkerLevel IS NULL)
//  AND (pegStatrId LIKE IF('%s'='all',CONCAT('%%','%%'),CONCAT('%%','%s','%%')) OR pegStatrId IS NULL)
// GROUP BY 
//   pegId
// ORDER BY
//   IFNULL(satkerNama,'XXXX'), pegKodeResmi ASC 
// LIMIT %s,%s
// "; 

//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   pub_pegawai
 %s
   GROUP BY pegId
   ";   


$sql['get_data_by_id']="
SELECT 
   pegKodeResmi,
   pegNipLama,
   pegNama,
   pegAlamat
FROM 
   pub_pegawai
WHERE 
   pegId = %s
";

$sql['get_data_id']="
SELECT 
   pegId
FROM 
   pub_pegawai
WHERE 
   pegKodeResmi = %s
";

$sql['get_id_struk']="
SELECT 
   jabstrukKompgajidtId
FROM 
   sdm_ref_jabatan_struktural
WHERE 
   jabstrukrId = %s
";

$sql['get_id_fung']="
SELECT 
   jabfungrKompGajiDetId
FROM 
   pub_ref_jabatan_fungsional
WHERE 
   jabfungrId = %s
";

$sql['get_datpeg_detail']="
SELECT
    a.*,
    b.satwilLevel,
    b.satwilKode,
    b.satwilNama,
    c.pegrekRekening as `rekening`,
    c.pegrekBankId as `bank`,
    d.bankNama as `bank_label`,
    c.pegrekResipien as `resipien`,
    a.pegJenFungsional as `pegJenFungsional`
FROM 
   pub_pegawai a
   LEFT JOIN pub_ref_satuan_wilayah b ON a.pegSatwilId = b.satwilId
   LEFT JOIN pub_pegawai_rekening c ON (c.pegrekPegId = a.pegId)
   LEFT JOIN pub_ref_bank d ON (d.bankId = c.pegrekBankId)
WHERE
   a.pegId = '%s'
";

$sql['get_datpeg_detail2']="
SELECT
    a.pegId AS id,
    a.pegKodeResmi AS nip,
    a.pegkodeGateAccess AS kodegateaccess,
    a.pegNipLama AS kodeint,
    a.pegKodeLain AS kodelain,
    a.pegSkck AS skck,
    a.pegKir AS kir,
    a.pegNoKarpeg AS karpeg,
    a.pegNoKpe AS kpe,
    a.pegKelurahan AS kelurahan,
    a.pegKecamatan AS kecamatan,
    a.pegRumah AS rumah,
    a.pegNama AS nama,
    a.pegAlamat AS alamat,
    a.pegKodePos AS kodepos,
    a.pegNoTelp AS telp,
    a.pegNoHp AS hp,
    a.pegEmail AS email,
      b.satwilNama AS satwil,
    a.pegTmpLahir AS tmplahir,
    a.pegTglLahir AS tgllahir,
    a.pegKelamin AS jenkel,                                           
    a.pegJenisIdLain AS jnsidlain,                                           
    a.pegIdLain AS idlain,                     
      c.agmNama AS agama,                               
    a.pegKepercayaan AS keper,                                           
      d.statnkhNama AS nikah,                                      
      e.goldrhNama AS goldar,                                           
    a.pegTinggiBadan AS tinggibdn,                             
    a.pegBeratBadan AS beratbdn,                                
    a.pegWarnaRambut AS warnarmbt,                                
    a.pegWarnaKulit AS warnakul,                              
    a.pegBentukMuka AS bentukmuka,                               
    a.pegCiriKhas AS cirikhas,                              
    a.pegCacat AS cacat,
    a.pegHobby AS hobi,                                           
  f.jnspegrNama AS jnspegid,                        
    a.pegPnsTmt AS pnstmt,                             
    a.pegCpnsTmt AS cpnstmt,                                
    a.pegTglMasukInstitusi AS tglmasuk,
    a.pegTglKeluarInstitusi AS tglkeluar,                          
  g.pendNama AS pnscpns,                                
    a.pegNoTaspen AS notaspen,                               
    a.pegNoAskes AS noaskes,
    a.pegStatusNpwp AS statusnpwp,                                     
    a.pegNoNpwp AS nonpwp,                                
    a.pegUsiaPensiun AS usiapens,                       
  h.statrPegawai AS statr,                               
    a.pegGelarDepan AS geldep,                                  
    a.pegGelarBelakang AS gelbel,                                    
    a.pegFoto AS foto,                    
    a.pegKodeAbsen AS kodeabsen,
    i.pegrekRekening as rekening,
    j.bankNama as bank_label,
    TIMESTAMPDIFF(YEAR, a.pegTglMasukInstitusi, now()) as 'durasi',
    i.pegrekResipien as `resipien`,
    k.levelNama as level,
    a.pegStatusWargaNeg 
FROM 
   pub_pegawai a
    LEFT JOIN pub_ref_satuan_wilayah b ON a.pegSatwilId = b.satwilId
    LEFT JOIN pub_ref_agama c ON a.pegAgamaId = c.agmId
    LEFT JOIN pub_ref_status_nikah d ON a.pegStatnikahId = d.statnkhId
    LEFT JOIN pub_ref_golongan_darah e ON a.pegGoldrhId = e.goldrhId
    LEFT JOIN sdm_ref_jenis_pegawai f ON a.pegJnspegrId = f.jnspegrId
    LEFT JOIN pub_ref_pendidikan g ON a.pegPnsCpnsTkpddkrId = g.pendId
    LEFT JOIN sdm_ref_status_pegawai h ON a.pegStatrId = h.statrId
    LEFT JOIN pub_pegawai_rekening i ON i.pegrekPegId = a.pegId
   LEFT JOIN pub_ref_bank j ON j.bankId = i.pegrekBankId
    LEFT JOIN sdm_ref_level k ON k.levelId = a.pegLevelId
WHERE
   a.pegId = '%s'
";

$sql['get_datpeg_detail3']="
SELECT
  j.kodenikahNama AS kodenikah,
  i.pegdtKategori AS katpeg,
  i.pegdtTipePeg AS tippeg,
  i.pegdtDirSpv AS dirspv,
  k.pegNama AS nama1,
  i.pegdtMor AS mor,
  l.pegNama AS nama2
FROM 
   sdm_pegawai_detail i
   LEFT JOIN sdm_ref_kode_nikah j ON i.pegdtKodenikahId = j.kodenikahId
   LEFT JOIN pub_pegawai k ON k.pegId = i.pegdtDirSpv
   LEFT JOIN pub_pegawai l ON l.pegId = i.pegdtMor
WHERE
   i.pegdtPegId = '%s'
";

$sql['get_datpeg_detail4']="
SELECT
  CONCAT(l.pktgolrId,'-',l.pktgolrNama) AS pktgol
FROM 
  sdm_pangkat_golongan k
LEFT JOIN sdm_ref_pangkat_golongan l ON k.pktgolPktgolrId = l.pktgolrId
WHERE
   k.pktgolPegKode = '%s' and k.pktgolStatus = 'Aktif'
";

$sql['get_datpeg_detail5']="
SELECT
  n.jabstrukrNama AS jabstruk
FROM 
   sdm_jabatan_struktural m
LEFT JOIN sdm_ref_jabatan_struktural n ON m.jbtnJabstrukrId = n.jabstrukrId
WHERE
   m.jbtnPegKode = '%s' and m.jbtnStatus = 'Aktif'
";

$sql['get_datpeg_detail6']="
SELECT
  p.jabfungrNama AS jabfung
FROM 
   sdm_jabatan_fungsional o 
LEFT JOIN pub_ref_jabatan_fungsional p ON o.jbtnJabfungrId = p.jabfungrId
WHERE
   o.jbtnPegKode = '%s' and o.jbtnStatus = 'Aktif'
";

$sql['get_datpeg_detail7']="
SELECT
  b.satkerNama AS satker
FROM 
   sdm_satuan_kerja_pegawai a 
LEFT JOIN pub_satuan_kerja b ON a.satkerpegSatkerId = b.satkerId
WHERE
   a.satkerpegPegId = '%s' and a.satkerpegAktif = 'Aktif'
";

$sql['get_combo_agama']="
SELECT 
   agmId as id,
   agmNama as name
FROM
   pub_ref_agama
ORDER BY agmNama ASC
";

$sql['get_combo_nikah']="
SELECT 
   statnkhId as id,
   statnkhNama as name
FROM
   pub_ref_status_nikah
ORDER BY statnkhNama ASC
";

$sql['get_combo_goldar']="
SELECT 
   goldrhId as id,
   goldrhNama as name
FROM
   pub_ref_golongan_darah
ORDER BY goldrhNama ASC
";

$sql['get_combo_satwil']="
SELECT 
   satwilId as id,
   satwilNama as name
FROM
   pub_ref_satuan_wilayah
ORDER BY satwilKode ASC
";

$sql['get_combo_satwil_kota']="
SELECT 
   satwilId AS id,
   satwilNama AS name
FROM
   pub_ref_satuan_wilayah
   WHERE `satwilLevel` LIKE '1.%' OR `satwilKode` = '9000'
ORDER BY satwilKode ASC
";

$sql['get_combo_jenispeg']="
SELECT 
   jnspegrId as id,
   jnspegrNama as name
FROM
   sdm_ref_jenis_pegawai
ORDER BY jnspegrUrut ASC
";

$sql['get_combo_statpeg']="
SELECT 
   statrId as id,
   statrPegawai as name
FROM
   sdm_ref_status_pegawai
ORDER BY statrPegawai ASC
";

$sql['get_status_kerja_id']="
SELECT 
   statrId as id,
   statrPegawai as name
FROM
   sdm_ref_status_pegawai
WHERE 
  statrId = '%s'
";

$sql['get_combo_level']="
SELECT 
   levelId as id,
   levelNama as name
FROM
   sdm_ref_level
ORDER BY levelNama ASC
";

$sql['get_combo_pnscpns']="
SELECT 
   pendId as id,
   pendNama as name
FROM
   pub_ref_pendidikan
ORDER BY pendNama ASC
";

$sql['get_nip']="
SELECT 
   pegKodeResmi
FROM
   pub_pegawai
WHERE
   pegKodeResmi = '%s'
";

$sql['get_kode_nikah']="
SELECT 
   pegdtPegId
FROM
   sdm_pegawai_detail
WHERE
   pegdtPegId = '%s'
";

$sql['get_peg_gol']="
SELECT 
   a.pktgolPktgolrId as 'id',
   b.pktgolrNama as 'nama'
FROM
   sdm_pangkat_golongan a
   LEFT JOIN sdm_ref_pangkat_golongan b ON b.pktgolrId = a.pktgolPktgolrId
WHERE
   a.pktgolPegKode = '%s' and a.pktgolStatus = 'Aktif'
";

$sql['get_peg_struk']="
SELECT 
   a.jbtnJabstrukrId as 'id',
   b.jabstrukrNama as 'nama'
FROM
   sdm_jabatan_struktural a
   LEFT JOIN sdm_ref_jabatan_struktural b ON b.jabstrukrId = a.jbtnJabstrukrId
WHERE
   a.jbtnPegKode = '%s' and a.jbtnStatus = 'Aktif'
";

$sql['get_peg_fung']="
SELECT 
   a.jbtnJabfungrId as 'id',
   b.jabfungrNama as 'nama'
FROM
   sdm_jabatan_fungsional a
   LEFT JOIN pub_ref_jabatan_fungsional b ON b.jabfungrId = a.jbtnJabfungrId
WHERE
   a.jbtnPegKode = '%s' and a.jbtnStatus = 'Aktif'
";

$sql['get_peg_ker']="
SELECT 
   a.satkerpegSatkerId as 'id',
   b.satkerNama as 'nama'
FROM
   sdm_satuan_kerja_pegawai a
   LEFT JOIN pub_satuan_kerja b ON b.satkerId = a.satkerpegSatkerId
WHERE
   a.satkerpegPegId = '%s' and a.satkerpegAktif = 'Aktif'
";

$sql['get_peg_nikah']="
SELECT 
   a.pegdtKodenikahId as 'id',
   b.kodenikahNama as 'nama',
   a.pegdtKategori as 'katpeg',
   a.pegdtTipePeg as 'tippeg',
   a.pegdtDirSpv as 'dirspv',
   a.pegdtMor as 'mor'
FROM
   sdm_pegawai_detail a
   LEFT JOIN sdm_ref_kode_nikah b ON b.kodenikahId = a.pegdtKodenikahId
WHERE
   a.pegdtPegId = '%s'
";

$sql['get_combo_gol']="
SELECT 
   pktgolrId as id,
   CONCAT(pktgolrId,' - ',pktgolrNama) as name
FROM
   sdm_ref_pangkat_golongan
ORDER BY pktgolrUrut ASC
";

$sql['get_combo_struk']="
SELECT 
   jabstrukrId as id,
   jabstrukrNama as name
FROM
   sdm_ref_jabatan_struktural
ORDER BY jabstrukrNama ASC
";

$sql['get_combo_fung']="
SELECT 
   jabfungrId as id,
   jabfungrNama as name
FROM
   pub_ref_jabatan_fungsional
ORDER BY jabfungrTingkat ASC
";

$sql['get_combo_ker']="
SELECT 
   satkerId as id,
   satkerNama as name
FROM
   pub_satuan_kerja
ORDER BY satkerLevel ASC
";

$sql['get_combo_kode_nikah']="
SELECT 
   kodenikahId as id,
   kodenikahNama as name
FROM
   sdm_ref_kode_nikah
ORDER BY kodenikahNama ASC
";

$sql['get_combo_bahasa']="
SELECT 
   bahasaId as id,
   bahasaNama as name
FROM
   pub_ref_bahasa
ORDER BY bahasaNama ASC
";

$sql['get_data_atas']="
SELECT 
   a.pegNama as 'nama',
   c.satkerNama as 'namaSat'
FROM
   pub_pegawai a
   LEFT JOIN sdm_satuan_kerja_pegawai b ON b.satkerpegPegId = a.pegId
   LEFT JOIN pub_satuan_kerja c ON c.satkerId = b.satkerpegSatkerId
WHERE
   a.pegId = '%s' and b.satkerpegAktif = 'Aktif'
";

$sql['get_satker_and_level']="
SELECT 
a.satkerId,
a.satkerLevel
FROM 
pub_satuan_kerja AS a
INNER JOIN gtfw_user_satuan_kerja ON 
userunitSatuanKerjaId=satkerId 
WHERE userunitUserId='%s' 
";


$sql['get_satker_and_level_multi']="
SELECT 
satkerId,
satkerLevel 
FROM 
gtfw_unit_group 
INNER JOIN gtfw_user_satuan_kerja ON 
userunitSatuanKerjaId=satkerId 
WHERE userunitUserId='%s' LIMIT 0,1 
";

$sql['get_jenis_pegawai_data']="
SELECT 
  COUNT(a.pegId) AS total,
  jen.jnspegrNama AS NAMA,
  a.`pegNama`,
  e.satkerpegAktif
  FROM pub_pegawai a
/*  LEFT JOIN sdm_pangkat_golongan c ON c.pktgolPegKode=a.pegId AND c.pktgolStatus='Aktif'
  LEFT JOIN sdm_ref_pangkat_golongan d ON d.pktgolrId=c.pktgolPktgolrId */
  LEFT JOIN sdm_satuan_kerja_pegawai e ON e.satkerpegPegId=a.pegId AND e.satkerpegAktif='Aktif'
  LEFT JOIN pub_satuan_kerja f ON f.satkerId=e.satkerpegSatkerId
  LEFT JOIN sdm_ref_jenis_pegawai jen ON jen.jnspegrId=a.pegJnspegrId
/*  LEFT JOIN sdm_jabatan_struktural g ON g.jbtnPegKode=a.pegId AND g.jbtnStatus='Aktif'
  LEFT JOIN sdm_ref_jabatan_struktural h ON h.jabstrukrId=g.jbtnJabstrukrId
  LEFT JOIN sdm_jabatan_fungsional i ON i.jbtnPegKode=a.pegId AND i.jbtnStatus='Aktif'
  LEFT JOIN pub_ref_jabatan_fungsional j ON j.jabfungrId=i.jbtnJabfungrId
  LEFT JOIN sdm_pegawai_detail b ON b.pegdtPegId = a.pegId */
  LEFT JOIN sdm_status_pegawai k ON k.statpegId=a.pegId AND k.statpegAktif='Aktif'
/*  LEFT JOIN sdm_ref_status_pegawai l ON l.statrId = k.statpegStatrId */
  LEFT JOIN sdm_verifikasi_data ver ON a.pegId=ver.verdataValue 
WHERE
    ver.verdataStatus='3' AND ver.verdataVerifikasiId='19' AND f.satkerId IN(%s) AND jen.`jnspegrId` = '%s'
"; 

$sql['get_list_jenis_pegawai_data']="
SELECT jnspegrId AS id, jnspegrNama AS NAME FROM sdm_ref_jenis_pegawai WHERE 1=1
";

$sql['get_count_pegawai_pensiun'] = "
SELECT 
  COUNT(*) as total
FROM
  pub_pegawai a
  /*LEFT JOIN sdm_pangkat_golongan c ON c.pktgolPegKode=a.pegId AND c.pktgolStatus='Aktif'
  LEFT JOIN sdm_ref_pangkat_golongan d ON d.pktgolrId=c.pktgolPktgolrId*/
  
  LEFT JOIN sdm_satuan_kerja_pegawai e ON e.satkerpegPegId=a.pegId AND e.satkerpegAktif='Aktif'
  LEFT JOIN pub_satuan_kerja f ON f.satkerId=e.satkerpegSatkerId
  
  /*LEFT JOIN sdm_jabatan_struktural g ON g.jbtnPegKode=a.pegId AND g.jbtnStatus='Aktif'
  LEFT JOIN sdm_ref_jabatan_struktural h ON h.jabstrukrId=g.jbtnJabstrukrId
  LEFT JOIN sdm_jabatan_fungsional i ON i.jbtnPegKode=a.pegId AND i.jbtnStatus='Aktif'
  LEFT JOIN pub_ref_jabatan_fungsional j ON j.jabfungrId=i.jbtnJabfungrId
  LEFT JOIN sdm_pegawai_detail b ON b.pegdtPegId = a.pegId */

  LEFT JOIN sdm_status_pegawai k ON k.statpegId=a.pegId AND k.statpegAktif='Aktif'
  
  /* LEFT JOIN sdm_ref_status_pegawai l ON l.statrId = k.statpegStatrId */
WHERE
    (a.pegKodeResmi LIKE '%s' OR a.pegNama LIKE '%s') AND DATEDIFF(NOW(),a.pegTglLahir) BETWEEN 21715 AND 21900
    AND f.satkerId IN(%s) OR f.satkerId = '%s'
    %s
GROUP BY a.pegId
";   

$sql['get_count_pegawai_masuk_pertahun'] = "
SELECT * FROM
(
SELECT 
  COUNT(*) AS total,
  YEAR(pegTglMasukInstitusi) AS tahun
FROM
  pub_pegawai a

  /* LEFT JOIN sdm_pangkat_golongan c ON c.pktgolPegKode=a.pegId AND c.pktgolStatus='Aktif'
  LEFT JOIN sdm_ref_pangkat_golongan d ON d.pktgolrId=c.pktgolPktgolrId */

  LEFT JOIN sdm_satuan_kerja_pegawai e ON e.satkerpegPegId=a.pegId AND e.satkerpegAktif='Aktif'
  
  /* LEFT JOIN pub_satuan_kerja f ON f.satkerId=e.satkerpegSatkerId
  LEFT JOIN sdm_jabatan_struktural g ON g.jbtnPegKode=a.pegId AND g.jbtnStatus='Aktif'
  LEFT JOIN sdm_ref_jabatan_struktural h ON h.jabstrukrId=g.jbtnJabstrukrId
  LEFT JOIN sdm_jabatan_fungsional i ON i.jbtnPegKode=a.pegId AND i.jbtnStatus='Aktif'
  LEFT JOIN pub_ref_jabatan_fungsional j ON j.jabfungrId=i.jbtnJabfungrId
  LEFT JOIN sdm_pegawai_detail b ON b.pegdtPegId = a.pegId
  LEFT JOIN sdm_status_pegawai k ON k.statpegId=a.pegId AND k.statpegAktif='Aktif'
  LEFT JOIN sdm_ref_status_pegawai l ON l.statrId = k.statpegStatrId */
  WHERE e.satkerpegSatkerId IN(%s)
  GROUP BY tahun ORDER BY tahun DESC LIMIT 0,5
  ) temp
  ORDER BY tahun
  ";

  $sql['get_count_pegawai_fungsional'] = "
SELECT 
  COUNT(*) AS total
FROM
  pub_pegawai a
/*   LEFT JOIN sdm_pangkat_golongan c ON c.pktgolPegKode=a.pegId AND c.pktgolStatus='Aktif'
  LEFT JOIN sdm_ref_pangkat_golongan d ON d.pktgolrId=c.pktgolPktgolrId */

  LEFT JOIN sdm_satuan_kerja_pegawai e ON e.satkerpegPegId=a.pegId AND e.satkerpegAktif='Aktif'

  LEFT JOIN pub_satuan_kerja f ON f.satkerId=e.satkerpegSatkerId
/*  LEFT JOIN sdm_jabatan_struktural g ON g.jbtnPegKode=a.pegId AND g.jbtnStatus='Aktif'
  LEFT JOIN sdm_ref_jabatan_struktural h ON h.jabstrukrId=g.jbtnJabstrukrId */

  LEFT JOIN sdm_jabatan_fungsional i ON i.jbtnPegKode=a.pegId AND i.jbtnStatus='Aktif'
  /* LEFT JOIN pub_ref_jabatan_fungsional j ON j.jabfungrId=i.jbtnJabfungrId */

/*  LEFT JOIN sdm_pegawai_detail b ON b.pegdtPegId = a.pegId */

  LEFT JOIN sdm_status_pegawai k ON k.statpegId=a.pegId AND k.statpegAktif='Aktif'

/*  LEFT JOIN sdm_ref_status_pegawai l ON l.statrId = k.statpegStatrId */
  LEFT JOIN pub_satuan_kerja z ON z.satkerId = %s
WHERE i.jbtnPegKode IS NOT NULL
    AND (f.satkerId=z.satkerId OR f.satkerLevel LIKE CONCAT(z.satkerLevel, '.%%'))
";   


$sql['get_data_pensiun']="
SELECT DISTINCT
  pegKodeResmi as nip
FROM
  pub_pegawai
  INNER JOIN sdm_ref_status_pegawai ON pegstatrId=statrId
  
 /* LEFT JOIN 
    (select * from sdm_pangkat_golongan order by pktgolStatus, pktgolId ASC) as why ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
  LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId

  LEFT JOIN sdm_jabatan_struktural js ON  js.jbtnPegKode=pegId AND js.jbtnStatus='Aktif'
  LEFT JOIN sdm_ref_jabatan_struktural ON js.jbtnJabstrukrId=jabstrukrId

  LEFT JOIN sdm_jabatan_fungsional jf ON jf.jbtnPegKode=pegId ANd jf.jbtnStatus='Aktif'
  LEFT JOIN pub_ref_jabatan_fungsional ON jf.jbtnJabfungrId=jabfungrId */

  LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
  LEFT JOIN pub_satuan_kerja s ON satkerpegSatkerId=s.satkerId
  LEFT JOIN sdm_verifikasi_data ON pegId=verdataValue 
  
  LEFT JOIN pub_satuan_kerja z ON z.satkerId = %s
WHERE
  #statrPegawai='Pensiun'
  1=1 AND verdataStatus='3' AND verdataVerifikasiId='19'
  AND DATEDIFF(DATE_ADD(pegTglLahir, INTERVAL pegUsiaPensiun YEAR),NOW()) BETWEEN 0 AND 180
  AND (s.satkerId=z.satkerId OR s.satkerLevel LIKE CONCAT(z.satkerLevel, '.%%'))
  %unit_kerja%
  %pangkat_golongan%
  %limit%
"; 


$sql['get_count_empty_jabatan']="
SELECT
COUNT(a.jabstrukrId) AS `total` 
FROM
sdm_ref_jabatan_struktural a
LEFT JOIN pub_satuan_kerja b ON a.jabstrukrSatker = b.satkerId 
WHERE 
1=1
 AND a.jabstrukrId NOT IN ( SELECT b.jbtnJabstrukrId FROM sdm_jabatan_struktural b WHERE b.jbtnStatus = 'Aktif')
--search--
--group--

";
?>
