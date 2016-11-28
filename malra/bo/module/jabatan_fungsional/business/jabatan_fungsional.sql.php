<?php


//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(jabfungrId) AS total
FROM 
   pub_ref_jabatan_fungsional
WHERE 
	jabfungrNama LIKE %s
   ";   


$sql['get_data']="
SELECT DISTINCT
   a.jabfungrId as id,
   a.jabfungrNama as nama,
   a.jabfungrNamaEformasi as nama_eformasi,
   a.jabfungrParentId AS `jfparent`,
(SELECT jabfungrNama FROM pub_ref_jabatan_fungsional WHERE jabfungrId = a.jabfungrParentId)  AS `parentName`,
   a.jabfungrJenisrId as jenisid,
   a.jabfungrTingkat as tingkat,
   a.jabfungrBatasUsiaPensiun as pensiun,
   a.jabfungrKompGajiDetId as gajiid,
   a.jabfungrKompGajiDetSksId as sksid,
   a.jabfungMaxSks as max_sks,
   b.jabfungJenis as jenis,
   c.kompgajidtId as gajiId,
   c.kompgajidtKode as gajiKode,
   c.kompgajidtNama as gajiNama,
   d.kompgajidtId as sksId,
   d.kompgajidtKode as sksKode,
   d.kompgajidtNama as sksNama,
   e.kompgajiId as gkompId,
   e.kompgajiNama as gkomNama,
   e.kompgajiKode as gkompKode,
   f.kompgajiId as skompId,
   f.kompgajiNama as skomNama,
   f.kompgajiKode as skompKode
FROM 
   pub_ref_jabatan_fungsional a
LEFT JOIN pub_ref_jabatan_fungsional_jenis b ON a.jabfungrJenisrId=b.jabfungjenisrId
LEFT JOIN sdm_ref_komponen_gaji_detail c ON a.jabfungrKompGajiDetId=c.kompgajidtId 
LEFT JOIN sdm_ref_komponen_gaji_detail d ON a.jabfungrKompGajiDetSksId=d.kompgajidtId 
LEFT JOIN sdm_ref_komponen_gaji e ON c.kompgajidtKompgajiId = e.kompgajiId
LEFT JOIN sdm_ref_komponen_gaji f ON d.kompgajidtKompgajiId = f.kompgajiId

";
//kompgajiId as id,
$sql['get_komponen_gaji']="
SELECT 
	kompgajiId as id,
	kompgajiKode as kode,
	kompgajiNama as name,
	
	kompgajiKeterangan as ket,
	kompgajiJenis as jenis
FROM
	sdm_ref_komponen_gaji
";

//kompgajidtId as id,
$sql['get_gaji_detail']="
SELECT 
	kompgajidtId as id,
	kompgajidtKode as kode,
	concat(kompgajidtKode,'-',kompgajidtNama) as name,
	kompgajidtKompgajiId as gaji,
	
	kompgajidtStatusSeting as seting,
	kompgajidtNominal as nominal,
	kompgajidtPersen as persen,
	kompgajidtTanggalBerlaku as tgl
FROM
	sdm_ref_komponen_gaji_detail
WHERE
	kompgajidtId IS NOT NULL
";


$sql['get_jenis_jabatan']="
SELECT
	jabfungjenisrId as id,
	jabfungJenis as name
FROM
	pub_ref_jabatan_fungsional_jenis
";
// DO-----------
$sql['do_add'] = "
INSERT INTO 
   pub_ref_jabatan_fungsional
   (jabfungrNama,jabfungrNamaEformasi,jabfungrJenisrId,jabfungrTingkat,jabfungrParentId,
   jabfungrBatasUsiaPensiun,jabfungrKompGajiDetId,jabfungMaxSks)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE pub_ref_jabatan_fungsional
SET 
	jabfungrNama = '%s',
	jabfungrNamaEformasi = '%s',
	jabfungrJenisrId = '%s',
	jabfungrTingkat = '%s',
   jabfungrParentId = '%s',
	jabfungrBatasUsiaPensiun = '%s',
	jabfungrKompGajiDetId = '%s',
	jabfungMaxSks = '%s'
WHERE 
	jabfungrId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   pub_ref_jabatan_fungsional
WHERE 
   jabfungrId = %s   
";

$sql['combo_jabatan_fungsional'] = "
SELECT
   a.jabfungrId as id,
   a.jabfungrNama as name
FROM 
   pub_ref_jabatan_fungsional a
";
?>
