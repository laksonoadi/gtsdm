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

/*
pddkId
pddkPegKode
pddkTkpddkrId
pddkInstitusi
pddkJurusan
pddkThnLulus
pddkTempat
pddkKepala
pddkNegaraId
pddkAsldnrId
pddkLamaDinas
pddkTglMulaiDinas
pddkTglSelesaiDinas
pddkKeterangan
pddkPktGolMax
pddkStatusTamat
*/

$sql['get_list_mutasi_pendidikan']="
SELECT DISTINCT 
   p.pddkId as id,
   p.pddkPegKode as nip, 
   p.pddkTkpddkrId as jpendid,
   p.pddkInstitusi as institusi,
   p.pddkJurusan as jurusan,
   p.pddkThnLulus as lulus,
   p.pddkNoIjazah as no_ijazah,
   p.pddkTempat as tempat,
   p.pddkKepala as kepsek,
   p.pddkNegaraId as negid,
   p.pddkAsldnrId as asdanid,
   p.pddkLamaDinas as lama,
   p.pddkTglMulaiDinas as mulai,
   p.pddkTglSelesaiDinas as selesai,
   p.pddkKeterangan as ket,
   p.pddkPktGolMax as golmax,
   p.pddkStatusTamat as istamat,
   jp.pendNama as jpendlabel,
   n.satwilNama as neglabel,
   p.pddkUpload as upload
FROM
	sdm_pendidikan p
	LEFT JOIN pub_ref_pendidikan jp ON (jp.pendId=p.pddkTkpddkrId)
	LEFT JOIN pub_ref_satuan_wilayah n ON (n.satwilId=p.pddkNegaraId)
WHERE 
   p.pddkPegKode='%s'
ORDER BY p.pddkThnLulus DESC
"; 

$sql['get_list_mutasi_pendidikan_verifikasi']="
SELECT DISTINCT 
   p.pddkId as id,
   p.pddkPegKode as nip, 
   p.pddkTkpddkrId as jpendid,
   p.pddkInstitusi as institusi,
   p.pddkJurusan as jurusan,
   p.pddkThnLulus as lulus,
   p.pddkNoIjazah as no_ijazah,
   p.pddkTempat as tempat,
   p.pddkKepala as kepsek,
   p.pddkNegaraId as negid,
   p.pddkAsldnrId as asdanid,
   p.pddkLamaDinas as lama,
   p.pddkTglMulaiDinas as mulai,
   p.pddkTglSelesaiDinas as selesai,
   p.pddkKeterangan as ket,
   p.pddkPktGolMax as golmax,
   p.pddkStatusTamat as istamat,
   jp.pendNama as jpendlabel,
   n.satwilNama as neglabel,
   p.pddkUpload as upload
FROM
   sdm_pendidikan p
   LEFT JOIN pub_ref_pendidikan jp ON (jp.pendId=p.pddkTkpddkrId)
   LEFT JOIN pub_ref_satuan_wilayah n ON (n.satwilId=p.pddkNegaraId)
   INNER JOIN sdm_verifikasi_data vd ON vd.`verdataValue`=p.`pddkId` AND vd.`verdataStatus`='3' AND vd.`verdataVerifikasiId`='1'
WHERE 
   p.pddkPegKode='%s'
ORDER BY p.pddkThnLulus DESC
"; 


$sql['get_data_mutasi_pendidikan_by_id']="
SELECT 
	p.pddkId as id,
   p.pddkPegKode as nip, 
   p.pddkTkpddkrId as jpendid,
   p.pddkInstitusi as institusi,
   p.pddkJurusan as jurusan,
   p.pddkThnLulus as lulus,
   p.pddkNoIjazah as no_ijazah,
   p.pddkTempat as tempat,
   p.pddkKepala as kepsek,
   p.pddkNegaraId as negid,
   p.pddkAsldnrId as asdanid,
   p.pddkLamaDinas as lama,
   p.pddkTglMulaiDinas as mulai,
   p.pddkTglSelesaiDinas as selesai,
   p.pddkKeterangan as ket,
   p.pddkPktGolMax as golmaxid,
   p.pddkStatusTamat as istamat,
   jp.pendNama as jpendlabel,
   n.satwilNama as neglabel,
   a.asldnrNama as asdanlabel,
   CONCAT(pg.pktgolrId,' ',pg.pktgolrNama) as golmaxlabel,
   p.pddkUpload as upload
FROM
	sdm_pendidikan p
	LEFT JOIN pub_ref_pendidikan jp ON (jp.pendId=p.pddkTkpddkrId)
	LEFT JOIN pub_ref_satuan_wilayah n ON (n.satwilId=p.pddkNegaraId)
	LEFT JOIN sdm_ref_pangkat_golongan pg ON (pg.pktgolrId=p.pddkPktGolMax)
	LEFT JOIN sdm_ref_asal_dana a ON (a.asldnrId=p.pddkAsldnrId)
WHERE 
   p.pddkPegKode='%s' AND
   p.pddkId='%s' 
"; 

$sql['get_combo_tingkat_pendidikan']="
SELECT
	pendId as id,
	pendNama as name
FROM
	pub_ref_pendidikan 
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
	satwilId as id,
	satwilNama as name
FROM
	pub_ref_satuan_wilayah
WHERE 
   satwilLevel NOT LIKE '%1.%' 
ORDER BY satwilNama
";

$sql['get_combo_pktgol']="
SELECT
	pktgolrId as id,
	CONCAT(pktgolrId,' ',pktgolrNama) as name
FROM
	sdm_ref_pangkat_golongan 
";

/*
pddkId
pddkPegKode
pddkTkpddkrId
pddkInstitusi
pddkJurusan
pddkThnLulus
pddkTempat
pddkKepala
pddkNegaraId
pddkAsldnrId
pddkLamaDinas
pddkTglMulaiDinas
pddkTglSelesaiDinas
pddkKeterangan
pddkPktGolMax
pddkStatusTamat
*/
// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_pendidikan(pddkPegKode,pddkTkpddkrId,pddkInstitusi,pddkJurusan,pddkThnLulus,pddkNoIjazah,pddkTempat,pddkKepala,pddkNegaraId,pddkAsldnrId,pddkLamaDinas,pddkTglMulaiDinas,pddkTglSelesaiDinas,pddkKeterangan,pddkPktGolMax,pddkStatusTamat,pddkUpload
   )
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')   
";

$sql['do_update'] = "
UPDATE sdm_pendidikan
SET 
   	pddkPegKode = '%s',
      pddkTkpddkrId = '%s',
      pddkInstitusi = '%s',
      pddkJurusan = '%s',
      pddkThnLulus = '%s',
      pddkNoIjazah = '%s',
      pddkTempat = '%s',
      pddkKepala = '%s',
      pddkNegaraId = '%s',
      pddkAsldnrId = '%s',
      pddkLamaDinas = '%s',
      pddkTglMulaiDinas = '%s',
      pddkTglSelesaiDinas = '%s',
      pddkKeterangan = '%s',
      pddkPktGolMax = '%s',
      pddkStatusTamat = '%s',
	  pddkUpload = '%s'
WHERE 
	pddkId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_pendidikan
WHERE 
   pddkId = %s  
";

?>
