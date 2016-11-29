<?php
$sql['list_policy_terbaru'] = "
SELECT 
   policyId as ID,
   policyNama as TITLE,
   policyKeterangan as RINGKAS,
   policyTanggalPolicy as DATE_POSTED
FROM 
   w_policy
WHERE 
  policyIsAktif=1
ORDER BY policyId DESC
LIMIT 1
";

$sql['list_beberapa_policy'] = "
SELECT 
   policyId as ID,
   policyNama as TITLE,
   policyKeterangan as RINGKAS,
   policyTanggalPolicy as DATE_POSTED
FROM 
   w_policy
WHERE 
  policyIsAktif=1
ORDER BY policyId DESC
LIMIT 1,5
";

$sql['list_beberapa_policy2'] = "
SELECT 
   policyId as ID,
   policyNama as TITLE,
   policyKeterangan as RINGKAS,
   policyTanggalPolicy as DATE_POSTED
FROM 
   w_policy
WHERE 
  policyIsAktif=1
ORDER BY policyId DESC
LIMIT 5
";

$sql['list_policy'] = "
SELECT 
   policyId as ID,
   policyNama as TITLE,
   policyKeterangan as RINGKAS,
   DATE(policyTanggalPosting) as DATE_POSTED,
   jnspolicyNama as KATEGORI,
   satkerNama as SATKER
FROM 
   w_policy
   INNER JOIN w_ref_jenis_policy ON (policyJnspolicyId=jnspolicyId)
   LEFT JOIN w_ref_satuan_kerja_policy ON (satkerpolicyId=policySatkerpolicyId)
   LEFT JOIN pub_satuan_kerja ON (satkerId=satkerpolicySatkerId)
WHERE
  policySatkerpolicyId=%s
  AND policyJnspolicyId=%s 
  AND policyIsAktif=1
ORDER BY policyTanggalPosting DESC
";

$sql['list_policy_file'] = "
SELECT 
   filePolicyFile as file,
   DATE(filePolicyTgl) as tanggal_upload,
   filepolicyIsDownload as is_download
FROM 
   w_policy_file
WHERE 
  filepolicyStatus='Aktif' AND
  filepolicyPolicyId='%s'
ORDER BY filepolicyId DESC
";

$sql['list_satuan_kerja_policy'] = "
SELECT
  satkerpolicyId as id,
  satkerNama as satker,
  ifnull(count(policyId), '-') as total_policy
FROM
  w_ref_satuan_kerja_policy
LEFT JOIN w_policy ON satkerpolicyId=policySatkerpolicyId
LEFT JOIN pub_satuan_kerja ON satkerId=satkerpolicySatkerId
GROUP BY
  satkerpolicyId
ORDER BY
  satkerNama ASC
";

$sql['list_jenis_policy'] = "
SELECT 
  policyJnspolicyId as id,
  jnspolicyNama as jenis_policy,
  count(filepolicyId) as total_policy
FROM 
  w_ref_jenis_policy
LEFT JOIN w_policy ON policyJnspolicyId=jnspolicyId and policySatkerpolicyId='%s'
LEFT JOIN w_policy_file ON policyId=filepolicyPolicyId
GROUP BY 
  jnspolicyId  
ORDER BY 
  jnspolicyNama ASC
";

$sql['get_policy_by_id'] = "
SELECT 
   policyId as ID,
   policyNama as TITLE,
   policyKeterangan as ARTIKEL,
   policyTanggalPolicy as DATE_POSTED,
   policyPengirim as PENGIRIM
FROM 
   w_policy
WHERE 
  policyId=%s
";

$sql['get_satuan_kerja_policy_by_id'] = "
SELECT 
   satkerId as id,
   satkerNama as nama
FROM
  pub_satuan_kerja
LEFT JOIN w_ref_satuan_kerja_policy on satkerpolicySatkerId=satkerId
WHERE 
  satkerpolicyId=%s
";

$sql['get_jenis_policy_by_id'] = "
SELECT 
   jnspolicyId as id,
   jnspolicyNama as nama
FROM 
  w_ref_jenis_policy
WHERE 
  jnspolicyId=%s
";

$sql['list_newest_file'] = "
SELECT 
   satkerId as satkerId,
   satkerNama as satker,
   satkerpolicyId as satkerpolicyId,
   satkerpolicyDeskripsi as satkerpolicyDeskripsi,
   policyId as policyId,
   policyNama as policy,
   jnspolicyId as jnspolicyId,
   jnspolicyNama as jnspolicy,
   filePolicyFile as file,
   DATE(filePolicyTgl) as tanggal_upload,
   TIME(filePolicyTgl) as jam_upload,
   filepolicyIsDownload as is_download
FROM 
   w_policy_file
LEFT JOIN w_policy ON policyId = filepolicyPolicyId
LEFT JOIN w_ref_jenis_policy ON policyJnspolicyId = jnspolicyId
LEFT JOIN w_ref_satuan_kerja_policy ON policySatkerpolicyId = satkerpolicyId
LEFT JOIN pub_satuan_kerja ON satkerId = satkerpolicySatkerId
WHERE 
  filepolicyStatus = 'Aktif'
  AND satkerpolicyStatus = 'Aktif'
GROUP BY policyId
ORDER BY filepolicyId DESC
LIMIT %d
";

?>