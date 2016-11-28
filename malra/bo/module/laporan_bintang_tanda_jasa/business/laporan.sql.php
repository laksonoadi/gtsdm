<?php
$sql['get_combo_unit_kerja'] = "
SELECT
  satkerId as id,
  satkerNama as name
FROM 
  pub_satuan_kerja
";

$sql['get_combo_tanda_jasa'] = "
SELECT
  tandaJasaId as id,
  tandaJasaNama as name
FROM 
  sdm_ref_tanda_jasa
";

$sql["count_data_bintang_tanda_jasa"] = "
SELECT FOUND_ROWS() AS total
";

$sql['get_data_bintang_tanda_jasa']="
SELECT SQL_CALC_FOUND_ROWS 
	pegKodeResmi as nip,
	concat(ifnull(pegGelarDepan,''),if(ifnull(pegGelarDepan,'')='','',' '),pegNama,if(ifnull(pegGelarBelakang,'')='','',', '),ifnull(pegGelarBelakang,'')) as nama,
	bintangtandajasaTanggal as tanggal,
	bintangtandajasaSertifikatNomor as sert_no,
	bintangtandajasaSertifikatTahun as sert_tahun,
	bintangtandajasaPemberi as pemberi,
	bintangtandajasaKeterangan as ket,
	tandaJasaNama as tanda_jasa,
	tandaJasaDeskripsi as tanda_jasa_deskripsi,
	satkerNama as unit_kerja
FROM
	pub_pegawai
	INNER JOIN sdm_bintang_tanda_jasa ON pegId=bintangtandajasaPegKode
	INNER JOIN sdm_ref_tanda_jasa ON bintangtandajasaTandaJasaId=tandaJasaId
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId

	LEFT JOIN sdm_verifikasi_data ON bintangtandajasaId=verdataValue 
WHERE
	verdataStatus='3' AND verdataVerifikasiId='17' AND
	bintangtandajasaTanggal>='%s' 
  AND bintangtandajasaTanggal<='%s'
	%unit_kerja%
	%tanda_jasa%
ORDER BY bintangtandajasaTanggal ASC
  %limit%
"; 

?>
