<?php
// DO-----------

$sql['do_add_pegawai'] = "
INSERT INTO 
   pegawai
   (pegNip,pegNama,pegGelarDepan,
    pegGelarBelakang,pegTanggalLahir,pegJenisKelaminKode,
	pegAlamatRumah,pegKodePos,pegNoTelpRumah,
	pegNoHP,pegJnpegrId,pegNomorKartuTaspen,
	pegIsAktif,pegTanggalPengubahan,pegKotaKodeLahir,pegKotaKode)
VALUES('%s',UPPER('%s'),'%s',
      '%s','%s','%s',
      '%s','%s','%s',
      '%s','1','%s',
      '1',now(),1551,1551)
ON DUPLICATE KEY UPDATE
	pegNama=VALUES(pegNama),
	pegGelarDepan=VALUES(pegGelarDepan),
	pegGelarBelakang=VALUES(pegGelarBelakang),
	pegTanggalLahir=VALUES(pegTanggalLahir),
	pegAlamatRumah=VALUES(pegAlamatRumah),
	pegKodePos=VALUES(pegKodePos),
	pegNoTelpRumah=VALUES(pegNoTelpRumah),
	pegNoHP=VALUES(pegNoHP),
	pegNomorKartuTaspen=VALUES(pegNomorKartuTaspen),
	pegTanggalPengubahan=NOW()
";

$sql['do_update_jenis_pegawai'] = "
UPDATE 
	pegawai
SET
	pegJnpegrId='%s'
WHERE
	pegNip='%s'
";

$sql['do_add_dosen'] = "
INSERT INTO 
   dosen
   (dsnPegNip,dsnNidn,dsnNomorSim,dsnSadrKode)
VALUES('%s','%s','%s','A')
ON DUPLICATE KEY UPDATE
	dsnNidn=VALUES(dsnNidn),
	dsnNomorSim=VALUES(dsnNomorSim)
";

$sql['do_delete_pegawai'] = "
DELETE FROM
	pegawai
WHERE
	pegNip='%s'
";

$sql['do_delete_dosen'] = "
DELETE FROM
	dosen
WHERE
	dsnPegNip='%s'
";

?>
