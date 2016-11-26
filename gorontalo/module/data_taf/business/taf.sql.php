<?php
//===GET===
$sql['get_count1'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   pub_pegawai
 %s
   GROUP BY pegId
";   

$sql['get_data1']="
SELECT 
   pegId as 'id',
   pegKodeResmi as 'nip',
   pegNama as 'nama'
FROM 
   pub_pegawai
%s
ORDER BY 
   pegKodeResmi
LIMIT %s,%s
"; 

$sql['get_count2'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   sdm_taf
WHERE
   tafPegId = %s
GROUP BY tafId
";   

$sql['get_data2']="
SELECT 
   tafId as 'id',
   tafNo as 'no',
   jnstafNama as 'tipe',
   tafTglPengajuan as 'tgl_aju',
   tafTotalHariKeseluruhan as 'total_hari',
   tafTotalAnggaran as 'total_anggaran',
   tafStatusSpv as 'supv_status',
   tafTglStatusSpv as 'supv_tgl',
   tafStatusFin as 'fin_status',
   tafTglStatusFin as 'fin_tgl',
   tafStatusHRD as 'hrd_status',
   tafTglStatusHRD as 'hrd_tgl'
FROM 
   sdm_taf
   LEFT JOIN sdm_ref_jenis_taf ON (tafJnstafId=jnstafId)
WHERE
   tafPegId = %s %s
GROUP BY tafId
ORDER BY tafNo
LIMIT %s,%s
"; 

$sql['get_data_by_id']="
SELECT 
   pegId as 'id',
   pegKodeResmi as 'nip',
   pegNama as 'nama',
   pegAlamat as 'alamat',
   pegFoto as 'foto'
FROM 
   pub_pegawai
WHERE 
   pegId = %s
";

$sql['get_detail_pegawai_by_id']="
SELECT DISTINCT
   pegId as 'id',
   pegKodeResmi as 'nip_pegawai',
   pegNama as 'nama_pegawai',
   pegKelamin as 'jns_kelamin',
   statnkhNama as 'status_nikah',
   jnspegrNama as 'jns_pegawai',
   jabstrukrNama as 'jabatan_struktural',
   jabstrukrNama as 'job_title',
   satkerNama as 'division',
   pktgolrTingkat as tingkat,
   pktgolrId as grade
FROM 
   pub_pegawai
   LEFT JOIN pub_ref_status_nikah ON statnkhId = pegStatnikahId
   LEFT JOIN sdm_ref_jenis_pegawai ON jnspegrId = pegJnspegrId
   LEFT JOIN sdm_jabatan_struktural ON jbtnPegKode = pegId
   LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId = jbtnJabstrukrId
   LEFT JOIN sdm_satuan_kerja_pegawai ON pegId=satkerpegPegId
   LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
   LEFT JOIN sdm_pangkat_golongan ON (pktgolPegKode=pegId)
   LEFT JOIN sdm_ref_pangkat_golongan ON (pktgolPktgolrId=pktgolrId)
WHERE 
   pegId = %s
";

$sql['get_data_taf_det']="
SELECT 
   *
FROM 
   sdm_taf
   INNER JOIN sdm_ref_jenis_taf ON (tafJnstafId=jnstafId)
WHERE
	tafId = '%s'
";

$sql['get_travel_by_taf_id']="
SELECT 
   taftujuanId as id,
   taftujuanTglAwal as tgl_awal,
   taftujuanTglAkhir as tgl_akhir,
   taftujuanTotalHari as total_hari,
   
   tujuanId as tujuan_id,
   tujuanNama as tujuan,
   tujuanKode as kode_tujuan
FROM 
   sdm_taf_tujuan
   INNER JOIN sdm_ref_tujuan ON (taftujuanTujuanId=tujuanId)
WHERE
	 taftujuanTafId = '%s'
";

$sql['get_transport_by_taf_id']="
SELECT 
   taftranspId as id,
   taftranspTglAwal as tgl_awal,
   taftranspTglAkhir as tgl_akhir,
   taftranspJamAwal as jam_awal,
   taftranspJamAkhir as jam_akhir,
   taftranspNomor as nama,
   taftranspAnggaran as anggaran,
   taftranspCatatan as note,
   
   jnstranspId as jenis_id,
   jnstranspNama as jenis,
   
   tujuanId as tujuan_id,
   tujuanNama as tujuan,
   tujuanKode as kode_tujuan
FROM 
   sdm_taf_transportasi
   INNER JOIN sdm_ref_jenis_transportasi ON (taftranspJnstranspId=jnstranspId)
   INNER JOIN sdm_ref_tujuan ON (taftranspTujuanId=tujuanId)
WHERE
	 taftranspTafId = '%s'
";

$sql['get_allowance_by_taf_id']="
SELECT 
   tafanggaranId as id,
   tafanggaranTotalHari as total_hari,
   tafanggaranAnggaran as nilai,
   tafanggaranTotalAnggaran as total,
   tafanggaranCatatan as note,
   
   tujuanId as tujuan_id,
   tujuanNama as tujuan,
   tujuanKode as kode_tujuan,
   
   kbjkntafId as kebijakan_id,
   kbjkntafAnggaran as nilai_max,
   kbjkntafOnBillStatus as on_bill,
   kbjkntafOnBillWithLimitStatus as on_bill_limit,
   kbjkntafPackageStatus as paket,
   
   desktafId as dekripsi_id,
   desktafNama as deskripsi,
   
   currId as curr_id,
   currCode as currency,
   currNama as nama_curr
FROM 
   sdm_taf_anggaran
   INNER JOIN sdm_taf_tujuan ON (tafanggaranTafTujuanId=tafTujuanId)
   INNER JOIN sdm_ref_tujuan ON (taftujuanTujuanId=tujuanId)
   INNER JOIN sdm_kebijakan_taf ON (tafanggaranKbjkntafId=kbjkntafId)
   INNER JOIN sdm_ref_deskripsi_taf ON (kbjkntafDesktafId=desktafId)
   INNER JOIN sdm_ref_currency ON (tafanggaranCurrId=currId)
WHERE
	 tafanggaranTafId = '%s'
ORDER BY tafanggaranTafTujuanId
";

$sql['get_budget_by_taf_id']="
SELECT 
   tafbudgetId as id,
   tafbudgetBulan as periode_bulan,
   tafbudgetThnAnggaran as periode_tahun,
   concat(tafbudgetBulan,'-',tafbudgetThnAnggaran) as periode,
   tafbudgetAnggaran as anggaran,
   
   budgetId as budget_id,
   budgetKode as kode_budget,
   budgetNama as deskripsi
FROM 
   sdm_taf_budget
   INNER JOIN finansi_bg_ref_budget ON (budgetId=tafbudgetBudgetId)
WHERE
	 tafbudgetTafId = '%s'
";



$sql['get_balance_taf_left']="
SELECT
  (balancetafTotal - balancetafDiambil) AS 'data_balance'
FROM
  sdm_taf_balance
WHERE
  balancetafPegId = '%s'
";

//List
$sql['get_jenis_taf']="
SELECT 
   jnstafId as id,
   jnstafNama as name
FROM
   sdm_ref_jenis_taf
";

$sql['get_zona_by_jenis_taf']="
SELECT 
   zonatujuanId as id,
   zonatujuanNama as name
FROM
   sdm_ref_zona_tujuan
WHERE
   zonatujuanjnstafId=%s
";

$sql['get_kebijakan']="
SELECT
   kbjknTafId as kebijakan_id, 
   desktafNama as deskripsi,
   kbjkntafAnggaran as allowance,
   currId as curr_id,
   currCode as currency
FROM
   sdm_kebijakan_taf
   INNER JOIN sdm_ref_deskripsi_taf ON kbjkntafDesktafId=desktafId
   INNER JOIN sdm_ref_zona_tujuan ON (kbjkntafZonatujuanId=zonatujuanId)
   INNER JOIN sdm_ref_jenis_taf ON (zonatujuanJnstafId=jnstafId)
   INNER JOIN sdm_ref_currency ON (jnstafCurrId=currId)
WHERE
   kbjkntafPktgolrId=%s AND
   kbjkntafZonatujuanId=%s
   
";

$sql['get_detail_zona']="
SELECT 
   zonatujuanJnstafId as jenis_id,
   zonatujuanId as zona_id,
   tujuanId as kota_id
FROM
   sdm_ref_tujuan 
   INNER JOIN sdm_ref_zona_tujuan ON (tujuanZonatujuanId=zonatujuanId)
";

/* Combobox */
$sql['get_combo_tujuan']="
SELECT 
   tujuanId as id,
   tujuanNama as name
FROM
   sdm_ref_tujuan
   INNER JOIN sdm_ref_zona_tujuan ON (tujuanZonatujuanId=zonatujuanId)
WHERE
   zonatujuanJnstafId='%s'
ORDER BY tujuanNama ASC
";

$sql['get_combo_tipe']="
SELECT 
   jnstafId as id,
   jnstafNama as name
FROM
   sdm_ref_jenis_taf
";

$sql['get_combo_tipe_transportasi']="
SELECT 
   jnstranspId as id,
   jnstranspNama as name
FROM
   sdm_ref_jenis_transportasi
";

$sql['get_combo_jenis_klaim']="
SELECT 
   jnsklaimId as id,
   jnsklaimNama as name
FROM
   sdm_ref_jenis_klaim
ORDER BY jnsklaimNama ASC
";

$sql['get_combo_budget']="
SELECT 
   budgetId as id,
   concat(budgetKode,' # [',budgetNama,']') as name
FROM
   finansi_bg_ref_budget
ORDER BY budgetKodeSistem ASC
";

$sql['get_jenis_taf_by_id']="
SELECT 
   jnstafId as id,
   jnstafNama as name
FROM
   sdm_ref_jenis_taf
WHERE
   jnstafId=%s
ORDER BY jnstafNama ASC
";

$sql['get_curr_by_jenis_taf_id']="
SELECT 
   jnstafId as id,
   jnstafNama as nama,
   currCode as name
FROM
   sdm_ref_jenis_taf
   INNER JOIN sdm_ref_currency ON (jnstafcurrId=currId)
WHERE
   jnstafId=%s
";

$sql['cek_nmr_taf']="
SELECT 
   tafNo as 'no'
FROM
   sdm_taf
WHERE
   tafNo = %s
";

$sql['get_tahun_no']="
SELECT 
   substr(tafNo,6,4) as 'tahun'
FROM
   sdm_taf
ORDER BY
   tafNo ASC
";

$sql['get_no_baru']="
SELECT 
   max(substr(tafNo,1,4))+1 as 'nmr'
FROM
   sdm_taf
WHERE
   substr(tafNo,6,4) = '%s'
ORDER BY
   tafNo ASC
";

$sql['get_last_id']="
SELECT 
   max(tafId) as 'last_id'
FROM
   sdm_taf
";

$sql['get_last_id_tujuan']="
SELECT 
   max(taftujuanId) as 'last_id'
FROM
   sdm_taf_tujuan
";

// DO-----------
$sql['do_add'] = "
INSERT INTO sdm_taf(
        tafNo,
        tafPegId,
        tafJnstafId,
        tafAlasan,
        tafTglPengajuan,         
        tafTotalHariKeseluruhan,         
        tafTotalAnggaran,         
        tafTotalAnggaranTransportasi,         
        tafTotalAnggaranBudget,         
        tafTglSubmit,
        tafUserId
      )
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s',now(),'%s')  
";

$sql['do_add_tujuan'] = "
INSERT INTO sdm_taf_tujuan(
        taftujuanTafId,
        taftujuanTujuanId,
        taftujuanTglAwal,
        taftujuanTglAkhir,
        taftujuanTotalHari,         
        taftujuanUserId
      )
VALUES('%s','%s','%s','%s','%s','%s')  
";

$sql['do_add_anggaran'] = "
INSERT INTO sdm_taf_anggaran (
        tafanggaranTafId,
        tafanggaranTafTujuanId,
        tafanggaranKbjkntafId,
        tafanggaranTotalHari,
        tafanggaranAnggaran,
        tafanggaranCurrId,
        tafanggaranTotalAnggaran,
        tafanggaranCatatan,
        tafanggaranUserId
      )
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s')  
";
                        
$sql['do_add_transportasi'] = "
INSERT INTO sdm_taf_transportasi (
        taftranspJnstranspId,
        taftranspTafId,
        taftranspTglAwal,
        taftranspTglAkhir,
        taftranspTujuanId,
        taftranspNomor,
        taftranspJamAwal,
        taftranspJamAkhir,
        taftranspAnggaran,
        taftranspCatatan,
        taftranspUserId
      )
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')  
";

$sql['do_add_budget'] = "
INSERT INTO sdm_taf_budget (
        tafbudgetTafId,
        tafbudgetBudgetId,
        tafbudgetBulan,
        tafbudgetThnAnggaran,
        tafbudgetAnggaran,
        tafbudgetUserId
      )
VALUES('%s','%s','%s','%s','%s','%s')  
";


$sql['do_update'] = "
UPDATE sdm_taf
SET 
	tafNo='%s',
  tafPegId='%s',
  tafJnstafId='%s',
  tafAlasan='%s',
  tafTglPengajuan='%s',         
  tafTotalHariKeseluruhan='%s',         
  tafTotalAnggaran='%s',         
  tafTotalAnggaranTransportasi='%s',         
  tafTotalAnggaranBudget='%s',         
  tafTglSubmit=now(),
  tafUserId='%s'
WHERE 
	tafId = '%s'
";

$sql['do_update_tujuan'] = "
UPDATE sdm_taf_tujuan
SET 
	taftujuanTafId='%s',
  taftujuanTujuanId='%s',
  taftujuanTglAwal='%s',
  taftujuanTglAkhir='%s',
  taftujuanTotalHari='%s',         
  taftujuanUserId='%s'
WHERE 
	taftujuanId = '%s'
";

$sql['do_update_anggaran'] = "
UPDATE sdm_taf_anggaran
SET 
	tafanggaranTafId='%s',
  tafanggaranTafTujuanId='%s',
  tafanggaranKbjkntafId='%s',
  tafanggaranTotalHari='%s',
  tafanggaranAnggaran='%s',
  tafanggaranCurrId='%s',
  tafanggaranTotalAnggaran='%s',
  tafanggaranCatatan='%s',
  tafanggaranUserId='%s'
WHERE 
	tafanggaranId = '%s'
";

$sql['do_update_anggaran_approval'] = "
UPDATE sdm_taf_anggaran
SET 
	tafanggaranTafId='%s',
  tafanggaranKbjkntafId='%s',
  tafanggaranTotalHari='%s',
  tafanggaranAnggaran='%s',
  tafanggaranCurrId='%s',
  tafanggaranTotalAnggaran='%s',
  tafanggaranCatatan='%s',
  tafanggaranUserId='%s'
WHERE 
	tafanggaranId = '%s'
";

$sql['do_update_transportasi'] = "
UPDATE sdm_taf_transportasi
SET 
	taftranspJnstranspId='%s',
  taftranspTafId='%s',
  taftranspTglAwal='%s',
  taftranspTglAkhir='%s',
  taftranspTujuanId='%s',
  taftranspNomor='%s',
  taftranspJamAwal='%s',
  taftranspJamAkhir='%s',
  taftranspAnggaran='%s',
  taftranspCatatan='%s',
  taftranspUserId='%s'
WHERE 
	taftranspId = '%s'
";

$sql['do_update_budget'] = "
UPDATE sdm_taf_budget
SET 
	tafbudgetTafId='%s',
  tafbudgetBudgetId='%s',
  tafbudgetBulan='%s',
  tafbudgetThnAnggaran='%s',
  tafbudgetAnggaran='%s',
  tafbudgetUserId='%s'
WHERE 
	tafbudgetId = '%s'
";

$sql['do_update_approval_supervisor'] = "
UPDATE sdm_taf
SET 
	tafStatusSpv='%s',
	tafTglStatusSpv='%s'
WHERE 
	tafId = '%s'
";

$sql['do_delete'] = "
DELETE FROM
   sdm_taf
WHERE 
   tafId = %s  
";

$sql['do_delete_allowance'] = "
DELETE FROM
   sdm_taf_anggaran
WHERE 
   tafanggaranTafId = %s  
";

$sql['do_delete_tujuan'] = "
DELETE FROM
   sdm_taf_tujuan
WHERE 
   taftujuanTafId = %s  
";

$sql['do_delete_transportasi'] = "
DELETE FROM
   sdm_taf_transportasi
WHERE 
   taftranspTafId = %s  
";

$sql['do_delete_budget'] = "
DELETE FROM
   sdm_taf_budget
WHERE 
   tafbudgetTafId = %s  
";

$sql['do_delete_allowance_massal'] = "
DELETE FROM
   sdm_taf_anggaran
WHERE 
   tafanggaranTafId = %s AND
   NOT (tafanggaranTaftujuanId IN (%s))  
";

$sql['do_delete_travel_massal'] = "
DELETE FROM
   sdm_taf_tujuan
WHERE 
   taftujuanTafId = %s AND
   NOT (taftujuanId IN (%s))  
";

$sql['do_delete_transport_massal'] = "
DELETE FROM
   sdm_taf_transportasi
WHERE 
   taftranspTafId = %s AND
   NOT (taftranspId IN (%s))  
";

$sql['do_delete_budget_massal'] = "
DELETE FROM
   sdm_taf_budget
WHERE 
   tafbudgetTafId = %s AND
   NOT (tafbudgetId IN (%s))  
";

$sql['get_balance_taf_by_peg_id']="
SELECT 
   balancetafId as 'per_id'
FROM
   sdm_taf_balance
LEFT JOIN sdm_taf ON tafPegId = balancetafPegId
WHERE
   balancetafPegId = '%s'
   AND balancetafStatus = 'Active'
ORDER BY
   tafId ASC
LIMIT
  0,1
";

$sql['do_update_balance_taf_diambil'] = "
UPDATE
  sdm_taf_balance 
SET
  balancetafDiambil = IFNULL(balancetafDiambil,0) + %s,
  balancetafTotal = balancetafTotal
WHERE 
  balancetafPegId	= %s
  AND balancetafId = %s
  AND balancetafStatus = 'Active'
";

$sql['do_update_balance_taf_diambil_tambah_by_id'] = "
UPDATE
  sdm_taf_balance 
SET
  balancetafDiambil = balancetafDiambil + %s,
  balancetafTotal = balancetafTotal
WHERE 
  balancetafPegId	= %s
  AND balancetafId = %s
  AND balancetafStatus = 'Active'
";

$sql['do_update_balance_taf_diambil_kurang_by_id'] = "
UPDATE
  sdm_taf_balance 
SET
  balancetafDiambil = balancetafDiambil - %s,
  balancetafTotal = balancetafTotal
WHERE 
  balancetafPegId	= %s
  AND balancetafId = %s
  AND balancetafStatus = 'Active'
";

?>
