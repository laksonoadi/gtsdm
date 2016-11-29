<?php


//===GET===
$sql['get_count_pegawai'] = "
SELECT 
   COUNT(pegId) AS total
FROM 
   pub_pegawai
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
   pegKodeResmi like '%s' %s
   pegNama like '%s'
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


$sql['get_list_mutasi_penelitian']="
SELECT DISTINCT 
   p.pnltnId as id,
   p.pnltnPegKode as nip, 
   p.pnltnTipePenelitianId as tipeId,
   p.pnltnJudulBuku as judulBuku,
   p.pnltnJudulArtikel as judulArtikel,
   p.pnltnJudulKaryaIlmiah as judulPenelitian,
   p.pnltnJudulPublikasi as judulPublikasi,
   ppnltnrPeranan as peranan,
   p.pnltnTahun as tahun,
   p.pnltnUpload as upload,
   CASE 
	WHEN p.pnltnTipePenelitianId=1 THEN UPPER(jnsbukuNama)
	WHEN p.pnltnTipePenelitianId=2 THEN UPPER(jnsbukuNama)
	WHEN p.pnltnTipePenelitianId=3 THEN UPPER(CONCAT(jnsPenelitianNama,IF(jnskryrNama IS NULL,'',CONCAT(' - ',jnskryrNama))))
	WHEN p.pnltnTipePenelitianId=4 THEN UPPER(jnspublikasiNama)
   END AS jenis
FROM
	sdm_penelitian p
	LEFT JOIN sdm_ref_peranan_penelitian ON (pnltnPpnltnrId=ppnltnrId)
	LEFT JOIN sdm_ref_jenis_buku ON jnsbukuId=pnltnJnsbukuId
	LEFT JOIN sdm_ref_jenis_penelitian ON jnsPenelitianId=pnltnJnspenelitianId
	LEFT JOIN sdm_ref_jenis_karya ON pnltnJnskryrId=jnskryrId
	LEFT JOIN sdm_ref_jenis_publikasi ON jnspublikasiId=pnltnJnspublikasiId
WHERE 
   p.pnltnPegKode='%s'
"; 

$sql['get_data_mutasi_penelitian_by_id']="
SELECT 
	pnltnId,
	pnltnPegKode,
	pegKodeResmi,
	pnltnTipePenelitianId,
	pnltnJnsbukuId,
	pnltnJnspenelitianId,
	pnltnJnspublikasiId,
	pnltnJnskryrId,
	pnltnJnskegrId,
	pnltnPpnltnrId,
	pnltnJudulBuku,
	pnltnJudulArtikel,
	pnltnJudulKaryaIlmiah,
	pnltnJudulPublikasi,
	pnltnPenerbit,
	pnltnTahun,
	pnltnAsldnrId,
	pnltnKeterangan,
	pnltnUpload AS upload
FROM
	sdm_penelitian 
	LEFT JOIN pub_pegawai ON pnltnPegKode=pegId
WHERE 
	pnltnPegKode='%s' AND
	pnltnId='%s' 
"; 

$sql['get_combo_jenis_buku']="
SELECT
	jnsbukuId as id,
	jnsbukuNama as name
FROM
	sdm_ref_jenis_buku 
";

$sql['get_combo_jenis_karya']="
SELECT
	jnskryrId as id,
	jnskryrNama as name
FROM
	sdm_ref_jenis_karya 
";

$sql['get_combo_jenis_penelitian']="
SELECT
	jnspenelitianId as id,
	jnspenelitianNama as name
FROM
	sdm_ref_jenis_penelitian 
";

$sql['get_combo_jenis_kegiatan']="
SELECT
	jnskegrId as id,
	jnskegrNama as name
FROM
	sdm_ref_jenis_kegiatan 
";

$sql['get_combo_jenis_publikasi']="
SELECT
	jnspublikasiId as id,
	jnspublikasiNama as name
FROM
	sdm_ref_jenis_publikasi 
";

$sql['get_combo_peranan']="
SELECT
	ppnltnrId as id,
	ppnltnrPeranan as name
FROM
	sdm_ref_peranan_penelitian 
";

$sql['get_combo_asal_dana']="
SELECT
	asldnrId as id,
	asldnrNama as name
FROM
	sdm_ref_asal_dana 
";

// DO-----------
$sql['do_add_buku'] = "
INSERT INTO sdm_penelitian(
      pnltnPegKode,
      pnltnTipePenelitianId,
      pnltnJnsbukuId,
      pnltnJudulBuku,
      pnltnJnskegrId,
      pnltnPpnltnrId,
      pnltnTahun,
      pnltnPenerbit,
      pnltnKeterangan,
	  pnltnUpload
   )
   VALUES('%s',1,'%s','%s','%s','%s','%s','%s','%s','%s')   
";

$sql['do_add_artikel'] = "
INSERT INTO sdm_penelitian(
      pnltnPegKode,
      pnltnTipePenelitianId,
      pnltnJnsbukuId,
      pnltnJudulArtikel,
      pnltnJnskegrId,
      pnltnPpnltnrId,
      pnltnTahun,
      pnltnKeterangan,
	  pnltnUpload
   )
   VALUES('%s',2,'%s','%s','%s','%s','%s','%s','%s')   
";

$sql['do_add_penelitian'] = "
INSERT INTO sdm_penelitian(
      pnltnPegKode,
      pnltnTipePenelitianId,
      pnltnJnskryrId,
      pnltnJnspenelitianId,
      pnltnJudulKaryaIlmiah,
      pnltnPpnltnrId,
      pnltnTahun,
      pnltnAsldnrId,
      pnltnKeterangan,
	  pnltnUpload
   )
   VALUES('%s',3,'%s','%s','%s','%s','%s','%s','%s','%s')   
";

$sql['do_add_publikasi'] = "
INSERT INTO sdm_penelitian(
      pnltnPegKode,
      pnltnTipePenelitianId,
      pnltnJnspublikasiId,
      pnltnJudulPublikasi,
      pnltnPpnltnrId,
      pnltnTahun,
      pnltnKeterangan,
	  pnltnUpload
   )
   VALUES('%s',4,'%s','%s','%s','%s','%s','%s')   
";

$sql['do_add_publikasi_integrasi'] = "
INSERT INTO pg_publikasi_dosen(
      pubdDsnPegNip,
      pubdJudul
   )
   VALUES('%s','%s')   
";

$sql['do_update_buku'] = "
UPDATE sdm_penelitian
SET 
      pnltnPegKode='%s',
      pnltnJnsbukuId='%s',
      pnltnJudulBuku='%s',
      pnltnJnskegrId='%s',
      pnltnPpnltnrId='%s',
      pnltnTahun='%s',
      pnltnPenerbit='%s',
      pnltnKeterangan='%s',
	  pnltnUpload ='%s'
WHERE
      pnltnId = %s     
";

$sql['do_update_artikel'] = "
UPDATE sdm_penelitian
SET 
      pnltnPegKode='%s',
      pnltnJnsbukuId='%s',
      pnltnJudulArtikel='%s',
      pnltnJnskegrId='%s',
      pnltnPpnltnrId='%s',
      pnltnTahun='%s',
      pnltnKeterangan='%s',
	  pnltnUpload ='%s'
WHERE
      pnltnId = %s   
";

$sql['do_update_penelitian'] = "
UPDATE sdm_penelitian
SET 
      pnltnPegKode='%s',
      pnltnJnskryrId='%s',
      pnltnJnspenelitianId='%s',
      pnltnJudulKaryaIlmiah='%s',
      pnltnPpnltnrId='%s',
      pnltnTahun='%s',
      pnltnAsldnrId='%s',
      pnltnKeterangan='%s',
	  pnltnUpload ='%s'
WHERE
      pnltnId = %s   
";

$sql['do_update_publikasi'] = "
UPDATE sdm_penelitian
SET 
      pnltnPegKode='%s',
      pnltnJnspublikasiId='%s',
      pnltnJudulPublikasi='%s',
      pnltnPpnltnrId='%s',
      pnltnTahun='%s',
      pnltnKeterangan='%s',
	  pnltnUpload ='%s'
WHERE
      pnltnId = %s 
"; 

$sql['do_delete'] = "
DELETE FROM
   sdm_penelitian
WHERE 
   pnltnId = %s  
";

?>
