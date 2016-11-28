<?php
//===GET===
$sql['get_count_pegawai'] = "
SELECT 
   COUNT(pegId) AS total
FROM 
   pub_pegawai
WHERE
   pegKodeResmi like '%s' OR pegNama like '%s'
";

$sql['get_count_mutasi'] = "
SELECT 
   COUNT(satkerpegId) AS total
FROM 
   sdm_satuan_kerja_pegawai
WHERE 
   satkerpegPegId='%s'
";

$sql['get_list_pegawai'] = "
SELECT 
    pegId as id,
	pegNama as nama,
	pegKodeResmi as nip,
	IF(pktgolStatus='Aktif',CONCAT(pktgolPktgolrId,' - ',pktgolrNama),(select CONCAT(pktgolrId,' - ',pktgolrNama) from sdm_ref_pangkat_golongan where pktgolrId=(select pktgolPktgolrId from sdm_pangkat_golongan where pktgolPegKode=pegId and pktgolStatus='Aktif'))) as pktgol,
	IF(satkerpegAktif='Aktif',satkerNama,(select satkerNama from pub_satuan_kerja where satkerId=(select satkerpegSatkerId from sdm_satuan_kerja_pegawai where satkerpegPegId=pegId and satkerpegAktif='Aktif'))) as satker,
	IF(a.jbtnStatus='Aktif',jabstrukrNama,(select jabstrukrNama from sdm_ref_jabatan_struktural where jabstrukrId=(select jbtnJabstrukrId from sdm_jabatan_struktural where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabstruk,
	IF(b.jbtnStatus='Aktif',jabfungrNama,(select jabfungrNama from pub_ref_jabatan_fungsional where jabfungrId=(select jbtnJabfungrId from sdm_jabatan_fungsional where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabfung
FROM 
   pub_pegawai
   LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId
   LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
   LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId
   LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
   LEFT JOIN sdm_jabatan_struktural a ON a.jbtnPegKode=pegId
   LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=a.jbtnJabstrukrId
   LEFT JOIN sdm_jabatan_fungsional b ON b.jbtnPegKode=pegId
   LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=b.jbtnJabfungrId
WHERE
   pegKodeResmi like '%s' OR pegNama like '%s'
GROUP BY 
	 pegId
ORDER BY
	 pegKodeResmi
   LIMIT %s, %s
";

$sql['get_data_pegawai']="
SELECT 
	pegId as id,
	pegNama as name,
	pegKodeResmi as kode,
	pegAlamat as alamat,
	pegNoTelp as telp,
	pegSatwilId as wil,
	pegFoto as foto,
	substring(pegTglMasukInstitusi,1,4) as masuk
FROM
	pub_pegawai
WHERE pegId='%s' 
"; 

$sql['get_list_mutasi_satuan_kerja_pegawai']="
SELECT DISTINCT 
	p.satkerpegId as id,
	p.satkerpegPegId as nip,
	p.satkerpegSatkerId as satker,
	p.satkerpegTmt as tmt,
	p.satkerpegPjbSk as pejabat,
	p.satkerpegNoSk as nosk,
	p.satkerpegTglSk as tgl_sk,
	p.satkerpegAktif as status,
	p.satkerpegSkUpload as upload,
	pg.satkerNama AS satkernama
FROM
	sdm_satuan_kerja_pegawai p
	LEFT JOIN pub_satuan_kerja pg ON (p.satkerpegSatkerId=pg.satkerId)
WHERE 
   p.satkerpegPegId='%s'
ORDER BY p.satkerpegId DESC
"; 

$sql['get_data_mutasi_satuan_kerja_pegawai_by_id']="
SELECT 
	p.satkerpegId as id,
	p.satkerpegPegId as nip,
	p.satkerpegSatkerId as satker,
	p.satkerpegJenPegId as jenpeg,
	p.satkerpegTmt as tmt,
	p.satkerpegPjbSk as pejabat,
	p.satkerpegNoSk as nosk,
	p.satkerpegTglSk as tgl_sk,
	p.satkerpegAktif as status,
	p.satkerpegSkUpload as upload,
	pg.satkerNama AS satkernama
FROM
	sdm_satuan_kerja_pegawai p
	LEFT JOIN pub_satuan_kerja pg ON (p.satkerpegSatkerId=pg.satkerId)
WHERE 
   p.satkerpegPegId='%s' AND
   p.satkerpegId='%s' 
"; 

$sql['get_combo_satuan_kerja']="
SELECT
	satkerId as id,
	satkerNama as name
FROM
	pub_satuan_kerja
ORDER BY
  satkerId ASC
";

$sql["get_combo_tree_satuan_kerja"] = "
SELECT SQL_CALC_FOUND_ROWS
	satkerId as id,
	satkerNama as nama,
	satkerLevel as level,
	satkerParentId as parentId
FROM pub_satuan_kerja
WHERE
   satkerParentId = %d
   --where--
ORDER BY CAST(SUBSTRING_INDEX(satkerLevel, '.', -1) AS SIGNED INT) ASC
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   `pub_pegawai_spt`
   (pubspt_pegId,
   pubspt_nomor_golongan,
   pubspt_nomor_spt,
   pubspt_sambutan,
   pubspt_tanggal,
   pubspt_panggoltlama,
   pubspt_jabatanlama,
   pubspt_jabatanbaru,
   pubspt_kotattd,
   pubspt_tanggalttd,
   pubspt_satuanttd_id,
   pubspt_panggolttd_id,
   pubspt_nipttd,
   pubspt_namattd,
   pubspt_jabatanttd_id,
   pubspt_tembusan4,
   pubspt_tembusan5,
   pubspt_tembusan6,
   pubspt_tembusan7,
   pubspt_tembusan8)
VALUES('%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s')   
";


$sql['do_update'] = "
UPDATE pub_pegawai_spt
SET 
   pubspt_pegId= %s,
   pubspt_nomor_golongan= %s,
   pubspt_nomor_spt= %s,
   pubspt_sambutan= %s,
   pubspt_tanggal= %s,
   pubspt_panggoltlama= %s,
   pubspt_jabatanlama= %s,
   pubspt_jabatanbaru= %s,
   pubspt_kotattd= %s,
   pubspt_tanggalttd= %s,
   pubspt_satuanttd_id= %s,
   pubspt_panggolttd_id= %s,
   pubspt_nipttd= %s,
   pubspt_namattd= %s,
   pubspt_jabatanttd_id= %s,
   pubspt_tembusan4= %s,
   pubspt_tembusan5= %s,
   pubspt_tembusan6= %s,
   pubspt_tembusan7= %s,
   pubspt_tembusan8= %s
WHERE 
	pubspt_pegId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_satuan_kerja_pegawai
WHERE 
   satkerpegId = %s  
";


$sql['update_status']="
update 
	sdm_satuan_kerja_pegawai
set satkerpegAktif = '%s'
where satkerpegId != %s AND satkerpegPegId = '%s'
";

$sql['get_max_status']="
select 
	max(satkerpegId) as MAXID,
	satkerpegAktif as STAT
FROM sdm_satuan_kerja_pegawai
WHERE satkerpegId=(select max(satkerpegId) FROM sdm_satuan_kerja_pegawai)
group by satkerpegId
";


$sql['get_detail_spt']="
select 
  pubspt_id as dataid,
  pubspt_pegId,
   pubspt_nomor_golongan,
   pubspt_nomor_spt,
   pubspt_sambutan,
   pubspt_tanggal,
   pubspt_panggoltlama,
   pubspt_jabatanlama,
   pubspt_jabatanbaru,
   pubspt_kotattd,
   pubspt_tanggalttd,
   pubspt_satuanttd_id,
   pubspt_panggolttd_id,
   pubspt_nipttd,
   pubspt_namattd,
   pubspt_jabatanttd_id,
   pubspt_tembusan4,
   pubspt_tembusan5,
   pubspt_tembusan6,
   pubspt_tembusan7,
   pubspt_tembusan8
FROM pub_pegawai_spt
WHERE pubspt_pegId = %s
ORDER BY pubspt_id DESC
";

$sql['get_max_id']="
select 
	max(satkerpegId) as MAXID
FROM sdm_satuan_kerja_pegawai
";

$sql['get_max_spt_id']="
SELECT 
  MAX(pubspt_id) AS MAXID
FROM `pub_pegawai_spt`
";

$sql['get_combo_jabstruk']="
SELECT
    CONCAT(a.pegKodeResmi,'|',a.pegGelarDepan,'',a.pegNama,'',a.pegGelarBelakang,'|',pktgolrNama,'|',pktgolrNama,'|',rjs.`jabstrukrNama`,'|', pktgolPktgolrId) AS id,
    rjs.`jabstrukrNama` AS name
FROM 
   pub_pegawai a
    LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=a.pegId AND pktgolStatus='Aktif'
    LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
    LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=a.pegId AND satkerpegAktif='Aktif'
    LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
  LEFT JOIN sdm_jabatan_struktural st ON st.`jbtnPegKode` = a.pegId
  LEFT JOIN sdm_ref_jabatan_struktural rjs ON rjs.`jabstrukrId` = st.`jbtnJabstrukrId`
  
WHERE
1=1  AND rjs.jabstrukrNama IS NOT NULL
GROUP BY rjs.jabstrukrNama 
";

$sql['get_combo_jabstruk_default']="
SELECT
    CONCAT(a.pegGelarDepan,'',a.pegNama,'',a.pegGelarBelakang) AS nama
    ,pktgolrNama
    ,rjs.`jabstrukrNama`
    ,pktgolPktgolrId AS id
    ,rjs.`jabstrukrNama` AS jabatan
    ,a.`pegKodeResmi` AS nip
FROM 
   pub_pegawai a
    LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=a.pegId AND pktgolStatus='Aktif'
    LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
    LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=a.pegId AND satkerpegAktif='Aktif'
    LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
  LEFT JOIN sdm_jabatan_struktural st ON st.`jbtnPegKode` = a.pegId
  LEFT JOIN sdm_ref_jabatan_struktural rjs ON rjs.`jabstrukrId` = st.`jbtnJabstrukrId`
  
WHERE
1=1  AND rjs.jabstrukrNama IS NOT NULL AND rjs.jabstrukrNama = 'KEPALA BKD DAN DIKLAT KOTA GORONTALO'
GROUP BY rjs.jabstrukrNama 
";


$sql['get_data_pegawai']="
SELECT
    a.pegId AS id,
    a.pegKodeResmi AS nip,
    a.pegkodeGateAccess AS kodegateaccess,
    a.pegNipLama AS kodeint,
    a.pegKodeLain AS kodelain,
    a.pegNama AS nama,
    a.pegAlamat AS alamat_jalan,
    a.pegKelurahan AS kelurahan,
    a.pegKecamatan AS kecamatan,
    a.pegRumah AS rumah,
    a.pegKab_Kota AS kota,
    a.pegKodePos AS alamat_kodepos,
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
      d.statnkhNama AS status_nikah,                                      
      e.goldrhNama AS golongan_darah,                                           
    a.pegTinggiBadan AS tinggi_badan,                             
    a.pegBeratBadan AS berat_badan,                                
    a.pegWarnaRambut AS rambut,                                
    a.pegWarnaKulit AS warna_kulit,                              
    a.pegBentukMuka AS bentuk_muka,                               
    a.pegCiriKhas AS ciri_khas,                              
    a.pegCacat AS cacat_tubuh,
    a.pegHobby AS hobi,                                           
  f.jnspegrNama AS jnspegid,                        
    a.pegPnsTmt AS pnstmt,                             
    a.pegCpnsTmt AS cpnstmt,                                
    a.pegTglMasukInstitusi AS tglmasuk,
                             
  g.pendNama AS pnscpns,                                
    a.pegNoTaspen AS notaspen,                               
    a.pegNoAskes AS noaskes,
                                      
    a.pegNoNpwp AS nonpwp,                                
    a.pegUsiaPensiun AS usiapens,                       
  h.statrPegawai AS statr,                               
    a.pegGelarDepan AS gelar_depan,                                  
    a.pegGelarBelakang AS gelar_belakang,                                    
    a.pegFoto AS foto,                    
    a.pegKodeAbsen AS kodeabsen,
    i.pegrekRekening as rekening,
    j.bankNama as bank_label,
    TIMESTAMPDIFF(YEAR, a.pegTglMasukInstitusi, now()) as 'durasi',
    i.pegrekResipien as `resipien`,
    k.levelNama as level,
    concat(bb.pendNama,' ',pddkJurusan,' ',pddkInstitusi) as pendidikan_tertinggi,
  	concat(pktgolrId,' ',pktgolrNama) as pangkat_golongan,
  	pktgolTmt as pangkat_golongan_tmt,
  	
  	jabfungrNama as jabatan_fungsional,
  	jabfungrId as diangkat,
  	jabfungrNama as diangkat_label,
  	jbtnTglMulai as jabatan_fungsional_tmt,
  	satkerId as unit_kerja_id,
  	satkerNama as unit_kerja,
	
	(
		SELECT
			GROUP_CONCAT(bahasaNama SEPARATOR '<BR>')
		FROM sdm_pegawai_bahasa
		JOIN pub_ref_bahasa ON pegbahasaBahasaId = bahasaId
		WHERE pegbahasaPegId=a.pegId
		GROUP BY pegbahasaPegId
	) as bahasa
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
    LEFT JOIN 
  		(select * from (
  				select * from 
  					sdm_pendidikan 
  					INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
  				where pddkStatusTamat='Selesai'
  				ORDER BY pendPendkelId DESC
  		) as bb GROUP by pddkPegKode ) as bb
  		ON pddkPegKode=a.pegId
  	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=a.pegId AND pktgolStatus='Aktif'
  	LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
  	LEFT JOIN sdm_jabatan_fungsional ON jbtnPegKode=a.pegId ANd jbtnStatus='Aktif'
  	LEFT JOIN pub_ref_jabatan_fungsional bbb ON jbtnJabfungrId=bbb.jabfungrId
  	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=a.pegId AND satkerpegAktif='Aktif'
  	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
WHERE
   a.pegId = '%s'
GROUP BY a.pegId
"; 


$sql["get_combo_jenis_kepegawaian"] = "
SELECT SQL_CALC_FOUND_ROWS
   id_ref_jns_peg as id,
   nama_ref_jns_peg as nama,
   nama_ref_jns_peg as label,
   nama_ref_jns_peg as `name`
FROM sdm_ref_jenis_kepegawaian
";


$sql["get_unit_spt"] = "
SELECT 
a.pegId AS `id`,
a.pegNama AS `nama`,
a.pegKodeResmi AS `nip`,
e.satkerNama AS `satuan_kerja`,
b.pktgolrNama AS `gol`,
b.pktgolrId AS `id`
FROM 
pub_pegawai a
LEFT JOIN sdm_satuan_kerja_pegawai d ON d.satkerpegPegId = a.pegId
LEFT JOIN pub_satuan_kerja e ON e.satkerId = d.satkerpegSatkerId
LEFT JOIN sdm_pangkat_golongan c ON a.pegId = c.pktgolPegKode
LEFT JOIN sdm_ref_pangkat_golongan b ON c.pktgolPktgolrId = b.pktgolrId
WHERE a.pegId = '%s'
ORDER BY a.pegId DESC
LIMIT 0,1;
";

$sql["get_list_jabatan"] = "
SELECT
a.jbtnId AS `jabId`,
a.jbtnPegKode AS `idpegawai`,
b.jabstrukrId AS `id`,
b.jabstrukrNama AS `jabatan`,
d.satkerNama AS `satuan_kerja`
FROM 
sdm_jabatan_struktural a
LEFT JOIN sdm_ref_jabatan_struktural b ON a.jbtnJabstrukrId = b.jabstrukrId
LEFT JOIN sdm_satuan_kerja_pegawai c ON a.jbtnPegKode = c.satkerpegPegId
LEFT JOIN pub_satuan_kerja d ON c.satkerpegSatkerId = d.satkerId
WHERE a.jbtnPegKode = '%s'
ORDER BY a.jbtnId DESC
LIMIT 0,2
";


$sql['do_add_ketua'] = "
INSERT INTO 
   `pub_pegawai_spt_ketua`
   (pubpeg_sk_1,
   pubpeg_jabat_nama,
   pubpeg_jabat_nip,
   pubpeg_jabat_panggol,
   pubpeg_jabat_jabatan,
   pubpeg_nama,
   pubpeg_nim,
   pubpeg_panggol,
   pubpeg_sk_walkot,
   pubpeg_sk_walkot_tgl,
   pubpeg_jabatan,
   pubpeg_unitkerja,
   pubpeg_tgl_lantik,
   pubpeg_tembusan4,
   pubpeg_tembusan5,
   pubpeg_tembusan6,
   pubpeg_tembusan7,
   pubpeg_sk_2,
   pubpeg_sk_walkot_menduduki,
   pubpeg_sk_walkot_menduduki_tgl,
   pubpeg_eselon,
   pubpeg_tgl_menduduki,
   pubpeg_gaji,
   pubpeg_sk3,
   pubpeg_tgl_tgs,
   pubpeg_tglsurat_1,
   pubpeg_tglsurat_2,
   pubpeg_tglsurat_3,
   pubpeg_idpeg
   )
VALUES(
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s',
'%s'
)   
";

$sql["get_data_spt_ketua"] = "
SELECT 

pubpeg_sk_1,
   pubpeg_jabat_nama,
   pubpeg_jabat_nip,
   pubpeg_jabat_panggol,
   pubpeg_jabat_jabatan,
   pubpeg_nama,
   pubpeg_nim,
   pubpeg_panggol,
   pubpeg_sk_walkot,
   pubpeg_sk_walkot_tgl,
   pubpeg_jabatan,
   pubpeg_unitkerja,
   pubpeg_tgl_lantik,
   pubpeg_tembusan4,
   pubpeg_tembusan5,
   pubpeg_tembusan6,
   pubpeg_tembusan7,
   pubpeg_sk_2,
   pubpeg_sk_walkot_menduduki,
   pubpeg_sk_walkot_menduduki_tgl,
   pubpeg_eselon,
   pubpeg_tgl_menduduki,
   pubpeg_gaji,
   pubpeg_sk3,
   pubpeg_tgl_tgs,
   pubpeg_tglsurat_1,
   pubpeg_tglsurat_2,
   pubpeg_tglsurat_3,
   pubpeg_idpeg
   FROM pub_pegawai_spt_ketua
   WHERE
   pubpeg_idpeg = '%s'
";

$sql['do_update_ketua'] = "
UPDATE pub_pegawai_spt_ketua
SET 
   pubpeg_sk_1= '%s',
   pubpeg_jabat_nama= '%s',
   pubpeg_jabat_nip= '%s',
   pubpeg_jabat_panggol= '%s',
   pubpeg_jabat_jabatan= '%s',
   pubpeg_nama= '%s',
   pubpeg_nim= '%s',
   pubpeg_panggol= '%s',
   pubpeg_sk_walkot= '%s',
   pubpeg_sk_walkot_tgl= '%s',
   pubpeg_jabatan= '%s',
   pubpeg_unitkerja= '%s',
   pubpeg_tgl_lantik= '%s',
   pubpeg_tembusan4= '%s',
   pubpeg_tembusan5= '%s',
   pubpeg_tembusan6= '%s',
   pubpeg_tembusan7= '%s',
   pubpeg_sk_2= '%s',
   pubpeg_sk_walkot_menduduki= '%s',
   pubpeg_sk_walkot_menduduki_tgl= '%s',
   pubpeg_eselon= '%s',
   pubpeg_tgl_menduduki= '%s',
   pubpeg_gaji= '%s',
   pubpeg_sk3= '%s',
   pubpeg_tgl_tgs= '%s',
   pubpeg_tglsurat_1= '%s',
   pubpeg_tglsurat_2= '%s',
   pubpeg_tglsurat_3= '%s'
   WHERE pubpeg_idpeg = %s
  
";

$sql['get_eselon'] = "
SELECT jbtnEselon AS eselon,
jabstrukrNama AS nama
FROM `sdm_jabatan_struktural` 
LEFT JOIN `sdm_ref_jabatan_struktural` ON jabstrukrId=jbtnJabstrukrId
WHERE jbtnPegKode = '%s' AND jbtnStatus = 'Aktif'
";
?>
