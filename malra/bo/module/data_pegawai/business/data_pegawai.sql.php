<?php

$sql['get_count_pegawai_by_user_id'] = "
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
WHERE
    (a.pegKodeResmi LIKE '%s' OR a.pegNama LIKE '%s')
    "./* AND (f.satkerLevel LIKE CONCAT('%s', '.%%') OR f.satkerId = '%s' OR f.satkerId IS NULL) */"
    --user--
    --status--
GROUP BY 
   a.pegId

";   

$sql['get_data']="
SELECT 
  a.pegId AS id,
  a.pegKodeResmi AS nip,
  a.pegNipLama AS pegKodeInternal,
  a.pegNama AS `NAME`,
  a.pegNama AS nama,
  a.pegKodeResmi AS kode,
  a.pegAlamat AS alamat,
  a.pegAsalDesa AS asaldesa,
  a.pegNoTelp AS telp,
  a.pegSatwilId AS wil,
  a.pegFoto AS foto,
  SUBSTRING(a.pegTglMasukInstitusi,1,4) AS masuk,
  CONCAT(d.pktgolrId,' - ',d.pktgolrNama) AS pktgol,
  a.pegSatKer AS satker,
  a.pegJabStruk AS jabstruk,
  a.pegJabFung AS jabfung,
  a.pegStatHukum AS stat_pegawai,
  a.pegGjBerkala AS gaji_berkala,
  a.pegMasKerThn AS masa_kerja_tahun,
  a.pegMasKerBln AS masa_kerja_bulan
  FROM pub_pegawai a
  LEFT JOIN sdm_pangkat_golongan c ON c.pktgolId=a.pegIdPktGol
  LEFT JOIN sdm_ref_pangkat_golongan d ON d.pktgolrId=c.pktgolPktgolrId
  LEFT JOIN sdm_satuan_kerja_pegawai e ON e.satkerpegId=a.pegIdSatKer
  LEFT JOIN pub_satuan_kerja f ON f.satkerId=e.satkerpegSatkerId
  LEFT JOIN sdm_status_pegawai k ON k.statpegId=a.pegIdStatHukum AND k.statpegAktif='Aktif'
  LEFT JOIN sdm_ref_status_pegawai l ON l.statrId = k.statpegStatrId
  WHERE 1=1
    "./* AND (f.satkerLevel LIKE CONCAT('%s', '.%%') OR f.satkerId = '%s' OR f.satkerId IS NULL) */"
     --status--
GROUP BY 
   a.pegId
ORDER BY
   f.satkerNama, a.pegKodeResmi ASC 
LIMIT %s,%s
"; 

$sql['get_data_pegawai_by_user_id']="
SELECT 
  a.pegId AS id,
  a.pegKodeResmi AS nip,
  a.pegNipLama AS pegKodeInternal,
  a.pegNama AS `NAME`,
  a.pegNama AS nama,
  a.pegKodeResmi AS kode,
  a.pegAlamat AS alamat,
  a.pegAsalDesa AS asaldesa,
  a.pegNoTelp AS telp,
  a.pegSatwilId AS wil,
  a.pegFoto AS foto,
  SUBSTRING(a.pegTglMasukInstitusi,1,4) AS masuk,
  CONCAT(d.pktgolrId,' - ',d.pktgolrNama) AS pktgol,
  "./* f.satkerNama AS satker,
  e.satkerpegSatkerId AS idsatker,
  f.satkerLevel AS levelSatker,
  h.jabstrukrNama AS jabstruk,
  j.jabfungrNama AS jabfung,
  l.statrPegawai AS stat_pegawai */"
  a.pegSatKer AS satker,
  a.pegJabStruk AS jabstruk,
  a.pegJabFung AS jabfung,
  a.pegStatHukum AS stat_pegawai,
  a.pegGjBerkala AS gaji_berkala,
  a.pegMasKerThn AS masa_kerja_tahun,
  a.pegMasKerBln AS masa_kerja_bulan
  FROM pub_pegawai a
  "./* LEFT JOIN sdm_pangkat_golongan c ON c.pktgolPegKode=a.pegId AND c.pktgolStatus='Aktif'
  LEFT JOIN sdm_ref_pangkat_golongan d ON d.pktgolrId=c.pktgolPktgolrId
  LEFT JOIN sdm_satuan_kerja_pegawai e ON e.satkerpegPegId=a.pegId AND e.satkerpegAktif='Aktif'
  LEFT JOIN pub_satuan_kerja f ON f.satkerId=e.satkerpegSatkerId
  LEFT JOIN sdm_jabatan_struktural g ON g.jbtnPegKode=a.pegId AND g.jbtnStatus='Aktif'
  LEFT JOIN sdm_ref_jabatan_struktural h ON h.jabstrukrId=g.jbtnJabstrukrId
  LEFT JOIN sdm_jabatan_fungsional i ON i.jbtnPegKode=a.pegId AND i.jbtnStatus='Aktif'
  LEFT JOIN pub_ref_jabatan_fungsional j ON j.jabfungrId=i.jbtnJabfungrId
  LEFT JOIN sdm_status_pegawai k ON k.statpegId=a.pegId AND k.statpegAktif='Aktif'
  LEFT JOIN sdm_ref_status_pegawai l ON l.statrId = k.statpegStatrId */"
  LEFT JOIN sdm_pangkat_golongan c ON c.pktgolId=a.pegIdPktGol
  LEFT JOIN sdm_ref_pangkat_golongan d ON d.pktgolrId=c.pktgolPktgolrId
  LEFT JOIN sdm_satuan_kerja_pegawai e ON e.satkerpegId=a.pegIdSatKer
  LEFT JOIN pub_satuan_kerja f ON f.satkerId=e.satkerpegSatkerId
  LEFT JOIN sdm_status_pegawai k ON k.statpegId=a.pegIdStatHukum AND k.statpegAktif='Aktif'
  LEFT JOIN sdm_ref_status_pegawai l ON l.statrId = k.statpegStatrId
  WHERE 

    (a.pegKodeResmi LIKE '%s' OR a.pegNama LIKE '%s')
    "./* AND (f.satkerLevel LIKE CONCAT('%s', '.%%') OR f.satkerId = '%s' OR f.satkerId IS NULL) */"
    --user--
  --status--
GROUP BY 
   a.pegId
ORDER BY
   f.satkerNama, a.pegKodeResmi ASC 
LIMIT %s,%s
"; 

// echo $sql['get_data_pegawai_by_user_id'];

$sql['get_data_pegawai_by_user_id_cetak']="
SELECT 
  pegId AS id,
  pegKodeResmi AS nip,
  pegKodeLain AS NIDN,
  pegNipLama AS pegKodeInternal,
  pegKodeGateAccess AS absen,
  pegNama AS nama,
  CONCAT(pegTmpLahir,',',pegTglLahir) AS lahir,
  pegJenisIdLain AS tipe_id,
  pegIdLain AS no_id,
  pegKelamin AS jender,
  pegAsalDesa AS asaldesa,
  agmNama AS agama,
  statnkhNama AS nikah,
  pegAlamat AS alamat,
  satwilNama AS wil,
  pegKodePos AS pos,
  pegNoTelp AS telp,
  pegNoHp AS hp,
  pegEmail AS email,
  goldrhNama AS gol_darah,
  pegTinggiBadan AS tinggi_badan,
  pegBeratBadan AS berat_badan,
  pegCacat AS cacat,
  pegHobby AS hobi,
  pegTglMasukInstitusi AS tgl_masuk,
  pegPnsTmt AS tmt_pns,
  pegCpnsTmt AS tmt_cpns,
  pegdtKategori AS kategori,
  pegdtTipePeg AS tipe_pegawai,
  jnspegrNama AS statpeker,
  statrPegawai AS statpeg,
  bankNama AS bank,
  d.pegrekRekening AS rekening,
  d.pegrekResipien AS nama_rekening,
  pegNoTaspen AS jam_sos,
  pegNoAskes AS jam_kes,
  pegStatusNPWP AS statNPWP,
  pegNoNPWP AS no_NPWP,
  pegTglNPWP AS tgl_NPWP,
  pegUsiaPensiun AS usia_pensiun,
  pegFoto AS foto,
  SUBSTRING(pegTglMasukInstitusi,1,4) AS masuk,
  IF(pktgolStatus='Aktif',CONCAT(pktgolPktgolrId,' - ',pktgolrNama),(SELECT CONCAT(pktgolrId,' - ',pktgolrNama) FROM sdm_ref_pangkat_golongan WHERE pktgolrId=(SELECT pktgolPktgolrId FROM sdm_pangkat_golongan WHERE pktgolPegKode=pegId AND pktgolStatus='Aktif'))) AS pktgol,
  IF(satkerpegAktif='Aktif',satkerNama,(SELECT satkerNama FROM pub_satuan_kerja WHERE satkerId=(SELECT satkerpegSatkerId FROM sdm_satuan_kerja_pegawai WHERE satkerpegPegId=pegId AND satkerpegAktif='Aktif'))) AS satker,
  IF(a.jbtnStatus='Aktif',jabstrukrNama,(SELECT jabstrukrNama FROM sdm_ref_jabatan_struktural WHERE jabstrukrId=(SELECT jbtnJabstrukrId FROM sdm_jabatan_struktural WHERE jbtnPegKode=pegId AND jbtnStatus='Aktif'))) AS jabstruk,
  IF(b.jbtnStatus='Aktif',jabfungrNama,(SELECT jabfungrNama FROM pub_ref_jabatan_fungsional WHERE jabfungrId=(SELECT jbtnJabfungrId FROM sdm_jabatan_fungsional WHERE jbtnPegKode=pegId AND jbtnStatus='Aktif'))) AS jabfung,
  satkerLevel,
  satkerNama AS 'satker',
    satkerpegSatkerId AS 'idsatker',
    satkerLevel AS 'levelSatker',
    CONCAT(pktgolrId,' ',pktgolrNama) AS 'pangkat',
    jabstrukrNama AS 'jabatan'
FROM
  pub_pegawai
  LEFT JOIN sdm_pegawai_detail ON pegdtPegId = pegId
  LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId
  LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
  LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId
  LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
  LEFT JOIN sdm_jabatan_struktural a ON a.jbtnPegKode=pegId
  LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=a.jbtnJabstrukrId
  LEFT JOIN sdm_jabatan_fungsional b ON b.jbtnPegKode=pegId
  LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=b.jbtnJabfungrId
  LEFT JOIN pub_ref_agama ON agmId=pegAgamaId
  LEFT JOIN pub_ref_status_nikah ON pegStatnikahId=statnkhId
  LEFT JOIN pub_ref_satuan_wilayah ON satwilId=pegSatwilId
  LEFT JOIN pub_ref_golongan_darah ON goldrhId=pegGoldrhId
  LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId = jnspegrId
  LEFT JOIN sdm_ref_status_pegawai ON pegStatrId = statrId
  LEFT JOIN pub_pegawai_rekening d ON d.pegrekPegId = pegId
  LEFT JOIN pub_ref_bank  ON bankId = d.pegrekBankId
WHERE
  pegId IS NOT NULL
  AND (pegKodeResmi LIKE '%s' OR pegNama like '%s')
    AND ( satkerLevel LIKE CONCAT((SELECT satkerLevel FROM pub_satuan_kerja INNER JOIN gtfw_user_satuan_kerja ON userunitSatuanKerjaId=satkerId WHERE userunitUserId='%s' LIMIT 0,1),'.%%') OR 
      satkerId LIKE (SELECT satkerId FROM pub_satuan_kerja INNER JOIN gtfw_user_satuan_kerja ON userunitSatuanKerjaId=satkerId WHERE userunitUserId='%s' LIMIT 0,1) OR
      satkerLevel IS NULL)
  AND (pegStatrId LIKE IF('%s'='all',CONCAT('%%','%%'),CONCAT('%%','%s','%%')) OR pegStatrId IS NULL)
GROUP BY 
   pegId
ORDER BY
   IFNULL(satker,'XXXX'), pegKodeResmi ASC
"; 

$sql['is_supervisor'] = "
SELECT 
   *
FROM 
   sdm_pegawai_detail
WHERE
   pegdtDirSpv=%s
";

$sql['get_combo_pegawai_bawahan']="
select distinct
  pegId as id,
  pegNama as name
from 
  pub_pegawai
  INNER JOIN sdm_pegawai_detail ON (pegdtPegId=pegId)
WHERE pegdtDirSpv=%s
";

//===GET===
$sql['get_peg_id_by_username']="
SELECT 
  peguserPegId as pegId
FROM 
  pub_pegawai_user
  INNER JOIN gtfw_user ON (userId=peguserUserId)
WHERE
  userName=%s
";

$sql['get_user_id_by_username']="
SELECT 
  userId as userId
FROM 
  gtfw_user
WHERE
  userName=%s
";

$sql['get_data_pegawai_by_username']="
SELECT 
  *
FROM 
  pub_pegawai_user
  INNER JOIN gtfw_user ON (userId=peguserUserId)
  INNER JOIN pub_pegawai ON (peguserPegId=pegId)
  LEFT JOIN sdm_pegawai_detail ON pegdtPegId=pegId
WHERE
  userName=%s
";

$sql['get_data_pegawai_detail_by_id']="
SELECT 
  *
FROM 
  pub_pegawai_user
  INNER JOIN gtfw_user ON (userId=peguserUserId)
  INNER JOIN pub_pegawai ON (peguserPegId=pegId)
  LEFT JOIN sdm_pegawai_detail ON pegdtPegId=pegId
WHERE
  pegId=%s
";

$sql['get_data_pegawai_detail_by_user_id']="
SELECT 
  *
FROM 
  pub_pegawai_user
  INNER JOIN gtfw_user ON (userId=peguserUserId)
  INNER JOIN pub_pegawai ON (peguserPegId=pegId)
  LEFT JOIN sdm_pegawai_detail ON pegdtPegId=pegId
WHERE
  userId=%s
";

$sql['get_email_by_id']="
SELECT 
  pegEmail as email
FROM 
  pub_pegawai
WHERE
  pegId=%s
";

$sql['get_email_by_nip']="
SELECT 
  pegEmail as email
FROM 
  pub_pegawai
WHERE
  pegKodeResmi='%s'
";

//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   pub_pegawai
WHERE 1=1
 --user--
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
    a.pegTglKir AS tglkir,
    a.pegNoSkbn AS noskbn,
    a.pegTglSkbn AS tglskbn,
    a.pegNoSkkb AS noskkb,
    a.pegTglSkkb AS tglskkb,
    a.pegNoKarpeg AS karpeg,
    a.pegNoKpe AS kpe,
    a.pegAsalDesa AS asaldesa,
    a.pegAsalKecamatan AS asalkecamatan,
    l.satwilNama AS asalsatwil,
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
    a.pegNoAkta AS noakta,
    a.pegAkta AS akta,
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
    a.pegTglNpwp AS tglnpwp,
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
    LEFT JOIN pub_ref_satuan_wilayah l ON a.pegAsalSatwilId = l.satwilId
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
  b.satkerNama AS satker,
  c.nama_ref_jns_peg AS stakepeg
FROM 
   sdm_satuan_kerja_pegawai a 
LEFT JOIN pub_satuan_kerja b ON a.satkerpegSatkerId = b.satkerId
LEFT JOIN sdm_ref_jenis_kepegawaian c ON a.satkerpegJenPegId = c.id_ref_jns_peg
WHERE
   a.satkerpegPegId = %s and a.satkerpegAktif = 'Aktif'
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
ORDER BY satwilNama ASC
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

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   pub_pegawai
   (pegKodeResmi,pegKodeGateAccess,pegNipLama,pegKodeLain,
  pegNama,pegGelarDepan,pegGelarBelakang,pegTmpLahir,pegTglLahir,pegNoAkta,pegAkta,
  pegIdLain,pegKelamin,pegAgamaId,pegStatnikahId,
  pegAlamat,pegKodePos,pegNoTelp,pegNoHp,
  pegEmail,pegGoldrhId,pegTinggiBadan,pegBeratBadan,
  pegCacat,pegHobby,pegTglMasukInstitusi,pegPnsTmt,pegCpnsTmt,
  pegNoTaspen,pegNoAskes,pegStatusNpwp,pegNoNpwp,pegTglNpwp,pegUsiaPensiun,
  pegKodeAbsen,pegJenisIdLain,pegJnspegrId,pegStatrId,
  pegSatwilId,pegAsalDesa,pegAsalKecamatan,pegAsalSatwilId,
  pegLevelId,pegStatusWargaNeg,pegFoto,pegCreationDate,pegUserId
  ,pegSkck,pegKir,pegTglKir,pegNoSkbn,pegTglSkbn,pegNoSkkb,pegTglSkkb,
  pegKelurahan, pegKecamatan, pegRumah
  ,pegNoKarpeg,pegNoKpe,pegJenFungsional
  )
VALUES('%s','%s','%s','%s',
      '%s','%s','%s','%s','%s','%s','%s',
      '%s','%s','%s','%s',
      '%s','%s','%s','%s',
      '%s','%s','%s','%s',
      '%s','%s','%s','%s','%s',
      '%s','%s','%s','%s','%s','%s',
      '%s','%s','%s','%s',
      '%s','%s','%s','%s',
      '%s','%s','%s',now(),'%s'
    ,'%s','%s','%s','%s','%s','%s','%s',
    '%s','%s','%s'
    ,'%s','%s','%s'
    )
";

$sql['do_add_2'] = "
INSERT INTO 
   sdm_ref_master_gaji
   (mstgajiPegId)
VALUES('%s')
";

$sql['do_add_nik'] = "
INSERT INTO 
   sdm_pegawai_detail
   (pegdtPegId,pegdtKodenikahId,pegdtKategori,pegdtTipePeg,pegdtDirSpv,pegdtMor)
VALUES('%s','%s','%s','%s','%s','%s')
";

$sql['do_add_satker'] = "
INSERT INTO 
   sdm_satuan_kerja_pegawai
   (satkerpegPegId,satkerpegSatkerId,satkerpegTmt,satkerpegAktif)
VALUES('%s','%s','%s','Aktif')
";

$sql['do_add_gol'] = "
INSERT INTO 
   sdm_pangkat_golongan
   (pktgolPegKode,pktgolJnspegrId,pktgolPktgolrId,pktgolTmt,pktgolStatus)
VALUES('%s','%s','%s','%s','Aktif')
";

$sql['do_add_struk'] = "
INSERT INTO 
   sdm_jabatan_struktural
   (jbtnPegKode,jbtnJabstrukrId ,jbtnPktGolId,jbtnTglMulai,jbtnStatus)
VALUES('%s','%s','%s','%s','Aktif')
";

$sql['do_add_fung'] = "
INSERT INTO 
   sdm_jabatan_fungsional
   (jbtnPegKode,jbtnJabfungrId,jbtnPktGolId,jbtnTglMulai,jbtnStatus)
VALUES('%s','%s','%s','%s','Aktif')
";

$sql['do_add_komp_gaji'] = "
INSERT INTO 
   sdm_komponen_gaji_pegawai_detail
   (kompgajipegdtPegId,kompgajipegdtKompgajidtrId,kompgajipegdtTanggal)
VALUES('%s','%s','%s')
";

$sql['do_add_data_rek'] = "
   INSERT INTO 
      pub_pegawai_rekening
      (pegrekPegId,pegrekBankId,pegrekRekening,pegrekResipien,pegrekCreationDate,pegrekUserId)
   VALUES 
      (%s, %s, '%s', '%s', now(),  %s)
";

$sql['do_update_nik'] = "
UPDATE 
  sdm_pegawai_detail
SET 
  pegdtKodenikahId = '%s',
  pegdtKategori = '%s',
  pegdtTipePeg = '%s',
  pegdtDirSpv = '%s',
  pegdtMor = '%s'
WHERE 
  pegdtPegId = %s
";

$sql['do_update'] = "
UPDATE pub_pegawai
SET 
  pegKodeResmi = '%s',
  pegKodeGateAccess = '%s',
  pegNipLama = '%s',
  pegKodeLain = '%s',
    pegNama = '%s',
    pegGelarDepan = '%s',
    pegGelarBelakang = '%s',
    pegTmpLahir = '%s',
    pegTglLahir = '%s',
    pegNoAkta = '%s',
    pegAkta = '%s',
  pegIdLain = '%s',
  pegKelamin = '%s',
  pegAgamaId = '%s',
  pegStatnikahId = '%s',
    pegAlamat = '%s',
    pegKodePos = '%s',
    pegNoTelp = '%s',
    pegNoHp = '%s',
  pegEmail = '%s',
  pegGoldrhId = '%s',
  pegTinggiBadan = '%s',
  pegBeratBadan = '%s',
    pegCacat = '%s',
    pegHobby = '%s',
    pegTglMasukInstitusi = '%s',
    pegPnsTmt = '%s',
  pegCpnsTmt = '%s',
  pegNoTaspen = '%s',
  pegNoAskes = '%s',
  pegStatusNpwp = '%s',
  pegNoNpwp = '%s',
  pegTglNpwp = '%s',
  pegUsiaPensiun = '%s',
    pegKodeAbsen = '%s',
    pegJenisIdLain = '%s',
    pegJnspegrId = '%s',
    pegStatrId = '%s',
  pegSatwilId = '%s',
  pegAsalDesa = '%s',
  pegAsalKecamatan = '%s',
  pegAsalSatwilId = '%s',
  pegLevelId = '%s',
  pegStatusWargaNeg = '%s',
  pegFoto = '%s',
  pegLastUpdate = now(),
    pegUserId = '%s',
    pegSkck = '%s',
    pegKir = '%s',
    pegTglKir = '%s',
    pegNoSkbn = '%s',
    pegTglSkbn = '%s',
    pegNoSkkb = '%s',
    pegTglSkkb = '%s',
    pegKelurahan = '%s',
    pegKecamatan = '%s',
    pegRumah = '%s',
    pegNoKarpeg = '%s',
    pegNoKpe = '%s',
    pegJenFungsional = '%s'
WHERE 
  pegId = %s
";  

$sql['do_update_custom'] = "
UPDATE pub_pegawai
SET 
  --fieldset--
WHERE 
  pegId = %s
";

$sql['do_delete'] = "
DELETE FROM
   pub_pegawai
WHERE 
   pegId = %s   
";

$sql['do_delete_2'] = "
DELETE FROM
   sdm_ref_master_gaji
WHERE 
   mstgajiPegId = %s   
";

$sql['do_delete_3'] = "
DELETE FROM
   sdm_pegawai_detail
WHERE 
   pegdtPegId = %s   
";

$sql['do_delete_rekening'] = "
DELETE FROM
   pub_pegawai_rekening
WHERE 
   pegrekPegId = %s   
";

$sql['do_add_rekening'] = "
INSERT INTO pub_pegawai_rekening(pegrekPegId,pegrekBankId,pegrekRekening,pegrekResipien,pegrekCreationDate,pegrekUserId)
VALUES('%s','%s','%s','%s',now(),'%s')  
";

$sql['get_datpeg_bahasa'] = "
SELECT
  bahasaId AS id,
  bahasaNama AS bahasa
FROM sdm_pegawai_bahasa
JOIN pub_ref_bahasa ON pegbahasaBahasaId = bahasaId
WHERE 
   pegbahasaPegId = %s   
";

$sql['do_delete_bahasa'] = "
DELETE FROM
   sdm_pegawai_bahasa
WHERE 
   pegbahasaPegId = %s   
";

$sql['do_add_bahasa'] = "
INSERT INTO sdm_pegawai_bahasa(pegbahasaPegId,pegbahasaBahasaId)
VALUES('%s','%s')  
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

$sql['get_spt_pegawai']="
SELECT DISTINCT
  pubspt_id AS id,
  pubspt_pegId ,
  pubspt_nomor_golongan,
  pubspt_nomor_spt,
  pubspt_sambutan,
  pubspt_tanggal,
  pubspt_panggoltlama,
  pubspt_jabatanlama,
  pubspt_jabatanbaru,
  pubspt_kotattd,
  pubspt_tanggalttd,
  pubspt_satuanttd_id as satker,
  pubspt_panggolttd_id,
  pubspt_nipttd,
  pubspt_namattd,
  pubspt_jabatanttd_id as jabfun,
  pubspt_tembusan4 as tembusan4,
  pubspt_tembusan5 as tembusan5,
  pubspt_tembusan6 as tembusan6,
  pubspt_tembusan7 as tembusan7,
  pubspt_tembusan8 as tembusan8
FROM 
  `pub_pegawai_spt`
  LEFT JOIN  `pub_satuan_kerja` b ON b.`satkerId`= pubspt_satuanttd_id
  LEFT JOIN  `pub_ref_jabatan_fungsional` c ON c.`jabfungrId`= pubspt_jabatanttd_id
WHERE pubspt_pegId= %s
ORDER BY pubspt_id DESC
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
    (a.pegKodeResmi LIKE '%s' OR a.pegNama LIKE '%s') AND ver.verdataStatus='3' AND ver.verdataVerifikasiId='19' %status%
    "./* AND (f.satkerLevel LIKE CONCAT('%s', '.%%') OR f.satkerId = '%s' OR f.satkerId IS NULL) */"
    --user--
GROUP BY a.pegId
";   


$sql['get_data_pegawai_by_user_id_verified']="
SELECT 
  a.pegId AS id,
  a.pegKodeResmi AS nip,
  a.pegNipLama AS pegKodeInternal,
  a.pegNama AS `NAME`,
  a.pegNama AS nama,
  a.pegKodeResmi AS kode,
  a.pegAlamat AS alamat,
  a.pegNoTelp AS telp,
  a.pegSatwilId AS wil,
  a.pegFoto AS foto,
  SUBSTRING(a.pegTglMasukInstitusi,1,4) AS masuk,
  CONCAT(d.pktgolrId,' - ',d.pktgolrNama) AS pktgol,
  f.satkerNama AS satker,
  e.satkerpegSatkerId AS idsatker,
  f.satkerLevel AS levelSatker,
  h.jabstrukrNama AS jabstruk,
  j.jabfungrNama AS jabfung,
  l.statrPegawai AS stat_pegawai
  FROM pub_pegawai a
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

    (a.pegKodeResmi LIKE '%s' OR a.pegNama LIKE '%s') AND ver.verdataStatus='3' AND ver.verdataVerifikasiId='19' %status%
    "./* AND (f.satkerLevel LIKE CONCAT('%s', '.%%') OR f.satkerId = '%s' OR f.satkerId IS NULL) */"
    --user--

LIMIT %s,%s
"; 

$sql['get_count_verified'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   pub_pegawai
 LEFT JOIN sdm_verifikasi_data ON pegId = verdataValue 
   LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
   LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
WHERE 1=1
    %s
   GROUP BY pegId
   ";   


$sql['get_data_verified']="
SELECT 
   pegId as 'id',
   pegKodeResmi as 'nip',
   pegNipLama as 'kodeint',
   pegNama as 'nama',
   pegAlamat as 'alamat',
   IF(pktgolStatus='Aktif',CONCAT(pktgolPktgolrId,' - ',pktgolrNama),(select CONCAT(pktgolrId,' - ',pktgolrNama) from sdm_ref_pangkat_golongan where pktgolrId=(select pktgolPktgolrId from sdm_pangkat_golongan where pktgolPegKode=pegId and pktgolStatus='Aktif'))) as pktgol,
   IF(satkerpegAktif='Aktif',satkerNama,(select satkerNama from pub_satuan_kerja where satkerId=(select satkerpegSatkerId from sdm_satuan_kerja_pegawai where satkerpegPegId=pegId and satkerpegAktif='Aktif'))) as satker,
   IF(a.jbtnStatus='Aktif',jabstrukrNama,(select jabstrukrNama from sdm_ref_jabatan_struktural where jabstrukrId=(select jbtnJabstrukrId from sdm_jabatan_struktural where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabstruk,
   IF(b.jbtnStatus='Aktif',jabfungrNama,(select jabfungrNama from pub_ref_jabatan_fungsional where jabfungrId=(select jbtnJabfungrId from sdm_jabatan_fungsional where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabfung
FROM 
   pub_pegawai
   LEFT JOIN sdm_pegawai_detail ON pegdtPegId = pegId
   LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
   LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
   LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
   LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
   LEFT JOIN sdm_jabatan_struktural a ON a.jbtnPegKode=pegId AND a.jbtnStatus='Aktif'
   LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=a.jbtnJabstrukrId
   LEFT JOIN sdm_jabatan_fungsional b ON b.jbtnPegKode=pegId AND b.jbtnStatus='Aktif'
   LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=b.jbtnJabfungrId
   LEFT JOIN sdm_verifikasi_data ON pegId = verdataValue 
WHERE 1=1
    %s
ORDER BY 
   pegKodeResmi
LIMIT %s,%s
"; 


?>
