<?php
$sql['cek_absensi_pegawai'] = "
SELECT 
    absensiId 
FROM  
    sdm_absensi
WHERE 
    absensiPegKodeGateAccess = '%s' AND absensiTanggal = '%s'
";

$sql['add_absensi_harian'] = "
INSERT INTO 
	sdm_absensi
SET
	absensiPegNama='%s',
	absensiJamMasuk='%s',
	absensiJamKeluar='%s',
	absensiAbsenMasuk='%s',
	absensiAbsenKeluar='%s',
	absensiPegKodeGateAccess='%s',
	absensiTanggal='%s'
";

$sql['update_absensi_harian'] = "
UPDATE 
	sdm_absensi
SET
	absensiPegNama='%s',
	absensiJamMasuk='%s',
	absensiJamKeluar='%s',
	absensiAbsenMasuk='%s',
	absensiAbsenKeluar='%s'
WHERE
	absensiPegKodeGateAccess='%s' AND
	absensiTanggal='%s'
";

$sql['update_kode_absensi_harian'] = "
UPDATE 
	sdm_absensi
SET
	absensiKode='%s'
WHERE
	absensiPegKodeGateAccess='%s' AND
	absensiTanggal='%s'
";

$sql['analisis_absensi_harian'] = "
UPDATE 
	sdm_absensi
SET
	absensiTglMasuk=CONCAT(absensiTanggal,' ',absensiAbsenMasuk),
	absensiTglKeluar=CONCAT(absensiTanggal,' ',absensiAbsenKeluar),
	absensiTerlambat=IF(absensiAbsenMasuk>absensiJamMasuk,MINUTE(TIMEDIFF(absensiAbsenMasuk,absensiJamMasuk))+HOUR(TIMEDIFF(absensiAbsenMasuk,absensiJamMasuk))*60,0),
	absensiPulangCepat=IF(absensiAbsenKeluar<absensiJamKeluar,MINUTE(TIMEDIFF(absensiAbsenKeluar,absensiJamKeluar))+HOUR(TIMEDIFF(absensiAbsenKeluar,absensiJamKeluar))*60,0),
	absensiLamaWaktu=(MINUTE(TIMEDIFF(absensiAbsenKeluar,absensiAbsenMasuk))+HOUR(TIMEDIFF(absensiAbsenKeluar,absensiAbsenMasuk))*60)
WHERE
	absensiPegKodeGateAccess='%s' AND
	absensiTanggal='%s'
";

$sql['analisis_absensi_harian_all'] = "
UPDATE 
	sdm_absensi
SET
	absensiTglMasuk=CONCAT(absensiTanggal,' ',absensiAbsenMasuk),
	absensiTglKeluar=CONCAT(absensiTanggal,' ',absensiAbsenKeluar),
	absensiTerlambat=IF(absensiAbsenMasuk>absensiJamMasuk,MINUTE(TIMEDIFF(absensiAbsenMasuk,absensiJamMasuk))+HOUR(TIMEDIFF(absensiAbsenMasuk,absensiJamMasuk))*60,0),
	absensiPulangCepat=IF(absensiAbsenKeluar<absensiJamKeluar,MINUTE(TIMEDIFF(absensiAbsenKeluar,absensiJamKeluar))+HOUR(TIMEDIFF(absensiAbsenKeluar,absensiJamKeluar))*60,0),
	absensiLamaWaktu=(MINUTE(TIMEDIFF(absensiAbsenKeluar,absensiAbsenMasuk))+HOUR(TIMEDIFF(absensiAbsenKeluar,absensiAbsenMasuk))*60)
WHERE
	(YEAR(absensiTanggal)<>'0000')AND(MONTH(absensiTanggal)<>'00')AND(DATE(absensiTanggal)<>'00')
";

?>
