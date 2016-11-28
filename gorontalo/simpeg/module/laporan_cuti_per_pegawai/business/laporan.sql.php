<?php
$sql['get_combo_nip'] = "
SELECT
  pegId AS id,
  CONCAT(pegKodeResmi,' - ',pegNama)AS name
FROM 
  pub_pegawai
";

$sql['get_data_cuti']="
SELECT 
   cutiId as id,
   cutiNo as no_cuti,
   cutiMulai as mulai,
   cutiSelesai as selesai,
   tipecutiNama as nama,
   cutiStatus as status,
   cutiTggjwbKerja as tggjwb
FROM 
   sdm_cuti
   LEFT JOIN sdm_ref_tipe_cuti ON tipecutiId = cutiTipecutiId
WHERE
   cutiPegId = %s 
ORDER BY 
   cutiNo
LIMIT %s,%s
"; 

$sql['get_data_cuti_xls']="
SELECT 
   cutiId as id,
   cutiNo as no_cuti,
   cutiMulai as mulai,
   cutiSelesai as selesai,
   tipecutiNama as nama,
   cutiStatus as status,
   cutiTggjwbKerja as tggjwb
FROM 
   sdm_cuti
   LEFT JOIN sdm_ref_tipe_cuti ON tipecutiId = cutiTipecutiId
WHERE
   cutiPegId = %s 
ORDER BY 
   cutiNo
"; 

$sql['get_count_data_cuti']="
SELECT 
   COUNT(cutiId) AS jumlah
FROM 
   sdm_cuti
   LEFT JOIN sdm_ref_tipe_cuti ON tipecutiId = cutiTipecutiId
WHERE
   cutiPegId = %s
 ";
 
 $sql['get_count_data'] = "
SELECT
    COUNT(pegId) as total
FROM
    pub_pegawai
WHERE
    pegNama LIKE '%s' AND
    NOT (pegId IN (
		   SELECT 
		      peguserPegId as pegId
		   FROM 
		      pub_pegawai_user
		      INNER JOIN gtfw_user u ON u.userId=peguserUserId
		      INNER JOIN gtfw_user_def_group dg ON u.UserId = dg.UserId
		   WHERE
		      GroupId='%s')
		)
";

$sql['get_data'] = "
SELECT
    pegId as id,
	pegKodeResmi as nip,
	pegNama as nama
FROM
    pub_pegawai
WHERE
    pegNama LIKE '%s' AND
    NOT (pegId IN (
		   SELECT 
		      peguserPegId as pegId
		   FROM 
		      pub_pegawai_user
		      INNER JOIN gtfw_user u ON u.userId=peguserUserId
		      INNER JOIN gtfw_user_def_group dg ON u.UserId = dg.UserId
		   WHERE
		      GroupId='%s')
		)
ORDER BY pegNama
LIMIT %s,%s
";
?>
