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
SELECT pegawai.*,
	IFNULL(MAX(a.nominalDafGaji), 0) as gaji,
	IFNULL(MIN(b.nominalDafGaji), MAX(a.nominalDafGaji)) as next_gaji
FROM (
SELECT tmp.*,
	IF(MIN(pktgolrTingkat)=1 AND MAX(pktgolrTingkat)>=2,
		CAST(IF(mk_bln>=12,mk_thn+1,mk_thn) AS SIGNED)-6,
		IF(MIN(pktgolrTingkat)<=2 AND MAX(pktgolrTingkat)>=3,
			CAST(IF(mk_bln>=12,mk_thn+1,mk_thn) AS SIGNED)-5,
			IF(mk_bln>=12,mk_thn+1,mk_thn)
		)
	) AS masa_kerja_tahun,
	IF(mk_bln>=12,mk_bln MOD 12,mk_bln) AS masa_kerja_bulan
FROM (
SELECT 
	pegId as id,
	pegNama as name,
	pegKodeResmi as kode,
	pegAlamat as alamat,
	pegNoTelp as telp,
	pegSatwilId as wil,
	pegFoto as foto,
	substring(pegTglMasukInstitusi,1,4) as masuk,
	IF(jnspegrNama IN ('PNS','CPNS'),
		pktgolrMasaKerja+FLOOR(DATEDIFF(NOW(),pegCpnsTmt)/365)+IFNULL(mkPenyesuaianTahun,0),
		pktgolrMasaKerja+FLOOR(DATEDIFF(NOW(),pegTglMasukInstitusi)/365)+IFNULL(mkPenyesuaianTahun,0)
	) AS mk_thn,
	IF(jnspegrNama IN ('PNS','CPNS'),
		FLOOR((DATEDIFF(NOW(),pegCpnsTmt) MOD 365)/30)+IFNULL(mkPenyesuaianBulan,0),
		FLOOR((DATEDIFF(NOW(),pegTglMasukInstitusi) MOD 365)/30)+IFNULL(mkPenyesuaianBulan,0)
	) AS mk_bln,
	pktgolrId as pangkat,
	pktgolrTingkat
FROM
	pub_pegawai
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId 
	LEFT JOIN sdm_masa_kerja_penyesuaian ON mkPegKode=pegId
	LEFT JOIN sdm_ref_jenis_pegawai ON pegJnspegrId=jnspegrId
WHERE pegId='%s' 
ORDER BY pktgolTmt DESC
) tmp
GROUP BY id
) pegawai
	LEFT JOIN sdm_ref_daftar_gaji a ON a.pktGolGaji = pangkat AND a.mkDafGaji <= masa_kerja_tahun
	LEFT JOIN sdm_ref_daftar_gaji b ON b.pktGolGaji = pangkat AND b.mkDafGaji > masa_kerja_tahun
ORDER BY a.mkDafGaji DESC, b.mkDafGaji ASC
LIMIT 0, 1
"; 

$sql['get_list_mutasi_kgb']="
SELECT 
	p.kgbId as id,
	p.kgbPegKode as nip,
	p.kgbPktgolId as pktgolid,
	p.kgbGajiPokokBaru as gapok,
	p.kgbThnMasaKerja as masa_thn,
	p.kgbBlnMasaKerja as masa_bln,
	p.kgbBerlakuTanggal as mulai,
	p.kgbTanggalAkanDatang as yad,
	p.kgbPejabatPenetap as pejabat,
	p.kgbTanggalPenetap as tgl_sk,
	p.kgbNomorPenetap as nosk,
	p.kgbAktif as status,
	p.kgbSkUpload as upload,
   CONCAT(pg.pktgolrId,' - ',pg.pktgolrNama) AS pktgollabel
FROM
	sdm_kenaikan_gaji_berkala p
	LEFT JOIN sdm_ref_pangkat_golongan pg ON (p.kgbPktgolId=pg.pktgolrId)
WHERE 
   p.kgbPegKode='%s'
ORDER BY p.kgbBerlakuTanggal DESC, p.kgbId DESC 
"; 

$sql['get_data_mutasi_kgb_by_id']="
SELECT 
	p.kgbId as id,
	p.kgbPegKode as nip,
	p.kgbPktgolId as pktgolid,
	p.kgbGajiPokokBaru as gapok,
	p.kgbThnMasaKerja as masa_thn,
	p.kgbBlnMasaKerja as masa_bln,
	p.kgbBerlakuTanggal as mulai,
	p.kgbTanggalAkanDatang as yad,
	p.kgbPejabatPenetap as pejabat,
	p.kgbTanggalPenetap as tgl_sk,
	p.kgbNomorPenetap as nosk,
	p.kgbAktif as status,
	p.kgbSkUpload as upload,
	CONCAT(pg.pktgolrId,' - ',pg.pktgolrNama) AS pktgollabel
FROM
	sdm_kenaikan_gaji_berkala p
	LEFT JOIN sdm_ref_pangkat_golongan pg ON (p.kgbPktgolId=pg.pktgolrId)
WHERE 
   p.kgbPegKode='%s' AND
   p.kgbId='%s' 
"; 

$sql['get_combo_golongan']="
SELECT
	pktgolrId as id,
	CONCAT(pktgolrId,' - ',pktgolrNama) as name
FROM
	sdm_ref_pangkat_golongan p,
	sdm_pangkat_golongan pg
WHERE 
   p.pktgolrId=pg.pktgolPktgolrId AND 
   pg.pktgolPegKode='%s' AND
   pg.pktgolStatus='Aktif'

";

$sql['get_combo_golongan_all']="
SELECT
	pktgolrId as id,
	CONCAT(pktgolrId,' - ',pktgolrNama) as name
FROM
	sdm_ref_pangkat_golongan
/* WHERE
  pktgolrId = '%s' */
";

$sql['get_id_komp']="
SELECT 
	a.gapokKompGajiDetId as 'id',
	b.kompgajidtNominal as 'nominal'
FROM
	sdm_ref_gaji_pokok a
	LEFT JOIN sdm_ref_komponen_gaji_detail b ON (b.kompgajidtId=a.gapokKompGajiDetId)
WHERE 
   a.gapokPktgolrId='%s' AND
   a.gapokMasaKerja='%s' 
";

$sql['get_gaji_by_pkt_mk']="
SELECT MAX(nominalDafGaji) as gaji
FROM
	sdm_ref_daftar_gaji
WHERE 1=1
    AND pktGolGaji = '%s'
    AND mkDafGaji <= '%s'
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_kenaikan_gaji_berkala
   (kgbPegKode,kgbPktgolId,kgbGajiPokokBaru,kgbThnMasaKerja,kgbBlnMasaKerja,kgbBerlakuTanggal,
   kgbTanggalAkanDatang,kgbPejabatPenetap,kgbTanggalPenetap,kgbNomorPenetap,kgbAktif,kgbSkUpload)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_kenaikan_gaji_berkala
SET 
	kgbPegKode = '%s',
	kgbPktgolId = '%s',
	kgbGajiPokokBaru = '%s',
	kgbThnMasaKerja = '%s',
	kgbBlnMasaKerja = '%s',
	kgbBerlakuTanggal = '%s',
	kgbTanggalAkanDatang = '%s',
	kgbPejabatPenetap = '%s',
	kgbTanggalPenetap = '%s',
	kgbNomorPenetap = '%s',
	kgbAktif = '%s',
	kgbSkUpload = '%s'
WHERE 
	kgbId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_kenaikan_gaji_berkala
WHERE 
   kgbId = %s  
";


$sql['update_status']="
update 
	sdm_kenaikan_gaji_berkala
set kgbAktif = '%s'
where kgbId != %s AND kgbPegKode = '%s' 
";

$sql['get_max_status']="
select 
	max(kgbId) as MAXID,
	kgbAktif as STAT
FROM sdm_kenaikan_gaji_berkala
WHERE kgbId=(select max(kgbId) FROM sdm_jabatan_struktural)
group by kgbId
";

$sql['get_max_id']="
select 
	max(kgbId) as MAXID
FROM sdm_kenaikan_gaji_berkala
";

$sql['get_id_lain']="
select 
	a.kgbId as 'id',
	b.gapokKompGajiDetId as 'komp'
FROM 
  sdm_kenaikan_gaji_berkala a
  LEFT JOIN sdm_ref_gaji_pokok b ON (b.gapokPktgolrId=a.kgbPktgolId)
WHERE 
  b.gapokMasaKerja = a.kgbThnMasaKerja AND
  a.kgbId != %s AND a.kgbPegKode = '%s'
";

$sql['do_add_komp_mutasi'] = "
INSERT INTO 
   sdm_komponen_gaji_pegawai_detail
   (kompgajipegdtPegId,kompgajipegdtKompgajidtrId,kompgajipegdtTanggal)
VALUES('%s','%s','%s')
";

$sql['do_update_komp_mutasi'] = "
UPDATE sdm_komponen_gaji_pegawai_detail
SET 
	kompgajipegdtKompgajidtrId = '%s',
	kompgajipegdtTanggal = '%s'
WHERE 
	kompgajipegdtPegId = %s and kompgajipegdtKompgajidtrId = %s
";

$sql['do_delete_komp_mutasi'] = "
DELETE FROM
   sdm_komponen_gaji_pegawai_detail
WHERE 
   kompgajipegdtPegId = %s and kompgajipegdtKompgajidtrId = %s  
";

$sql['get_last_mutasi_kgb_pegawai']="
SELECT
a.`kgbId` AS `id`,
a.`kgbPegKode` AS `pegKode`,
a.`kgbGajiPokokBaru` AS `gajiPokok`,
a.`kgbBerlakuTanggal` AS `tmt`
FROM sdm_kenaikan_gaji_berkala a
WHERE 
a.`kgbPegKode` = '%s'
ORDER BY a.kgbBerlakuTanggal DESC, a.kgbId DESC
";

?>
