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
	substring(pegTglMasukInstitusi,1,4) as masuk
FROM
	pub_pegawai
WHERE pegId='%s' 
"; 


$sql['get_list_mutasi_masa_kerja_penyesuaian']="
SELECT DISTINCT 
	p.mkId as id,
	p.mkPegKode as nip,
	p.mkPenyesuaianTahun as tahun,
	p.mkPenyesuaianBulan as bulan,
	p.mkSkPjb as pejabat,
	p.mkSkNmr as nosk,
	p.mkSkTgl as tgl_sk,
	p.mkSkUpload as upload
FROM
	sdm_masa_kerja_penyesuaian p
WHERE 
   p.mkPegKode='%s'
ORDER BY p.mkSkTgl DESC
"; 

$sql['get_data_mutasi_masa_kerja_penyesuaian_by_id']="
SELECT 
	p.mkId as id,
	p.mkPegKode as nip,
	p.mkPenyesuaianTahun as tahun,
	p.mkPenyesuaianBulan as bulan,
	p.mkSkPjb as pejabat,
	p.mkSkNmr as nosk,
	p.mkSkTgl as tgl_sk,
	p.mkSkUpload as upload
FROM
	sdm_masa_kerja_penyesuaian p
WHERE 
   p.mkPegKode='%s' AND
   p.mkId='%s' 
"; 

/*
mkId
mkPegKode
mkPenyesuaianTahun
mkPenyesuaianBulan
mkSkPjb
mkSkNmr
mkSkTgl
mkSkUpload
*/

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_masa_kerja_penyesuaian
   (mkPegKode,mkPenyesuaianTahun,mkPenyesuaianBulan,mkSkPjb,mkSkNmr,
   mkSkTgl,mkSkUpload)
VALUES('%s','%s','%s','%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_masa_kerja_penyesuaian
SET 
	mkPegKode = '%s',
	mkPenyesuaianTahun = '%s',
	mkPenyesuaianBulan = '%s',
	mkSkPjb = '%s',
	mkSkNmr = '%s',
	mkSkTgl = '%s',
	mkSkUpload = '%s'
WHERE 
	mkId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_masa_kerja_penyesuaian
WHERE 
   mkId = %s  
";


?>
