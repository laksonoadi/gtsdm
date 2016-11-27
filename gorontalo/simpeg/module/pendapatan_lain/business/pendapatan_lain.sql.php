<?php
//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   sdm_pendapatan_lain a
   LEFT JOIN sdm_ref_jenis_pendapatan_lain b ON a.pndptnlainJnsId = b.jnspndptnlainrId 
   LEFT JOIN pub_pegawai c ON a.pndptnlainPegId = c.pegId 
%s
   GROUP BY a.pndptnlainJnsId,b.jnspndptnlainrNama ,a.pndptnlainTanggal,a.pndptnlainDeskripsi
   ";   

$sql['get_data']="
SELECT
   a.pndptnlainJnsId AS 'id',
   b.jnspndptnlainrNama AS 'nama',
   a.pndptnlainTanggal AS 'tgl',
   a.pndptnlainDeskripsi AS 'des',
   SUM(a.pndptnlainNominal) AS 'nominal'
FROM 
   sdm_pendapatan_lain a
   LEFT JOIN sdm_ref_jenis_pendapatan_lain b ON a.pndptnlainJnsId = b.jnspndptnlainrId
   LEFT JOIN pub_pegawai c ON a.pndptnlainPegId = c.pegId 
%s
   GROUP BY a.pndptnlainJnsId,b.jnspndptnlainrNama, a.pndptnlainDeskripsi ,a.pndptnlainTanggal  
   ORDER BY a.pndptnlainTanggal DESC, b.jnspndptnlainrNama,a.pndptnlainDeskripsi ASC
LIMIT %s,%s
"; 

$sql['get_combo_jenis']="
SELECT 
   jnspndptnlainrId as id,
   jnspndptnlainrNama as name
FROM
   sdm_ref_jenis_pendapatan_lain
ORDER BY jnspndptnlainrNama ASC
";

$sql['get_data_by_id']="
SELECT 
   a.pndptnlainJnsId as 'id',
   b.jnspndptnlainrNama as 'nama',
   a.pndptnlainTanggal as 'tgl',
   SUM(a.pndptnlainNominal) as 'nominal',
   a.pndptnlainDeskripsi as 'des'
FROM 
   sdm_pendapatan_lain a
   LEFT JOIN sdm_ref_jenis_pendapatan_lain b ON a.pndptnlainJnsId = b.jnspndptnlainrId
WHERE
   a.pndptnlainDeskripsi = '%s' and a.pndptnlainTanggal = '%s'
GROUP BY a.pndptnlainJnsId,b.jnspndptnlainrNama, a.pndptnlainDeskripsi ,a.pndptnlainTanggal 
";

$sql['get_pegawai_by_id'] = "
SELECT 
  a.pegId as 'id',
  a.pegKodeResmi as 'kode',
  a.pegNama as 'nama',
  b.pndptnlainNominal as 'nominal'
FROM 
  pub_pegawai a
  LEFT JOIN sdm_pendapatan_lain b ON (b.pndptnlainPegId = a.pegId)
WHERE
  b.pndptnlainDeskripsi='%s' and b.pndptnlainTanggal='%s'
ORDER BY
  a.pegKodeResmi ASC
";

// DO-----------
$sql['do_add_pendapatan'] ="
   INSERT INTO 
      sdm_pendapatan_lain
      (pndptnlainJnsId, pndptnlainDeskripsi, pndptnlainTanggal)
   VALUES 
      (%s, '%s', '%s')
";

$sql['do_add_pegawai'] ="
   INSERT INTO 
      sdm_pendapatan_lain
      (pndptnlainPegId, pndptnlainJnsId, pndptnlainNominal, pndptnlainDeskripsi, pndptnlainTanggal)
   VALUES 
      (%s, %s, %s, '%s', '%s')
";

$sql['do_delete_pegawai'] = "
DELETE FROM
   sdm_pendapatan_lain
WHERE 
   pndptnlainDeskripsi = '%s' and pndptnlainTanggal = '%s'   
";

?>
