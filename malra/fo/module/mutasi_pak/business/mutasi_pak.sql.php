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
   COUNT(pktgolId) AS total
FROM 
   sdm_pangkat_golongan
WHERE 
   pegId='%s'
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
	pegId as id,
	pegNama as name,
	pegKodeResmi as kode,
	pegAlamat as alamat,
	pegNoTelp as telp,
	pegSatwilId as wil,
	pegFoto as foto,
	substring(pegTglMasukInstitusi,1,4) as masuk,
	pegKodeGateAccess as no_seri,
	pegTglLahir as tgl_lahir,
	pegKelamin as jenis_kelamin,
	concat(pendNama,' ',pddkJurusan,' ',pddkInstitusi) as pendidikan_tertinggi,
	concat(pktgolrId,' ',pktgolrNama) as pangkat_golongan,
	pktgolTmt as pangkat_golongan_tmt,
	
	jabfungrNama as jabatan_fungsional,
	jabfungrId as diangkat,
	jabfungrNama as diangkat_label,
	jbtnTglMulai as jabatan_fungsional_tmt,
	satkerId as unit_kerja_id,
	satkerNama as unit_kerja
FROM
	pub_pegawai
	LEFT JOIN 
		(select * from (
				select * from 
					sdm_pendidikan 
					INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
				where pddkStatusTamat='Selesai'
				ORDER BY pendPendkelId DESC
		) as b GROUP by pddkPegKode ) as b
		ON pddkPegKode=pegId
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
	LEFT JOIN sdm_jabatan_fungsional ON jbtnPegKode=pegId ANd jbtnStatus='Aktif'
	LEFT JOIN pub_ref_jabatan_fungsional b ON jbtnJabfungrId=b.jabfungrId
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
WHERE pegId='%s' 
"; 

$sql['get_list_mutasi_pak']="
SELECT DISTINCT 
  pakId as id,
	pakNomorPak as nomor,
	pakTanggal as tanggal_penetapan,
	pakPejabatNama as pejabat,
	pakPeriodeAwal as mulai,
	pakPeriodeAkhir as selesai
FROM
	sdm_pak
WHERE 
   pakPegKode='%s'
"; 

$sql['get_data_mutasi_pak_by_id']="
SELECT 
	pakId as id,
	pakNomorPAK as nopak,
	pakPegKode as pegId,
	pakTanggal as tgl_penetapan,
	pakPejabatNama as pejabat,
	pakPeriodeAwal as mulai,
	pakPeriodeAkhir as selesai,
	pakJbtnIdDapatDiangkat as diangkat,
	jabfungrNama as diangkat_label
FROM
	sdm_pak
	LEFT JOIN pub_ref_jabatan_fungsional b ON pakJbtnIdDapatDiangkat=b.jabfungrId
WHERE 
   pakPegKode='%s' AND pakId='%s' 
"; 

$sql['get_data_unsur_penilaian']="
SELECT DISTINCT
  paknId as id,
  pakrefId as idref,
	pakrefNama as nama,
	ifnull(paknAngkaLama,0) as lama,
	ifnull(paknAngkaBaru,0) as baru,
	ifnull(paknJumlahDigunakan,0) as digunakan,
	ifnull(paknJumlahLebihan,0) as lebihan
FROM	
	sdm_ref_pak_penilaian
	INNER JOIN pub_ref_jabatan_fungsional ON jabfungrJenisrId=pakrefJabFungJenisrId
	INNER JOIN sdm_jabatan_fungsional ON jbtnJabfungrId=jabfungrId AND jbtnStatus='Aktif' AND jbtnPegKode='%s'
	LEFT JOIN sdm_pak_penilaian ON paknPakrefKode=pakrefId AND paknPakId='%s'
	LEFT JOIN sdm_pak ON paknPakId=pakId AND pakPegKode=jbtnPegKode
WHERE
	pakrefUnsur='%s' AND pakrefAktif='Ya'
	
"; 

$sql['get_combo_unit_kerja']="
SELECT
	satkerId as id,
	satkerNama as name
FROM
	pub_satuan_kerja 
";

$sql['get_combo_jabatan']="
SELECT
	jabfungrId as id,
	jabfungrNama as name
FROM
	pub_ref_jabatan_fungsional
WHERE
	jabfungrJenisrId IN (
				SELECT 
					jabfungrJenisrId
				FROM
					sdm_jabatan_fungsional 
					INNER JOIN pub_ref_jabatan_fungsional ON jbtnJabfungrId=jabfungrId AND jbtnStatus='Aktif' AND jbtnPegKode='%s'
			     )   
";

$sql['get_id_struk']="
SELECT 
   jabstrukKompgajidtId
FROM 
   sdm_ref_jabatan_struktural
WHERE 
   jabstrukrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO sdm_pak(
    pakPegKode,
    pakNomorPAK,
    pakTanggal,
    pakPejabatNama,
    pakPeriodeAwal,
    pakPeriodeAkhir,
    pakJbtnIdDapatDiangkat
   )
VALUES('%s','%s','%s','%s','%s','%s','%s')  
";

$sql['do_add_unsur'] = "
INSERT INTO sdm_pak_penilaian(
    paknPakId,
    paknPakrefKode,
    paknAngkaLama,
    paknAngkaBaru,
    paknJumlahDigunakan,
    paknJumlahLebihan
   )
VALUES('%s','%s','%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_pak
SET 
	  pakPegKode='%s',
    pakNomorPAK='%s',
    pakTanggal='%s',
    pakPejabatNama='%s',
    pakPeriodeAwal='%s',
    pakPeriodeAkhir='%s',
    pakJbtnIdDapatDiangkat='%s'
WHERE 
	  pakId = '%s'
";  

$sql['do_update_unsur'] = "
UPDATE sdm_pak_penilaian
SET 
	  paknPakId='%s',
    paknPakrefKode='%s',
    paknAngkaLama='%s',
    paknAngkaBaru='%s',
    paknJumlahDigunakan='%s',
    paknJumlahLebihan='%s'
WHERE 
	  paknId = '%s'
";

$sql['do_delete'] = "
DELETE FROM
   sdm_pak
WHERE 
   pakId = %s  
";

$sql['get_max_id']="
select 
	max(pakId) as MAXID
FROM sdm_pak
";

?>
