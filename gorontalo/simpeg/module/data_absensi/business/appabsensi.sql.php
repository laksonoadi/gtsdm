<?php
//===GET===
$sql['get_combo_pegawai'] = "
SELECT 
   pegId as id,
   concat(pegKodeResmi,'-',pegKodeGateAccess,'-',pegNama) as name
FROM 
   pub_pegawai
WHERE
   pegStatrId=1
ORDER BY pegNama
";

$sql['get_count_temp'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   sdm_absensi_temp
%s
GROUP BY absensitempId
";   

$sql['get_data_temp']="
SELECT 
   absensitempId as 'id',
   absensitempPegKodeGateAccess as 'gateaccess',
   absensitempPegNama as 'nama',
   DATE(absensitempTgl) as 'tgl',
   TIME_FORMAT(absensitempTgl, '%s') as 'waktu'
FROM 
   sdm_absensi_temp
%s
ORDER BY 
   absensitempTgl DESC
LIMIT %s,%s
";

$sql['get_count'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   sdm_absensi
WHERE
   1=1
   %s
";  

$sql['get_kode_gate_access_by_peg_id'] = "
SELECT 
   LPAD(pegKodeGateAccess,8,0) AS kode_absen,
   pegNama as nama
FROM 
   pub_pegawai
WHERE
   pegId=%s
"; 

$sql['get_data_absen_by_id']="
SELECT 
   absensiId as 'id',
   absensiPegKodeGateAccess as 'gateaccess',
   pegId as 'pegId',
   absensiPegNama as 'nama',
   DATE(absensiTglMasuk) as 'tgl',
   HOUR(absensiTglMasuk) as 'masuk_jam',
   MINUTE(absensiTglMasuk) as 'masuk_menit',
   HOUR(absensiTglKeluar) as 'keluar_jam',
   MINUTE(absensiTglKeluar) as 'keluar_menit',
   absensiIsManual as 'manual',
   absensiAlasan as 'alasan'
FROM 
   sdm_absensi
   LEFT JOIN pub_pegawai ON (pegKodeGateAccess=absensiPegKodeGateAccess)
WHERE
   absensiId=%s
";

$sql['get_data']="
SELECT 
   absensiId as 'id',
   absensiPegKodeGateAccess as 'gateaccess',
   absensiPegNama as 'nama',
   DATE(absensiTglMasuk) as 'tgl',
   TIME_FORMAT(absensiTglMasuk, '%s') as 'masuk',
   TIME_FORMAT(absensiTglKeluar, '%s') as 'keluar',
   
   CASE
	WHEN (absensiKode=1) THEN 'Sakit Dengan Surat Dokter'
	WHEN (absensiKode=2) THEN 'Tidak Masuk Dengan Keterangan/Sakit Tanpa Surat Dokter'
	WHEN (absensiKode=3) THEN 'Uang Makan Dinas Lebih Besar Dari Uang Makan Harian'
	WHEN (absensiTerlambat>0) THEN 'Terlambat'
	WHEN (absensiPulangCepat>0) THEN 'Pulang Cepat'
	ELSE 'Tepat Waktu'
   END AS STATUS,
   
   CASE
    WHEN (absensiKode=1) THEN 'blue'
	WHEN (absensiKode=2) THEN 'blue'
	WHEN (absensiKode=3) THEN 'blue'
	WHEN (absensiTerlambat>0) THEN 'red'
	WHEN (absensiPulangCepat>0) THEN 'red'
	ELSE 'green'
   END AS WARNA_STATUS,
   
   absensiIsManual as 'manual'
FROM 
   sdm_absensi
WHERE
   1=1
   %s
ORDER BY absensiPegKodeGateAccess ASC, DATE(absensiTglMasuk) ASC
LIMIT %s,%s
";

$sql['get_data_sheet1']="
SELECT 
   absensiId as 'id',
   absensiPegKodeGateAccess as 'gateaccess',
   absensiPegNama as 'nama',
   DATE(absensiTglMasuk) as 'tgl',
   TIME_FORMAT(absensiTglMasuk, '%s') as 'masuk',
   TIME_FORMAT(absensiTglKeluar, '%s') as 'keluar',
   absensiIsManual as 'manual'
FROM 
   sdm_absensi
%s
ORDER BY 
   absensiTglMasuk DESC
";

$sql['get_data_by_id'] = "
SELECT 
      a.pegId as `id`,
      a.pegKodeResmi as `nip`,
      a.pegNama as `nama`,
      a.pegAlamat as `alamat`,
      a.pegNoHp as `hp`,
      a.pegNoTelp as `telp`,
      b.pegrekRekening as `rekening`,
      b.pegrekBankId as `bank`,
      c.bankNama as `bank_label`,
		  e1.satkerNama AS `satker_unit`,
      d.mstgajiIsCash as cash,
      d.mstgajiIsAktif as aktif,
      d.mstgajiTanggalGaji as tgl_gajian
   FROM 
      pub_pegawai a
      LEFT JOIN pub_pegawai_rekening b ON (b.pegrekPegId = a.pegId)
      LEFT JOIN pub_ref_bank c ON (c.bankId = b.pegrekBankId)
      LEFT JOIN sdm_ref_master_gaji d ON (d.mstgajiPegId = a.pegId)
      LEFT JOIN sdm_satuan_kerja_pegawai e ON (e.satkerpegPegId = a.pegId)
      LEFT JOIN pub_satuan_kerja e1 ON (e1.satkerId = e.satkerpegSatkerId)
   WHERE
      a.pegId='%s'
";

$sql['get_total_pegawai_aktif'] = "
   SELECT 
      COUNT(*) as total
   FROM 
      pub_pegawai
      LEFT JOIN sdm_ref_master_gaji ON (mstgajiPegId = pegId)
   WHERE mstgajiIsAktif='Ya'
      ";
      

$sql['get_total_seluruh'] = "
   SELECT SUM(gaji) AS total FROM(
SELECT  
      (SELECT gajipegTotalGaji FROM sdm_gaji_pegawai WHERE gajipegPegId = pegId AND gajipegPeriode BETWEEN '%s' AND '%s' ORDER BY gajipegId DESC LIMIT 0,1) AS gaji      
   FROM 
      pub_pegawai 
	    LEFT JOIN sdm_ref_master_gaji ON (mstgajiPegId = pegId)
   WHERE 
      mstgajiIsAktif='Ya'
      ) a
      ";
      
$sql['get_komponen_by_id'] = "
SELECT 
      kompgajidtId as id,
	    kompgajidtKode as kode,
	    kompgajidtNama as nama
   FROM 
      sdm_ref_komponen_gaji_detail
	    JOIN sdm_komponen_gaji_pegawai_detail ON (kompgajipegdtKompgajidtrId = kompgajidtId)
   WHERE
      kompgajipegdtPegId='%s'
";

$sql['cek_data'] = "
SELECT 
      pegrekId
   FROM 
      pub_pegawai_rekening
   WHERE
      pegrekPegId='%s'
";


//===DO===
$sql['do_add_absen_manual'] ="
   INSERT INTO sdm_absensi
      (absensiPegKodeGateAccess,absensiPegNama,absensiTglMasuk,absensiTglKeluar,absensiIsManual,absensiAlasan)
   VALUES 
      ('%s', '%s', '%s','%s',1, '%s')";

$sql['do_update_absen_manual'] ="
   UPDATE sdm_absensi
   SET
      absensiPegKodeGateAccess='%s',
      absensiPegNama='%s',
      absensiTglMasuk='%s',
      absensiTglKeluar='%s',
      absensiAlasan='%s'
   WHERE
      absensiId='%s'   
";

$sql['do_delete_absen_manual'] ="
   DELETE FROM sdm_absensi
   WHERE absensiId='%s'   
";  
      
$sql['do_up_status_by_array_id'] ="
   UPDATE 
      sdm_gaji_pegawai
   SET
      gajipegStatus = '1'
   WHERE 
      gajipegPegId IN ('%s') AND EXTRACT(YEAR_MONTH FROM gajipegPeriode) IN ('%s')
";

$sql['do_up_status'] ="
   UPDATE 
      sdm_gaji_pegawai
   SET
      gajipegStatus = '1'
   WHERE 
      gajipegPegId ='%s' AND EXTRACT(YEAR_MONTH FROM gajipegPeriode) ='%s'
";

$sql['update_pendapatan_pegawai']="
UPDATE 
  sdm_pendapatan_lain 
SET 
  pndptnlainGajiPegId = '%s' 
WHERE 
  pndptnlainPegId = '%s' and pndptnlainGajiPegId is NULL
";

$sql['do_update_data'] = "
   UPDATE 
      sdm_ref_master_gaji
   SET
      mstgajiIsCash = '%s',
      mstgajiTanggalGaji = '%s',
      mstgajiIsAktif = '%s'
   WHERE 
      mstgajiPegId = '%s'";

$sql['do_add_data_2'] = "
   INSERT INTO 
      pub_pegawai_rekening
      (pegrekPegId, pegrekBankId, pegrekRekening, pegrekCreationDate, pegrekUserId)
   VALUES 
      (%s, %s, '%s', now(), %s)
";

$sql['do_update_data_2'] = "
   UPDATE 
      pub_pegawai_rekening
   SET
      pegrekRekening = '%s',
      pegrekBankId = '%s',
      pegrekLastUpdate = now(),
      pegrekUserId = '%s'
   WHERE 
      pegrekPegId = '%s'";
      
$sql['do_add_komponen'] ="
   INSERT INTO 
      sdm_komponen_gaji_pegawai_detail
      (kompgajipegdtPegId, kompgajipegdtKompgajidtrId,kompgajipegdtTanggal)
   VALUES 
      (%s, %s, '%s')";

$sql['do_delete_data'] = "
   DELETE FROM 
     sdm_gaji_pegawai
   WHERE 
      gajipegPegId ='%s' AND EXTRACT(YEAR_MONTH FROM gajipegPeriode) = '%s'
";

/*$sql['do_delete_data_by_array_id'] = "
   DELETE FROM 
      biodata_pegawai
   WHERE 
      bdtpegId IN ('%s')";
*/
$sql['do_delete_data_by_array_id'] = "
   DELETE FROM 
      sdm_gaji_pegawai
   WHERE 
      gajipegPegId IN ('%s') AND EXTRACT(YEAR_MONTH FROM gajipegPeriode) IN ('%s')
";
      
$sql['do_delete_komponen'] = "
   DELETE FROM 
      sdm_komponen_gaji_pegawai_detail
   WHERE 
      kompgajipegdtPegId=%s
";

$sql['get_hari_libur'] = "
SELECT 
   hariliburTgl as tanggal
FROM 
   sdm_ref_hari_libur
";
?>
