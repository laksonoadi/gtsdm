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


$sql['GetListMutasi']="
SELECT DISTINCT 
   bintangtandajasaId as id,
   bintangtandajasaPegKode as nip, 
   bintangtandajasaTandaJasaId as tanda_jasa_id,
   tandajasaNama as tanda_jasa,
   bintangtandajasaTanggal as tanggal,
   bintangtandajasaSertifikatNomor as sk_nomor,
   bintangtandajasaSertifikatTahun as sk_tahun,
   bintangtandajasaPemberi as pemberi,
   bintangtandajasaKeterangan as keterangan,
   bintangtandajasaUpload as upload
FROM
	sdm_bintang_tanda_jasa
	LEFT JOIN sdm_ref_tanda_jasa ON tandajasaId = bintangtandajasaTandaJasaId
WHERE 
   bintangtandajasaPegKode='%s'
"; 

$sql['GetListMutasi_verifikasi']="
SELECT DISTINCT 
   bintangtandajasaId AS id,
   bintangtandajasaPegKode AS nip, 
   bintangtandajasaTandaJasaId AS tanda_jasa_id,
   tandajasaNama AS tanda_jasa,
   bintangtandajasaTanggal AS tanggal,
   bintangtandajasaSertifikatNomor AS sk_nomor,
   bintangtandajasaSertifikatTahun AS sk_tahun,
   bintangtandajasaPemberi AS pemberi,
   bintangtandajasaKeterangan AS keterangan,
   bintangtandajasaUpload AS upload
FROM
	sdm_bintang_tanda_jasa
	LEFT JOIN sdm_ref_tanda_jasa ON tandajasaId = bintangtandajasaTandaJasaId
	INNER JOIN sdm_verifikasi_data vd ON vd.verdataValue = bintangtandajasaId AND vd.`verdataStatus`='3' AND vd.`verdataVerifikasiId`='17'
WHERE 
   bintangtandajasaPegKode='%s'
"; 


$sql['GetDataMutasiById']="
SELECT 
   bintangtandajasaId as id,
   bintangtandajasaPegKode as nip, 
   bintangtandajasaTandaJasaId as tanda_jasa_id,
   tandajasaNama as tanda_jasa,
   bintangtandajasaTanggal as tanggal,
   bintangtandajasaSertifikatNomor as sk_nomor,
   bintangtandajasaSertifikatTahun as sk_tahun,
   bintangtandajasaPemberi as pemberi,
   bintangtandajasaKeterangan as keterangan,
   bintangtandajasaUpload as upload
FROM
	sdm_bintang_tanda_jasa
	LEFT JOIN sdm_ref_tanda_jasa ON tandajasaId = bintangtandajasaTandaJasaId
WHERE 
   bintangtandajasaPegKode='%s' AND
   bintangtandajasaId='%s' 
"; 

$sql['GetComboTandaJasa']="
SELECT
	tandajasaId as id,
	tandajasaNama as name
FROM
	sdm_ref_tanda_jasa 
";


// DO-----------
$sql['do_add'] = "
INSERT INTO sdm_bintang_tanda_jasa SET
	bintangtandajasaPegKode			= '%s',
	bintangtandajasaTandaJasaId		= '%s',
	bintangtandajasaTanggal			= '%s',
	bintangtandajasaSertifikatNomor	= '%s',
	bintangtandajasaSertifikatTahun	= '%s',
	bintangtandajasaPemberi			= '%s',
	bintangtandajasaKeterangan		= '%s',
	bintangtandajasaUpload			= '%s'
";

$sql['do_update'] = "
UPDATE sdm_bintang_tanda_jasa SET 
	bintangtandajasaPegKode			= '%s',
	bintangtandajasaTandaJasaId		= '%s',
	bintangtandajasaTanggal			= '%s',
	bintangtandajasaSertifikatNomor	= '%s',
	bintangtandajasaSertifikatTahun	= '%s',
	bintangtandajasaPemberi			= '%s',
	bintangtandajasaKeterangan		= '%s',
	bintangtandajasaUpload			= '%s'
WHERE 
	bintangtandajasaId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_bintang_tanda_jasa
WHERE 
   bintangtandajasaId = %s  
";

?>
