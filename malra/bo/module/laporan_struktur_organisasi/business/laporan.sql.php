<?php
$sql['get_combo_unit_kerja'] = "
SELECT 
a.satkerId AS `id`,
a.satkerNama AS `name`,
a.satkerUnitId AS `unit`
FROM pub_satuan_kerja a
JOIN gtfw_unit b ON b.UnitId = a.satkerUnitId
where a.satkerUnitId != '1'
GROUP BY a.satkerUnitId 
ORDER BY a.satkerId
";

$sql['get_user_satker'] = "
SELECT 
    satkerId,
    satkerNama,
    satkerLevel,
    satkerUnitId 
FROM 
    pub_satuan_kerja 
INNER JOIN gtfw_user_satuan_kerja ON userunitSatuanKerjaId=satkerId 
WHERE userunitUserId='%s' 
";

$sql['get_combo_user_unit_kerja'] = "
SELECT 
    b.satkerId AS `id`,
    b.satkerNama AS `name`
FROM 
    pub_satuan_kerja a
INNER JOIN pub_satuan_kerja b ON (a.satkerId = b.satkerId OR b.satkerLevel LIKE CONCAT(a.satkerLevel, '.%%'))
INNER JOIN gtfw_unit ON b.satkerUnitId=UnitId
WHERE a.satkerId='%s' 
GROUP BY UnitId
";

// $sql['get_struktur_organisasi'] = "
// SELECT
// a.jabstrukrId AS `id_jabatan`,
// a.jabstrukrNama AS `jabatan`,
// b.satkerId AS `id_satker`,
// b.satkerNama AS `satuan`,
// e.pegKodeResmi AS `nip`,
// d.jbtnTglMulai AS `mulai`,
// CONCAT(e.pegGelarDepan,' ',e.pegNama,' ',e.pegGelarBelakang) AS `nama`
// FROM
// sdm_ref_jabatan_struktural a
// LEFT JOIN sdm_jabatan_struktural d ON a.jabstrukrId = d.jbtnJabstrukrId
// LEFT JOIN pub_pegawai e ON d.jbtnPegKode = e.pegId
// JOIN pub_satuan_kerja b ON a.jabstrukrSatker = b.satkerId
// WHERE a.jabstrukrSatker = '%s'
// ";

$sql['get_struktur_organisasi'] = "
SELECT
a.satkerId AS `id`,
a.satkerNama AS `satker`,
a.satkerParentId AS `parent`,
b.jabstrukrNama AS `jabatan`,
b.jabstrukrTingkat AS `tingkat`,
d.pegKodeResmi AS `nip`,
c.jbtnTglMulai AS `mulai`,
CONCAT(d.pegGelarDepan,' ',d.pegNama,' ',d.pegGelarBelakang) AS `nama`
FROM
pub_satuan_kerja a
LEFT JOIN sdm_ref_jabatan_struktural b ON a.satkerId = b.jabstrukrSatker
LEFT JOIN sdm_jabatan_struktural c ON c.jbtnJabstrukrId = b.jabstrukrId
LEFT JOIN pub_pegawai d ON d.pegId = c.jbtnPegKode
WHERE 1=1 
--search--
";



$sql['get_struktur_organisasi_name'] ="
SELECT 
a.satkerNama AS `name`
FROM pub_satuan_kerja a
WHERE a.satkerId = '%s'
";

// $sql['get_parent_struktur'] ="
// SELECT 
// a.satkerId AS `parent`
// FROM pub_satuan_kerja a
// WHERE a.satkerParentId = '%s' 
// ";

?>
