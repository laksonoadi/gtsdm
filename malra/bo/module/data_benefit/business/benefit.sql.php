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
   sdm_benefit
WHERE
   benefitPegId = %s %s
   GROUP BY benefitId
";   

$sql['get_data2']="
SELECT 
   a.benefitId as 'id',
   a.benefitNo as 'no',
   a.benefitBalancebenefitId as 'balancebenefit_id',
   a.benefitPegId as 'peg_id',
   a.benefitNamaPasien as 'nama_pasien',
   a.benefitRelasiPasien as 'relasi_pasien',
   a.benefitBenefitId as 'benefit_id',
   d.benefitNama as 'tipe_klaim',
   a.benefitTgl as 'tgl_benefit',
   a.benefitTglSubmit as 'tgl_submit',
   a.benefitTempat as 'tempat',
   a.benefitTotalKlaim as 'total_klaim',
   a.benefitAlasan as 'alasan',
   a.benefitTglKlaim as 'tgl_klaim',
   a.benefitStatus as 'status',
   a.benefitTglStatus as 'tgl_status',
   a.benefitUserId as 'user_id'
FROM 
   sdm_benefit a
   LEFT JOIN pub_pegawai b ON b.pegId = a.benefitPegId
   LEFT JOIN sdm_benefit_balance c ON c.balancebenefitId = a.benefitBalancebenefitId
   LEFT JOIN sdm_ref_benefit d ON d.benefitId = a.benefitBenefitId
   LEFT JOIN gtfw_user e ON e.UserId = a.benefitUserId
   LEFT JOIN sdm_benefit_klaim f ON f.klaimbenefitBenefitId = a.benefitId
   LEFT JOIN sdm_ref_jenis_klaim g ON g.jnsklaimId = f.klaimbenefitJnsklaimId
WHERE
  a.benefitPegId = %s %s
GROUP BY
  a.benefitId
ORDER BY 
  a.benefitNo
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
SELECT 
   pegId as 'id',
   pegKodeResmi as 'nip',
   pegNama as 'nama_pegawai',
   pegKelamin as 'jns_kelamin',
   statnkhNama as 'status_nikah',
   jnspegrNama as 'jns_pegawai',
   jabstrukrNama as 'jabatan_struktural'
FROM 
   pub_pegawai
LEFT JOIN pub_ref_status_nikah ON statnkhId = pegStatnikahId
LEFT JOIN sdm_ref_jenis_pegawai ON jnspegrId = pegJnspegrId
LEFT JOIN sdm_jabatan_struktural ON jbtnPegKode = pegId
LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId = jbtnJabstrukrId
WHERE 
   pegId = %s
";

$sql['get_data_benefit_det']="
SELECT 
   a.benefitId as 'id',
   a.benefitNo as 'no',
   a.benefitBalancebenefitId as 'balancebenefit_id',
   a.benefitPegId as 'peg_id',
   a.benefitNamaPasien as 'nama_pasien',
   a.benefitRelasiPasien as 'relasi_pasien',
   a.benefitBenefitId as 'benefit_id',
   d.benefitNama as 'tipe_klaim',
   a.benefitTgl as 'tgl_benefit',
   a.benefitTglSubmit as 'tgl_submit',
   a.benefitTempat as 'tempat',
   a.benefitTotalKlaim as 'total_klaim',
   a.benefitAlasan as 'alasan',
   a.benefitTglKlaim as 'tgl_klaim',
   a.benefitStatus as 'status',
   a.benefitTglStatus as 'tgl_status',
   a.benefitUserId as 'user_id'
FROM 
   sdm_benefit a
   LEFT JOIN pub_pegawai b ON b.pegId = a.benefitPegId
   LEFT JOIN sdm_benefit_balance c ON c.balancebenefitId = a.benefitBalancebenefitId
   LEFT JOIN sdm_ref_benefit d ON d.benefitId = a.benefitBenefitId
   LEFT JOIN gtfw_user e ON e.UserId = a.benefitUserId
   LEFT JOIN sdm_benefit_klaim f ON f.klaimbenefitBenefitId = a.benefitId
   LEFT JOIN sdm_ref_jenis_klaim g ON g.jnsklaimId = f.klaimbenefitJnsklaimId
WHERE
	a.benefitId = '%s'
";

$sql['get_data_klaim_from_benefit_id']="
SELECT
  klaimbenefitId as 'id',
  klaimbenefitBenefitId as 'benefit_id',
  klaimbenefitJnsklaimId as 'jnsklaim_id',
  jnsklaimNama as 'tipe_klaim',
  klaimbenefitNilai as 'nilai_klaim',
  klaimbenefitNilaiDisetujui as 'nilai_klaim_disetujui',
  klaimbenefitFile as 'file_klaim'
FROM
  sdm_benefit_klaim
LEFT JOIN sdm_ref_jenis_klaim ON jnsklaimId = klaimbenefitJnsklaimId
WHERE
  klaimbenefitBenefitId = '%s'
ORDER BY 
  klaimbenefitId
";

$sql['get_balance_benefit_left']="
SELECT
  (balancebenefitTotal - balancebenefitDiambil) AS 'data_balance'
FROM
  sdm_benefit_balance
WHERE
  balancebenefitPegId = '%s'
";

/* Combobox */
$sql['get_combo_jenis_benefit']="
SELECT 
   benefitId as id,
   benefitNama as name
FROM
   sdm_ref_benefit
ORDER BY benefitNama ASC
";

$sql['get_combo_jenis_klaim']="
SELECT 
   jnsklaimId as id,
   jnsklaimNama as name
FROM
   sdm_ref_jenis_klaim
ORDER BY jnsklaimNama ASC
";

$sql['cek_nmr_benefit']="
SELECT 
   benefitNo as 'no'
FROM
   sdm_benefit
WHERE
   benefitNo = %s
";

$sql['get_tahun_no']="
SELECT 
   substr(benefitNo,6,4) as 'tahun'
FROM
   sdm_benefit
ORDER BY
   benefitNo ASC
";

$sql['get_no_baru']="
SELECT 
   max(substr(benefitNo,1,4))+1 as 'nmr'
FROM
   sdm_benefit
WHERE
   substr(benefitNo,6,4) = '%s'
ORDER BY
   benefitNo ASC
";

$sql['get_last_id']="
SELECT 
   max(benefitId) as 'last_id'
FROM
   sdm_benefit
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_benefit
   (benefitPegId,benefitNo,benefitPengajuan,benefitSubmit,benefitMulai,benefitSelesai,
    benefitTipebenefitId,benefitKrngiJthBenefitStatus,benefitAlasan,benefitStatus,benefitTglStatus,benefitTggjwbKerja,benefitPeggjwbSmntra,
    benefitPeggjwbSmntraKontak,benefitBenefitperId)
VALUES('%s','%s',now(),now(),'%s','%s','%s',
       '%s','%s','approved',now(),'%s','%s',
       '%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_benefit
SET 
	benefitpegId = '%s',
	benefitNo = '%s',
	benefitMulai = '%s',
	benefitSelesai = '%s',
	 benefitTipebenefitId = '%s',
	 benefitKrngiJthBenefitStatus = '%s',
	 benefitAlasan = '%s',
	 benefitStatus = '%s',
	benefitTglStatus = '%s',
	benefitTggjwbKerja = '%s',
	benefitPeggjwbSmntra = '%s',
  benefitPeggjwbSmntraKontak = '%s',
   benefitBenefitperId = '%s'
WHERE 
	benefitId = '%s'
";

$sql['do_delete'] = "
DELETE FROM
   sdm_benefit
WHERE 
   benefitId = %s  
";

$sql['get_balance_benefit_by_peg_id']="
SELECT 
   balancebenefitId as 'per_id'
FROM
   sdm_benefit_balance
LEFT JOIN sdm_benefit ON benefitPegId = balancebenefitPegId
WHERE
   balancebenefitPegId = '%s'
   AND balancebenefitStatus = 'Active'
ORDER BY
   benefitId ASC
LIMIT
  0,1
";

$sql['do_update_balance_benefit_diambil'] = "
UPDATE
  sdm_benefit_balance 
SET
  balancebenefitDiambil = IFNULL(balancebenefitDiambil,0) + %s,
  balancebenefitTotal = balancebenefitTotal - %s
WHERE 
  balancebenefitPegId	= %s
  AND balancebenefitId = %s
  AND balancebenefitStatus = 'Active'
";

$sql['do_update_balance_benefit_diambil_tambah_by_id'] = "
UPDATE
  sdm_benefit_balance 
SET
  balancebenefitDiambil = balancebenefitDiambil + %s,
  balancebenefitTotal = balancebenefitTotal - %s
WHERE 
  balancebenefitPegId	= %s
  AND balancebenefitId = %s
  AND balancebenefitStatus = 'Active'
";

$sql['do_update_balance_benefit_diambil_kurang_by_id'] = "
UPDATE
  sdm_benefit_balance 
SET
  balancebenefitDiambil = balancebenefitDiambil - %s,
  balancebenefitTotal = balancebenefitTotal + %s
WHERE 
  balancebenefitPegId	= %s
  AND balancebenefitId = %s
  AND balancebenefitStatus = 'Active'
";

?>
