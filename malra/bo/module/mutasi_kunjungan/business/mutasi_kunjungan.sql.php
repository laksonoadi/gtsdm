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


$sql['get_list_mutasi_kunjungan']="
SELECT DISTINCT 
   p.klnId as id,
   p.klnPegKode as nip, 
   p.klnJnsklnrId as jkid,
   p.klnTujuan as tujuan,
   p.klnNegId as negid,
   p.klnTglMulai as mulai,
   p.klnTglSelesai as selesai,
   p.klnAsldnrId as asdanid,
   p.klnKeterangan as ket,
   j.jnsklnrNama as jklabel,
   n.satwilNama as neglabel,
   a.asldnrNama as asdanlabel,
   p.klnUpload as upload
FROM
	sdm_kunjungan_ln p
	LEFT JOIN pub_ref_satuan_wilayah n ON (n.satwilId=p.klnNegId)
	LEFT JOIN sdm_ref_asal_dana a ON (a.asldnrId=p.klnAsldnrId)
	LEFT JOIN sdm_ref_jenis_kunjungan_ln j ON (j.jnsklnrId=p.klnJnsklnrId)
WHERE 
   p.klnPegKode='%s'
ORDER BY p.klnTglMulai DESC
"; 

$sql['get_list_mutasi_kunjungan_verifikasi']="
SELECT DISTINCT 
   p.klnId as id,
   p.klnPegKode as nip, 
   p.klnJnsklnrId as jkid,
   p.klnTujuan as tujuan,
   p.klnNegId as negid,
   p.klnTglMulai as mulai,
   p.klnTglSelesai as selesai,
   p.klnAsldnrId as asdanid,
   p.klnKeterangan as ket,
   j.jnsklnrNama as jklabel,
   n.satwilNama as neglabel,
   a.asldnrNama as asdanlabel,
   p.klnUpload as upload
FROM
	sdm_kunjungan_ln p
	LEFT JOIN pub_ref_satuan_wilayah n ON (n.satwilId=p.klnNegId)
	LEFT JOIN sdm_ref_asal_dana a ON (a.asldnrId=p.klnAsldnrId)
	LEFT JOIN sdm_ref_jenis_kunjungan_ln j ON (j.jnsklnrId=p.klnJnsklnrId)
    INNER JOIN sdm_verifikasi_data vd ON vd.`verdataValue`=p.`klnId` AND vd.`verdataStatus`='3' AND vd.`verdataVerifikasiId`='18'
WHERE 
   p.klnPegKode='%s'
ORDER BY p.klnTglMulai DESC
"; 

$sql['get_data_mutasi_kunjungan_by_id']="
SELECT 
	p.klnId as id,
   p.klnPegKode as nip, 
   p.klnJnsklnrId as jkid,
   p.klnTujuan as tujuan,
   p.klnNegId as negid,
   p.klnTglMulai as mulai,
   p.klnTglSelesai as selesai,
   p.klnAsldnrId as asdanid,
   p.klnKeterangan as ket,
   j.jnsklnrNama as jklabel,
   n.satwilNama as neglabel,
   a.asldnrNama as asdanlabel,
   p.klnUpload as upload
FROM
	sdm_kunjungan_ln p
	LEFT JOIN pub_ref_satuan_wilayah n ON (n.satwilId=p.klnNegId)
	LEFT JOIN sdm_ref_asal_dana a ON (a.asldnrId=p.klnAsldnrId)
	LEFT JOIN sdm_ref_jenis_kunjungan_ln j ON (j.jnsklnrId=p.klnJnsklnrId)
WHERE 
   p.klnPegKode='%s' AND
   p.klnId='%s' 
"; 

$sql['get_combo_jenis_kunjungan']="
SELECT
	jnsklnrId as id,
	jnsklnrNama as name
FROM
	sdm_ref_jenis_kunjungan_ln 
";

$sql['get_combo_asal_dana']="
SELECT
	asldnrId as id,
	asldnrNama as name
FROM
	sdm_ref_asal_dana 
";

$sql['get_combo_negara']="
SELECT
   satwilId AS id,
   satwilNama AS name
FROM
   pub_ref_satuan_wilayah
WHERE 
   satwilLevel LIKE '2.%' OR satwilLevel = '1' OR satwilKode = '9000'
ORDER BY IF(satwilKode = '9000', 1, 0) DESC, satwilNama ASC
";


/*
klnId
klnPegKode
klnJnsklnrId
klnTujuan
klnNegId
klnTglMulai
klnTglSelesai
klnAsldnrId
klnKeterangan
*/

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_kunjungan_ln(klnPegKode,klnJnsklnrId,klnTujuan,klnNegId,klnTglMulai,klnTglSelesai,klnAsldnrId,klnKeterangan,klnUpload)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s')   
";

$sql['do_update'] = "
UPDATE sdm_kunjungan_ln
SET 
   	klnPegKode = '%s',
      klnJnsklnrId = '%s',
      klnTujuan = '%s',
      klnNegId = '%s',
      klnTglMulai = '%s',
      klnTglSelesai = '%s',
      klnAsldnrId = '%s',
      klnKeterangan = '%s',
	  klnUpload = '%s'
WHERE 
	klnId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_kunjungan_ln
WHERE 
   klnId = %s  
";

?>
