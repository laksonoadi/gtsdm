<?php
$sql['gtSDM_get_data_pegawai'] = "
SELECT
   peg.pegNip AS bdtpegNip,
   peg.pegNidn AS bdtpegNidn,
   peg.pegNama AS bdtpegNama,
   peg.pegAlamat AS bdtpegAlamat,
   peg.pegNoTelp AS bdtpegNoTelp,
   fungr.jabfungrKompGajiDetId AS pegdtKompgajidtId1,
   strukr.jabstrukrKompGajiDetId AS pegdtKompgajidtId2,
   gapok.gapokKompGajiDetId AS pegdtKompgajidtId3
FROM
   pg_pegawai peg
   LEFT JOIN pg_jabatan_fungsional fung ON fung.jbtnPegKode = peg.pegKode AND fung.jbtnStatus = 'Aktif'
   LEFT JOIN pg_jabatan_fungsional_ref fungr ON fung.jbtnJabfungrId = fungr.jabfungrId
   LEFT JOIN pg_jabatan_struktural struk ON struk.jbtnPegKode = peg.pegKode AND struk.jbtnStatus = 'Aktif'
   LEFT JOIN pg_jabatan_struktural_ref strukr ON strukr.jabstrukrId = struk.jbtnJabstrukrId
   LEFT JOIN pg_pangkat_golongan gol ON gol.pktgolPegKode = peg.pegKode AND gol.pktgolStatus = 'Aktif'
   LEFT JOIN pg_pangkat_golongan_ref golr ON golr.pktgolrId = gol.pktgolPktgolrId
   LEFT JOIN pg_gaji_pokok_ref gapok ON gapok.gapokPktgolrId = golr.pktgolrId AND gapok.gapokMasaKerja = (year(now()) - year(peg.pegPnsTmt))
";

$sql['get_last_transaksi_gaji']="
	SELECT
		MAX(transId) as last_id
	FROM
		transaksi
	WHERE
		transReferensi LIKE '%PAYROLL%'
";

$sql['insert_transaksi_gaji_to_gtfinansi'] = "
INSERT INTO transaksi (
		transTtId,
		transTransjenId,
		transUnitkerjaId,
		transTppId,
		transThanggarId,
		transReferensi,
		transUserId,
		transTanggal,
		transTanggalEntri,
		transDueDate,
		transCatatan,
		transNilai,
		transPenanggungJawabNama,
		transIsJurnal
	) VALUES(
		(SELECT ttId FROM transaksi_tipe_ref WHERE UPPER(ttNamaTransaksi)='PENGELUARAN' AND ttIsAktif='Y'),
		(SELECT transjenId FROM transaksi_jenis_ref WHERE UPPER(transjenNama)='PAYROLL'),
		'%s', 	#Unit Kerja
		(SELECT tppId FROM tahun_pembukuan_periode WHERE tppIsBukaBuku='Y'),
		(SELECT thanggarId FROM tahun_anggaran WHERE thanggarIsAktif='Y'),
		'%s', 	#No Referensi
		1, 	#UserId nobody
		NOW(),
		NOW(),
		DATE(ADDDATE(NOW(),30)),
		'%s', 	#Catatan
		'%s', 	#Nilai
		'%s', 	#Penanggung Jawab
		'T'
	)";
	
$sql['insert_transaksi_gaji_detail_to_gtfinansi'] = "
INSERT INTO transaksi_detail_gaji (
		transdtgajiTransId,
		transdtgajiNIP,
		transdtgajiNama,
		transdtgajiTanggalGaji,
		transdtgajiTanggalPeriodeGaji,
		transdtgajiNominalGaji
	) VALUES(
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		'%s'
	)";

$sql['get_sql_generate_number'] = "
	SELECT 
		formatNumberFormula 
	FROM 
		finansi_ref_formula_number 
	WHERE 
		formatNumberCode = '%s' 
	AND 
		formatNumberIsAktif = 'Y' 
	LIMIT 0,1
";

?>
