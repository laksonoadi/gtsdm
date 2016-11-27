<?php
$sql['get_data_pegawai_duk'] = "
	SELECT 
		pegId as id,
		pegKodeResmi as nip,
		pegNama as nama,
		pegKelamin as jenis_kelamin,
		pegJnspegrId as jenis_pegawai,
		pegTglLahir as ttl
	FROM  
		pub_pegawai
	WHERE 
		pegKodeResmi = '%s'
";

$sql['add_data_pegawai_duk'] = "
	INSERT INTO pub_pegawai(
			pegKodeResmi,
			pegNama,
			pegKelamin,
			pegJnspegrId,
			pegTglLahir
		)VALUES(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
		);
";

$sql['update_data_pegawai_duk'] = "
	UPDATE 
		pub_pegawai 
	SET 
		pegKodeResmi = '%s',
		pegNama = '%s',
		pegKelamin = '%s',
		pegJnspegrId = '%s',
		pegTglLahir = '%s'
	WHERE 
		pegId = '%s'
";

$sql['get_pangkat_golongan_duk'] = "
	SELECT 
		pktgolId as id,
		sdm_pangkat_golongan.*
	FROM  
		sdm_pangkat_golongan
	WHERE 
		pktgolPktgolrId = '%s' AND pktgolPegKode='%s'
";

$sql['add_pangkat_golongan_duk'] = "
	INSERT INTO sdm_pangkat_golongan(
			pktgolPegKode,
			pktgolJnspegrId,
			pktgolPktgolrId,
			pktgolTmt,
			pktgolStatus
		)VALUES(
			'%s',
			'%s',
			'%s',
			'%s',
			'Aktif'
		);
";

$sql['update_pangkat_golongan_duk'] = "
	UPDATE 
		sdm_pangkat_golongan 
	SET 
		pktgolPegKode='%s',
		pktgolJnspegrId='%s',
		pktgolPktgolrId='%s',
		pktgolTmt='%s',
		pktgolStatus='Aktif'
	WHERE 
		pktgolId = '%s'
";

$sql['nonaktifkan_pangkat_golongan_duk'] = "
	UPDATE 
		sdm_pangkat_golongan 
	SET 
		pktgolStatus='Tidak Aktif'
	WHERE 
		pktgolPegKode = '%s'
";

$sql['get_jabatan_fungsional_duk'] = "
	SELECT 
		jbtnId as id,
		sdm_jabatan_fungsional.*
	FROM  
		sdm_jabatan_fungsional
	WHERE 
		jbtnJabfungrId = '%s' AND jbtnPegKode='%s'
";

$sql['add_jabatan_fungsional_duk'] = "
	INSERT INTO sdm_jabatan_fungsional(
			jbtnPegKode,
			jbtnPktGolId,
			jbtnJabfungrId,
			jbtnTglMulai,
			jbtnStatus
		)VALUES(
			'%s',
			'%s',
			'%s',
			'%s',
			'Aktif'
		);
";

$sql['update_jabatan_fungsional_duk'] = "
	UPDATE 
		sdm_jabatan_fungsional 
	SET 
		jbtnPegKode='%s',
		jbtnPktGolId='%s',
		jbtnJabfungrId='%s',
		jbtnTglMulai='%s',
		jbtnStatus='Aktif'
	WHERE 
		jbtnId = '%s'
";

$sql['nonaktifkan_jabatan_fungsional_duk'] = "
	UPDATE 
		sdm_jabatan_fungsional 
	SET 
		jbtnStatus='Tidak Aktif'
	WHERE 
		jbtnPegKode = '%s'
";

$sql['get_jabatan_struktural_duk'] = "
	SELECT 
		jbtnId as id,
		sdm_jabatan_struktural.*
	FROM  
		sdm_jabatan_struktural
	WHERE 
		jbtnJabstrukrId = '%s' AND jbtnPegKode='%s'
";

$sql['add_jabatan_struktural_duk'] = "
	INSERT INTO sdm_jabatan_struktural(
			jbtnPegKode,
			jbtnPktGolId,
			jbtnJabstrukrId,
			jbtnTglMulai,
			jbtnStatus
		)VALUES(
			'%s',
			'%s',
			'%s',
			'%s',
			'Aktif'
		);
";

$sql['update_jabatan_struktural_duk'] = "
	UPDATE 
		sdm_jabatan_struktural 
	SET 
		jbtnPegKode='%s',
		jbtnPktGolId='%s',
		jbtnJabstrukrId='%s',
		jbtnTglMulai='%s',
		jbtnStatus='Aktif'
	WHERE 
		jbtnId = '%s'
";

$sql['nonaktifkan_jabatan_struktural_duk'] = "
	UPDATE 
		sdm_jabatan_struktural 
	SET 
		jbtnStatus='Tidak Aktif'
	WHERE 
		jbtnPegKode = '%s'
";

$sql['get_masa_kerja_duk'] = "
	SELECT 
		mkId as id,
		sdm_masa_kerja_penyesuaian.*
	FROM  
		sdm_masa_kerja_penyesuaian
	WHERE 
		mkPegKode='%s'
	ORDER BY mkId DESC
";

$sql['add_masa_kerja_duk'] = "
	INSERT INTO sdm_masa_kerja_penyesuaian(
			mkPegKode,
			mkPenyesuaianTahun,
			mkPenyesuaianBulan
		)VALUES(
			'%s',
			'%s',
			'%s'
		);
";

$sql['get_pendidikan_duk'] = "
	SELECT 
		pddkId as id,
		sdm_pendidikan.*
	FROM  
		sdm_pendidikan
	WHERE 
		pddkTkpddkrId = '%s' AND pddkPegKode='%s'
";

$sql['add_pendidikan_duk'] = "
	INSERT INTO sdm_pendidikan(
			pddkPegKode,
			pddkTkpddkrId,
			pddkInstitusi,
			pddkJurusan,
			pddkThnLulus,
			pddkStatusTamat
		)VALUES(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'Selesai'
		);
";

$sql['update_pendidikan_duk'] = "
	UPDATE 
		sdm_pendidikan 
	SET 
		pddkPegKode='%s',
		pddkTkpddkrId='%s',
		pddkInstitusi='%s',
		pddkJurusan='%s',
		pddkThnLulus='%s',
		pddkStatusTamat='Selesai'
	WHERE 
		pddkId = '%s'
";

$sql['get_unit_kerja_duk'] = "
	SELECT 
		satkerpegId as id,
		sdm_satuan_kerja_pegawai.*
	FROM  
		sdm_satuan_kerja_pegawai
	WHERE 
		satkerpegSatkerId = '%s' AND satkerpegPegId='%s'
";

$sql['add_unit_kerja_duk'] = "
	INSERT INTO sdm_satuan_kerja_pegawai(
			satkerpegPegId,
			satkerpegSatkerId,
			satkerpegTmt,
			satkerpegAktif
		)VALUES(
			'%s',
			'%s',
			'%s',
			'Aktif'
		);
";

$sql['update_unit_kerja_duk'] = "
	UPDATE 
		sdm_satuan_kerja_pegawai 
	SET 
		satkerpegPegId='%s',
		satkerpegSatkerId='%s',
		satkerpegTmt='%s',
		satkerpegAktif='Aktif'
	WHERE 
		satkerpegId = '%s'
";

$sql['nonaktifkan_unit_kerja_duk'] = "
	UPDATE 
		sdm_satuan_kerja_pegawai 
	SET 
		satkerpegAktif='Tidak Aktif'
	WHERE 
		satkerpegPegId = '%s'
";

$sql['add_unit_kerja_lengkap'] = "
	INSERT INTO sdm_satuan_kerja_pegawai(
			satkerpegPegId,
			satkerpegSatkerId,
			satkerpegTmt,
			satkerpegPjbSk,
			satkerpegNoSk,
			satkerpegTglSk,
			satkerpegAktif
		)VALUES(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
		);
";

$sql['update_unit_kerja_lengkap'] = "
	UPDATE 
		sdm_satuan_kerja_pegawai 
	SET 
		satkerpegPegId='%s',
		satkerpegSatkerId='%s',
		satkerpegTmt='%s',
		satkerpegPjbSk='%s',
		satkerpegNoSk='%s',
		satkerpegTglSk='%s',
		satkerpegAktif='%s'
	WHERE 
		satkerpegId='%s'
";

$sql['add_pangkat_golongan_lengkap'] = "
	INSERT INTO sdm_pangkat_golongan(
			pktgolPegKode,
			pktgolPktgolrId,
			pktgolTmt,
			pktgolNaikPktYad,
			pktgolPejabatSk,
			pktgolNoSk,
			pktgolTglSk,
			pktgolDsrPeraturan,
			pktgolStatus
		)VALUES(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
		);
";

$sql['update_pangkat_golongan_lengkap'] = "
	UPDATE 
		sdm_pangkat_golongan 
	SET 
		pktgolPegKode='%s',
		pktgolPktgolrId='%s',
		pktgolTmt='%s',
		pktgolNaikPktYad='%s',
		pktgolPejabatSk='%s',
		pktgolNoSk='%s',
		pktgolTglSk='%s',
		pktgolDsrPeraturan='%s',
		pktgolStatus='%s'
	WHERE 
		pktgolId='%s'
";

$sql['add_jabatan_fungsional_lengkap'] = "
	INSERT INTO sdm_jabatan_fungsional(
			jbtnPegKode,
			jbtnJabfungrId,
			jbtnTglMulai,
			jbtnSkPjb,
			jbtnSkNmr,
			jbtnSkTgl,
			jbtnStatus
		)VALUES(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
		);
";

$sql['update_jabatan_fungsional_lengkap'] = "
	UPDATE 
		sdm_jabatan_fungsional 
	SET 
		jbtnPegKode='%s',
		jbtnJabfungrId='%s',
		jbtnTglMulai='%s',
		jbtnSkPjb='%s',
		jbtnSkNmr='%s',
		jbtnSkTgl='%s',
		jbtnStatus='%s'
	WHERE 
		jbtnId='%s'
";

$sql['add_jabatan_struktural_lengkap'] = "
	INSERT INTO sdm_jabatan_struktural(
			jbtnPegKode,
			jbtnJabstrukrId,
			jbtnTglMulai,
			jbtnSkPjb,
			jbtnSkNmr,
			jbtnSkTgl,
			jbtnStatus
		)VALUES(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
		);
";

$sql['update_jabatan_struktural_lengkap'] = "
	UPDATE 
		sdm_jabatan_struktural 
	SET 
		jbtnPegKode='%s',
		jbtnJabstrukrId='%s',
		jbtnTglMulai='%s',
		jbtnSkPjb='%s',
		jbtnSkNmr='%s',
		jbtnSkTgl='%s',
		jbtnStatus='%s'
	WHERE 
		jbtnId='%s'
";

$sql['add_pendidikan_lengkap'] = "
	INSERT INTO sdm_pendidikan(
			pddkPegKode,
			pddkTkpddkrId,
			pddkLamaDinas,
			pddkTglMulaiDinas,
			pddkTglSelesaiDinas,
			pddkInstitusi,
			pddkNegaraId,
			pddkJurusan,
			pddkThnLulus,
			pddkKepala,
			pddkStatusTamat
		)VALUES(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
		);
";

$sql['update_pendidikan_lengkap'] = "
	UPDATE 
		sdm_pendidikan 
	SET 
		pddkPegKode='%s',
		pddkTkpddkrId='%s',
		pddkLamaDinas='%s',
		pddkTglMulaiDinas='%s',
		pddkTglSelesaiDinas='%s',
		pddkInstitusi='%s',
		pddkNegaraId='%s',
		pddkJurusan='%s',
		pddkThnLulus='%s',
		pddkKepala='%s',
		pddkStatusTamat='%s'
	WHERE 
		pddkId='%s'
";

$sql['get_gaji_pokok_lengkap'] = "
	SELECT 
		kgbId as id,
		sdm_kenaikan_gaji_berkala.*
	FROM  
		sdm_kenaikan_gaji_berkala
	WHERE 
		kgbPktgolId = '%s' AND kgbPegKode='%s'
";

$sql['add_gaji_pokok_lengkap'] = "
	INSERT INTO sdm_kenaikan_gaji_berkala(
			kgbPegKode,
			kgbPktgolId,
			kgbGajiPokokBaru,
			kgbBerlakuTanggal,
			kgbTanggalAkanDatang,
			kgbPejabatPenetap,
			kgbNomorPenetap,
			kgbTanggalPenetap,
			kgbAktif
		)VALUES(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
		);
";

$sql['update_gaji_pokok_lengkap'] = "
	UPDATE 
		sdm_kenaikan_gaji_berkala 
	SET 
		kgbPegKode='%s',
		kgbPktgolId='%s',
		kgbGajiPokokBaru='%s',
		kgbBerlakuTanggal='%s',
		kgbTanggalAkanDatang='%s',
		kgbPejabatPenetap='%s',
		kgbNomorPenetap='%s',
		kgbTanggalPenetap='%s',
		kgbAktif='%s'
	WHERE 
		kgbId='%s'
";

$sql['nonaktifkan_gaji_pokok_lengkap'] = "
	UPDATE 
		sdm_kenaikan_gaji_berkala 
	SET 
		kgbAktif='Tidak Aktif'
	WHERE 
		kgbPegKode = '%s'
";

?>
