<?php
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
 %s
   GROUP BY pegId
   ";   


$sql['get_data']="
SELECT 
   pegId as 'id',
   pegKodeResmi as 'nip',
   pegNipLama as 'kodeint',
   pegNama as 'nama',
   pegAlamat as 'alamat'
FROM 
   pub_pegawai
%s
ORDER BY 
   pegKodeResmi
LIMIT %s,%s
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
    a.pegId,
    a.pegKodeResmi,
    a.pegKodeGateAccess,
    a.pegNipLama,
    a.pegKodeLain,
    a.pegNama,
    a.pegAlamat,
    a.pegKodePos,
    a.pegNoTelp,
    a.pegNoHp,
    a.pegEmail,
    a.pegSatwilId,
    a.pegTmpLahir,
    a.pegTglLahir,
    a.pegKelamin,                                           
    a.pegJenisIdLain,                                           
    a.pegIdLain,                       
    a.pegAgamaId,                                           
    a.pegStatnikahId,                                      
    a.pegGoldrhId,                                           
    a.pegTinggiBadan,                             
    a.pegBeratBadan,                         
    a.pegCacat,
    a.pegHobby,                                           
    a.pegJnspegrId,                        
    a.pegPnsTmt,                                 
    a.pegTglMasukInstitusi,
    a.pegTglKeluarInstitusi,                               
    a.pegNoTaspen,                               
    a.pegNoAskes,
    a.pegStatusNpwp,                                     
    a.pegNoNpwp,                                
    a.pegUsiaPensiun,                       
    a.pegStatrId, 
    a.pegLevelId,  
    a.pegStatusWargaNeg,                              
    a.pegGelarDepan, 
    a.pegGelarBelakang,                                    
    a.pegFoto,                    
    a.pegKodeAbsen,
    b.satwilLevel,
    b.satwilKode,
    b.satwilNama,
    c.pegrekRekening as `rekening`,
    c.pegrekBankId as `bank`,
    d.bankNama as `bank_label`,
    c.pegrekResipien as `resipien`
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
	pegNama,pegGelarDepan,pegGelarBelakang,pegTmpLahir,pegTglLahir,
	pegIdLain,pegKelamin,pegAgamaId,pegStatnikahId,
	pegAlamat,pegKodePos,pegNoTelp,pegNoHp,
	pegEmail,pegGoldrhId,pegTinggiBadan,pegBeratBadan,
	pegCacat,pegHobby,pegTglMasukInstitusi,pegPnsTmt,
	pegNoTaspen,pegNoAskes,pegStatusNpwp,pegNoNpwp,pegUsiaPensiun,
	pegKodeAbsen,pegJenisIdLain,pegJnspegrId,pegStatrId,
	pegSatwilId,pegLevelId,pegStatusWargaNeg,pegFoto,pegCreationDate,pegUserId,pegIsCalon)
VALUES('%s','%s','%s','%s',
      '%s','%s','%s','%s','%s',
      '%s','%s','%s','%s',
      '%s','%s','%s','%s',
      '%s','%s','%s','%s',
      '%s','%s','%s','%s',
      '%s','%s','%s','%s','%s',
      '%s','%s','%s','%s',
      '%s','%s','%s','%s',now(),'%s',1)
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
	pegNoTaspen = '%s',
	pegNoAskes = '%s',
	pegStatusNpwp = '%s',
	pegNoNpwp = '%s',
	pegUsiaPensiun = '%s',
    pegKodeAbsen = '%s',
    pegJenisIdLain = '%s',
    pegJnspegrId = '%s',
    pegStatrId = '%s',
  pegSatwilId = '%s',
  pegLevelId = '%s',
  pegStatusWargaNeg = '%s',
	pegFoto = '%s',
	pegLastUpdate = now(),
    pegUserId = '%s'
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

?>
