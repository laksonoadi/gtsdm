<?php
$sql['get_combo_unit_kerja'] = "
SELECT
  satkerId as id,
  satkerNama as name
FROM 
  pub_satuan_kerja
";

$sql['get_combo_unit_kerja_like'] = "
SELECT
  satkerId AS id,
  ROUND((LENGTH(satkerLevel)-LENGTH(REPLACE(satkerLevel, '.', '')))/LENGTH('.')) AS level,
  satkerNama AS name
FROM 
  pub_satuan_kerja
WHERE 1=1 %filter%
ORDER BY satkerLevel
";

$sql["get_data"] = "
SELECT SQL_CALC_FOUND_ROWS
    *,
    COUNT(sub_pegawai_id) as sub_total,
    GROUP_CONCAT(IFNULL(sub_pegawai_id, -1) SEPARATOR ',') as sub_ids
FROM (
(SELECT
    a.satkerId as satker_id,
    a.satkerNama as satker_nama,
    a.satkerLevel as satker_level,
    /* LEFT(a.satkerLevel, LENGTH(a.satkerLevel) - LOCATE('.', REVERSE(a.satkerLevel))) as satker_parent_level, */
    CAST(SUBSTRING_INDEX(a.satkerLevel, '.', -1) AS SIGNED INT) as satker_child_index,
    a.satkerParentId as satker_parent_id,
    (LENGTH(a.satkerLevel) - LENGTH(REPLACE(a.satkerLevel, '.', ''))) AS lv,
    jabstrukr.jabstrukrId as jabatan_id,
    jabstrukr.jabstrukrNama as jabatan_nama,
    jabstruk.jbtnTglMulai as jabatan_tmt,
    pegId as pegawai_id,
    pegNama as pegawai_nama,
    jabfung.jbtnPegKode as sub_pegawai_id,
    satkerpegTmt as sub_satker_tmt,
    c.total as sub_satker_total,
    jabfung.jbtnTglMulai as sub_jabfung_tmt,
    CONCAT(IFNULL(pegGelarDepan, ''), IF(IFNULL(pegGelarDepan, '') = '', '', ' '), pegNama, IF(IFNULL(pegGelarBelakang, '') = '', '', ', '), IFNULL(pegGelarBelakang, '')) AS pegawai_nama_gelar,
    0 as is_sub
FROM pub_satuan_kerja a
    LEFT JOIN (SELECT * FROM pub_satuan_kerja WHERE satkerId = '--unit_satker--') b ON 1=1
    LEFT JOIN (SELECT COUNT(zz2.satkerId) as total FROM pub_satuan_kerja zz1 INNER JOIN pub_satuan_kerja zz2 ON zz2.satkerParentId = zz1.satkerId OR ((LENGTH(zz1.satkerLevel) - LENGTH(REPLACE(zz1.satkerLevel, '.', ''))) = (LENGTH(zz2.satkerLevel) - LENGTH(REPLACE(zz2.satkerLevel, '.', ''))) + 1) WHERE zz1.satkerId = '--unit_satker--' GROUP BY zz2.satkerParentId) c ON 1=1
    LEFT JOIN sdm_ref_jabatan_struktural jabstrukr ON a.satkerId = jabstrukr.jabstrukrSatker
    LEFT JOIN sdm_jabatan_struktural jabstruk ON jabstrukr.jabstrukrId = jbtnJabstrukrId AND jabstruk.jbtnStatus = 'Aktif'
    LEFT JOIN pub_pegawai ON jabstruk.jbtnPegKode = pegId
    LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegSatkerJabId = jabstrukrId AND satkerpegSatkerId = a.satkerId AND satkerpegAktif = 'Aktif'
    LEFT JOIN sdm_jabatan_fungsional jabfung ON satkerpegPegId = jabfung.jbtnPegKode AND jabfung.jbtnStatus = 'Aktif'
WHERE 1=1
    --where--
    AND jabstrukrId IS NOT NULL
GROUP BY a.satkerId, jabstrukrId, satkerpegPegId)
UNION
(SELECT
    a.satkerId as satker_id,
    a.satkerNama as satker_nama,
    a.satkerLevel as satker_level,
    /* LEFT(a.satkerLevel, LENGTH(a.satkerLevel) - LOCATE('.', REVERSE(a.satkerLevel))) as satker_parent_level, */
    CAST(SUBSTRING_INDEX(a.satkerLevel, '.', -1) AS SIGNED INT) as satker_child_index,
    a.satkerParentId as satker_parent_id,
    (LENGTH(a.satkerLevel) - LENGTH(REPLACE(a.satkerLevel, '.', ''))) AS lv,
    NULL as jabatan_id,
    NULL as jabatan_nama,
    NULL as jabatan_tmt,
    NULL as pegawai_id,
    NULL as pegawai_nama,
    NULL as sub_pegawai_id,
    NULL as sub_satker_tmt,
    NULL as sub_jabfung_tmt,
    0 as sub_satker_total,
    NULL AS pegawai_nama_gelar,
    0 as is_sub
FROM pub_satuan_kerja a
    LEFT JOIN (SELECT * FROM pub_satuan_kerja WHERE satkerId = '--unit_satker--') b ON 1=1
    LEFT JOIN sdm_ref_jabatan_struktural ON a.satkerId = jabstrukrSatker
    LEFT JOIN sdm_jabatan_struktural ON jabstrukrId = jbtnJabstrukrId AND jbtnStatus = 'Aktif'
WHERE 1=1
    --where--
    AND jabstrukrId IS NULL)
ORDER BY lv ASC, satker_level ASC, satker_child_index ASC, satker_level ASC, jabatan_nama ASC, jabatan_tmt DESC, sub_satker_tmt DESC, sub_jabfung_tmt DESC
) a
GROUP BY jabatan_id, satker_id
ORDER BY lv ASC, satker_level ASC, satker_child_index ASC, satker_level ASC, jabatan_nama ASC
";

$sql['get_sub_data'] = "
SELECT
    pegId as pegawai_id,
    pegNama as pegawai_nama,
    jbtnId as jabatan_id,
    jabfungrNama as jabatan_nama,
    jabfungrNamaEformasi as jabatan_eformasi,
    jbtnTglMulai as jabatan_tmt,
    a.satkerId as satker_id,
    a.satkerNama as satker_nama,
    a.satkerLevel as satker_level,
    (LENGTH(a.satkerLevel) - LENGTH(REPLACE(a.satkerLevel, '.', ''))) AS lv,
    satkerpegSatkerJabId as parent_jabatan_id,
    jabstrukrNama as parent_jabatan_nama,
    CONCAT(IFNULL(pegGelarDepan, ''), IF(IFNULL(pegGelarDepan, '') = '', '', ' '), pegNama, IF(IFNULL(pegGelarBelakang, '') = '', '', ', '), IFNULL(pegGelarBelakang, '')) AS pegawai_nama_gelar,
    1 as is_sub
FROM pub_pegawai
    LEFT JOIN sdm_satuan_kerja_pegawai ON pegId = satkerpegPegId AND satkerpegAktif = 'Aktif'
    LEFT JOIN pub_satuan_kerja a ON satkerpegSatkerId = satkerId
    LEFT JOIN (SELECT * FROM pub_satuan_kerja WHERE satkerId = '--unit_satker--') b ON 1=1
    LEFT JOIN sdm_jabatan_fungsional ON pegId = jbtnPegKode AND jbtnStatus = 'Aktif'
    LEFT JOIN pub_ref_jabatan_fungsional ON jbtnJabfungrId = jabfungrId
    LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId = satkerpegSatkerJabId
WHERE 1=1
    AND (a.satkerId = b.satkerId OR a.satkerLevel LIKE CONCAT(b.satkerLevel, '.%'))
    AND jabfungrId IS NOT NULL
    AND pegId IN (--peg_list--)
ORDER BY jabatan_nama ASC, jabatan_tmt DESC, pegawai_nama ASC, pegId ASC, satkerpegTmt DESC, jabatan_tmt DESC
";

$sql['get_combo_pendidikan'] = "
SELECT
	  pendId as id,
	  pendNama as name
FROM 
	  pub_ref_pendidikan
ORDER BY pendPendkelId DESC, pendId DESC
";

$sql['get_combo_jenis_pegawai'] = "
SELECT
  jnspegrId as id,
  jnspegrNama as name
FROM 
  sdm_ref_jenis_pegawai
";

$sql['get_combo_jabatan_fungsional'] = "
SELECT
  jabfungjenisrId as id,
  jabfungJenis as name
FROM 
  pub_ref_jabatan_fungsional_jenis
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
