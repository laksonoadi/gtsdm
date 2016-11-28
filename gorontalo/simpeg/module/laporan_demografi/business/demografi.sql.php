<?php
//===GET===
$sql['get_jumlah_manpower']="
SELECT 
   count(distinct pegId) as jumlah
FROM 
   pub_pegawai
   INNER JOIN sdm_satuan_kerja_pegawai ON (pegId=satkerpegPegId AND satkerpegAktif='Aktif')
   INNER JOIN sdm_verifikasi_data ON pegId=verdataValue 
WHERE
   satkerpegSatkerId='%s' and 0='%s'
   AND verdataStatus='3' AND verdataVerifikasiId='19'
";

$sql['get_list_unit_kerja']="
SELECT 
   satkerId as id,
   satkerLevel as unit_kerja_kode,
   satkerNama as label
FROM 
   pub_satuan_kerja
";

$sql['get_combo_unit_kerja_like'] = "
SELECT
  satkerId AS id,
  ROUND((LENGTH(satkerLevel)-LENGTH(REPLACE(satkerLevel, '.', '')))/LENGTH('.')) AS level,
  satkerNama AS name,
  satkerNama AS judul,
  satkerNama AS label
FROM 
  pub_satuan_kerja
WHERE 1=1 %filter%
ORDER BY satkerLevel
";

$sql['get_list_status']="
SELECT 
   jnspegrId as id,
   jnspegrNama as label
FROM 
   sdm_ref_jenis_pegawai
";

$sql['get_jumlah_status']="
SELECT 
   count(distinct pegId) as jumlah
FROM 
   pub_pegawai
   INNER JOIN sdm_satuan_kerja_pegawai ON (pegId=satkerpegPegId AND satkerpegAktif='Aktif')
   INNER JOIN sdm_verifikasi_data ON pegId=verdataValue 
WHERE
   satkerpegSatkerId='%s' AND pegJnspegrId='%s'
   AND verdataStatus='3' AND verdataVerifikasiId='19'
";

$sql['get_list_posisi']="
SELECT 
   jabstrukrId as id,
   jabstrukrNama as label
FROM 
   sdm_ref_jabatan_struktural
   WHERE jabstrukrSatker IN(%s)
";

$sql['get_jumlah_posisi']="
SELECT 
   count(distinct pegId) as jumlah
FROM 
   pub_pegawai
   INNER JOIN sdm_satuan_kerja_pegawai ON (pegId=satkerpegPegId AND satkerpegAktif='Aktif')
   INNER JOIN sdm_jabatan_struktural ON (pegId=jbtnPegKode AND jbtnStatus='Aktif')
   INNER JOIN sdm_ref_jabatan_struktural ON (jabstrukrId=jbtnJabstrukrId)
   INNER JOIn sdm_ref_tipe_struktural ON (jabstrukTpstrId=tpstrId)
   INNER JOIN sdm_verifikasi_data ON pegId=verdataValue 
WHERE
   satkerpegSatkerId='%s' AND jabstrukrId='%s'
   AND verdataStatus='3' AND verdataVerifikasiId='19'
";

$sql['get_list_grade']="
SELECT 
   pktGolrId as id,
   concat(pktgolrId) as label
FROM 
   sdm_ref_pangkat_golongan
ORDER BY pktgolrUrut DESC
";

$sql['get_jumlah_grade']="
SELECT 
   count(distinct pegId) as jumlah
FROM 
   pub_pegawai
   INNER JOIN sdm_satuan_kerja_pegawai ON (pegId=satkerpegPegId AND satkerpegAktif='Aktif')
   INNER JOIN sdm_pangkat_golongan ON (pegId=pktgolPegKode ANd pktgolStatus='Aktif')
   INNER JOIN sdm_verifikasi_data ON pegId=verdataValue 
WHERE
   satkerpegSatkerId='%s' AND pktgolPktgolrId='%s' AND verdataStatus='3' AND verdataVerifikasiId='19'
";

$sql['get_list_grade_academic']="
SELECT 
   jabfungrId as id,
   jabfungrNama as label
FROM 
   pub_ref_jabatan_fungsional
";

$sql['get_jumlah_grade_academic']="
SELECT 
   count(distinct pegId) as jumlah
FROM 
   pub_pegawai
   INNER JOIN sdm_satuan_kerja_pegawai ON (pegId=satkerpegPegId AND satkerpegAktif='Aktif')
   INNER JOIN sdm_jabatan_fungsional ON (pegId=jbtnPegKode AND jbtnStatus='Aktif')
   INNER JOIN sdm_verifikasi_data ON pegId=verdataValue 
WHERE
   satkerpegSatkerId='%s' AND jbtnJabfungrId='%s'   AND verdataStatus='3' AND verdataVerifikasiId='19'
";

$sql['get_jumlah_lama_kerja']="
SELECT 
   count(distinct pegId) as jumlah
FROM 
   pub_pegawai
   INNER JOIN sdm_satuan_kerja_pegawai ON (pegId=satkerpegPegId AND satkerpegAktif='Aktif')
   INNER JOIN sdm_verifikasi_data ON pegId=verdataValue 
WHERE
   satkerpegSatkerId='%s' AND
   if (round(DATEDIFF(now(),pegTglMasukInstitusi)/365)>8,8,round(DATEDIFF(now(),pegTglMasukInstitusi)/365)) BETWEEN '%s' AND '%s'
      AND verdataStatus='3' AND verdataVerifikasiId='19'
";

$sql['get_jumlah_jenis_kelamin']="
SELECT 
   count(distinct pegId) as jumlah
FROM 
   pub_pegawai
   INNER JOIN sdm_satuan_kerja_pegawai ON (pegId=satkerpegPegId AND satkerpegAktif='Aktif')
   INNER JOIN sdm_verifikasi_data ON pegId=verdataValue 
WHERE
   satkerpegSatkerId='%s' AND
   pegKelamin='%s' AND verdataStatus='3' AND verdataVerifikasiId='19'
";

$sql['get_jumlah_umur']="
SELECT 
   count(distinct pegId) as jumlah
FROM 
   pub_pegawai
   INNER JOIN sdm_satuan_kerja_pegawai ON (pegId=satkerpegPegId AND satkerpegAktif='Aktif')
   INNER JOIN sdm_verifikasi_data ON pegId=verdataValue 
WHERE
   satkerpegSatkerId='%s' AND
   ROUND(DATEDIFF(now(),pegTglLahir)/365) BETWEEN '%s' AND '%s' AND verdataStatus='3' AND verdataVerifikasiId='19'
";

$sql['get_list_pendidikan']="
SELECT 
   pendId as id,
   pendNama as label
FROM 
   pub_ref_pendidikan
WHERE
   pendId>3
";

$sql['get_jumlah_pendidikan']="
SELECT 
   count(distinct pegId) as jumlah
FROM 
   pub_pegawai
   INNER JOIN sdm_satuan_kerja_pegawai ON (pegId=satkerpegPegId AND satkerpegAktif='Aktif')
   INNER JOIN sdm_pendidikan ON (pegId=pddkPegKode)
   INNER JOIN sdm_verifikasi_data ON pegId=verdataValue 
WHERE
   satkerpegSatkerId='%s' AND pddkTkpddkrId='%s' AND verdataStatus='3' AND verdataVerifikasiId='19'
";

$sql['get_list_status_nikah']="
SELECT 
   statnkhId as id,
   statnkhNama as label
FROM 
   pub_ref_status_nikah
";

$sql['get_jumlah_status_nikah']="
SELECT 
   count(distinct pegId) as jumlah
FROM 
   pub_pegawai
   INNER JOIN sdm_satuan_kerja_pegawai ON (pegId=satkerpegPegId AND satkerpegAktif='Aktif')
   INNER JOIN sdm_verifikasi_data ON pegId=verdataValue 
WHERE
   satkerpegSatkerId='%s' AND pegStatnikahId='%s' AND verdataStatus='3' AND verdataVerifikasiId='19'
";

$sql['get_list_agama']="
SELECT 
   agmId as id,
   agmNama as label
FROM 
   pub_ref_agama
";

$sql['get_jumlah_agama']="
SELECT 
   count(distinct pegId) as jumlah
FROM 
   pub_pegawai
   INNER JOIN sdm_satuan_kerja_pegawai ON (pegId=satkerpegPegId AND satkerpegAktif='Aktif')
   INNER JOIN sdm_verifikasi_data ON pegId=verdataValue 
WHERE
   satkerpegSatkerId='%s' AND pegAgamaId='%s' AND verdataStatus='3' AND verdataVerifikasiId='19'
";

$sql['get_satker_and_level']="
SELECT 
a.satkerId,
a.satkerLevel
FROM 
pub_satuan_kerja AS a
INNER JOIN gtfw_user_satuan_kerja ON 
userunitSatuanKerjaId=satkerId 
WHERE userunitUserId='%s' 
";

?>
