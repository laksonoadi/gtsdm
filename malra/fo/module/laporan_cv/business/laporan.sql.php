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

$sql['get_list_pegawai'] = "
SELECT 
    pegId as id,
	 pegNama as nama,
	 pegKodeResmi as nip
FROM 
   pub_pegawai
WHERE
   pegKodeResmi like '%s' OR pegNama like '%s'
ORDER BY
	 pegKodeResmi
   LIMIT %s, %s
";
  
$sql['get_data_pegawai']="
SELECT
    a.pegId AS id,
    a.pegKodeResmi AS nip,
    a.pegkodeGateAccess AS kodegateaccess,
    a.pegKodeInternal AS kodeint,
    a.pegKodeLain AS kodelain,
    a.pegNama AS nama,
    a.pegAlamat AS alamat_jalan,
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
  	satkerNama as unit_kerja
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

?>
