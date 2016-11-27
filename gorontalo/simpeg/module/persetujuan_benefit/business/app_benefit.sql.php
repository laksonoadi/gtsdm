<?php
//===GET===
$sql['get_count2'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   sdm_benefit
WHERE
   benefitStatus = 'request'
   %s
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
   a.benefitUserId as 'user_id',
   b.pegNama as 'peg_nama',
	 b.pegKodeResmi as 'peg_nip',
	 i.satkerNama as 'satker'
FROM 
   sdm_benefit a
   LEFT JOIN pub_pegawai b ON b.pegId = a.benefitPegId
   LEFT JOIN sdm_benefit_balance c ON c.balancebenefitId = a.benefitBalancebenefitId
   LEFT JOIN sdm_ref_benefit d ON d.benefitId = a.benefitBenefitId
   LEFT JOIN gtfw_user e ON e.UserId = a.benefitUserId
   LEFT JOIN sdm_benefit_klaim f ON f.klaimbenefitBenefitId = a.benefitId
   LEFT JOIN sdm_ref_jenis_klaim g ON g.jnsklaimId = f.klaimbenefitJnsklaimId
   LEFT JOIN sdm_satuan_kerja_pegawai h ON h.satkerpegPegId = b.pegId
   LEFT JOIN pub_satuan_kerja i ON i.satkerId = h.satkerpegSatkerId
WHERE
  a.benefitStatus = 'request'
  %s
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
   pegFoto as 'foto',
   satkerNama as 'satker',
   pegdtDirSpv as 'id_spv',
   pegdtMor as 'id_mor'
FROM 
   pub_pegawai
   LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId = pegId
   LEFT JOIN pub_satuan_kerja ON satkerId = satkerpegSatkerId
   LEFT JOIN sdm_pegawai_detail ON pegdtPegId = pegId
WHERE 
   pegId = %s
";

$sql['get_spv_by_spv_id']="
SELECT 
   a.pegId as 'id',
   a.pegNama as 'spv',
   b.pegdtDirSpv as 'id_spv'
FROM 
   pub_pegawai a
LEFT JOIN sdm_pegawai_detail b ON b.pegdtPegId = a.pegId
WHERE 
   pegId = %s
";

$sql['get_mor_by_mor_id']="
SELECT 
   a.pegId as 'id',
   a.pegNama as 'mor',
   b.pegdtMor as 'id_mor'
FROM 
   pub_pegawai a
LEFT JOIN sdm_pegawai_detail b ON b.pegdtPegId = a.pegId
WHERE 
   pegId = %s
";

$sql['get_data_app_benefit_det']="
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
   a.benefitUserId as 'user_id',
   b.pegNama as 'peg_nama',
	 b.pegKodeResmi as 'peg_nip',
	 i.satkerNama as 'satker'
FROM 
   sdm_benefit a
   LEFT JOIN pub_pegawai b ON b.pegId = a.benefitPegId
   LEFT JOIN sdm_benefit_balance c ON c.balancebenefitId = a.benefitBalancebenefitId
   LEFT JOIN sdm_ref_benefit d ON d.benefitId = a.benefitBenefitId
   LEFT JOIN gtfw_user e ON e.UserId = a.benefitUserId
   LEFT JOIN sdm_benefit_klaim f ON f.klaimbenefitBenefitId = a.benefitId
   LEFT JOIN sdm_ref_jenis_klaim g ON g.jnsklaimId = f.klaimbenefitJnsklaimId
   LEFT JOIN sdm_satuan_kerja_pegawai h ON h.satkerpegPegId = b.pegId
   LEFT JOIN pub_satuan_kerja i ON i.satkerId = h.satkerpegSatkerId
WHERE
	a.benefitId = '%s'

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

$sql['do_update_app_benefit'] = "
UPDATE sdm_benefit
SET 
	benefitStatus = '%s',
	benefitTglStatus = '%s'
WHERE 
	benefitId = '%s'
";

?>
