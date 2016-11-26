<?php
//===GET===
$sql['get_count_pegawai'] = "
SELECT 
   COUNT(pegId) AS total
FROM 
   pub_pegawai
WHERE
   pegKodeResmi like '%s' OR pegNama like '%s'
";

$sql['get_count_mutasi'] = "
SELECT 
   COUNT(dplId) AS total
FROM 
   sdm_dp3
WHERE 
   dpPegKode='%s'
";

$sql['get_list_pegawai'] = "
SELECT 
   pegId as id,
	 pegNama as nama,
	 pegKodeResmi as nip
FROM 
   pub_pegawai
WHERE
   pegKodeResmi like '%s' OR pegNama like '%s'
ORDER BY
	 pegKodeResmi
   LIMIT %s, %s
";
  
$sql['get_data_pegawai']="
SELECT 
	pegId as id,
	pegNama as name,
	pegKodeResmi as kode,
	pegAlamat as alamat,
	pegNoTelp as telp,
	pegSatwilId as wil,
	pegFoto as foto,
	pegPnsTmt as tgl_pns,
	substring(pegTglMasukInstitusi,1,4) as masuk,
	pegKodeGateAccess as no_seri,
	pegTglLahir as tgl_lahir,
	pegKelamin as jenis_kelamin,
	concat(pendNama,' ',pddkJurusan,' ',pddkInstitusi) as pendidikan_tertinggi,
	pktgolrId as pangkat_id,
	concat(pktgolrId,' ',pktgolrNama) as pangkat_golongan,
	pktgolTmt as pangkat_golongan_tmt,
	
	jabfungrNama as jabatan_fungsional,
	jabfungrId as diangkat,
	jabfungrNama as diangkat_label,
	c.jbtnTglMulai as jabatan_fungsional_tmt,
	satkerId as unit_kerja_id,
	satkerNama as unit_kerja,
	jabstrukrId as jabatan_id,
	jabstrukrNama as jabatan
FROM
	pub_pegawai
	LEFT JOIN 
		(select * from (
				select * from 
					sdm_pendidikan 
					INNER JOIN pub_ref_pendidikan ON pendId=pddkTkpddkrId
				where pddkStatusTamat='Selesai'
				ORDER BY pendPendkelId DESC
		) as b GROUP by pddkPegKode ) as b
		ON pddkPegKode=pegId
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
	LEFT JOIN sdm_jabatan_fungsional c ON c.jbtnPegKode=pegId ANd c.jbtnStatus='Aktif'
	LEFT JOIN pub_ref_jabatan_fungsional b ON jbtnJabfungrId=b.jabfungrId
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerpegSatkerId=satkerId
	LEFT JOIN sdm_jabatan_struktural d ON d.jbtnPegKode=pegId ANd d.jbtnStatus='Aktif'
	LEFT JOIN sdm_ref_jabatan_struktural ON jbtnJabstrukrId=jabstrukrId
WHERE pegId='%s' 
"; 

$sql['get_list_mutasi_dp3']="
SELECT DISTINCT 
  dpId as id,
  a.pegNama as yang_dinilai,
  b.pegNama as pejabat_penilai,
  c.pegNama as atasan_pejabat_penilai,
	dpPeriode as mulai,
	dpPeriodeAkhir as selesai,
	dpTanggalDibuat as tanggal_penilaian,
	dpTanggalDiterimaAtasan as tanggal_diterima
FROM
	sdm_dp3
	LEFT JOIN pub_pegawai a ON dpPegKode=a.pegId
	LEFT JOIN pub_pegawai b ON dpPenilaiPegKode=b.pegId
	LEFT JOIN pub_pegawai c ON dpAtsnPenilaiPegKode=c.pegId
WHERE 
   dpPegKode='%s'
"; 

$sql['get_data_mutasi_dp3_by_id']="
SELECT 
	a1.pegId as pejabat_id,
	a1.pegNama as pejabat_nama,
	a1.pegKodeResmi as pejabat_nip,
	concat(a3.pktgolrId,' ',a3.pktgolrNama) as pejabat_pangkat,
	a5.jabstrukrNama as pejabat_jabatan,
	a7.satkernama as pejabat_unit_kerja,
	
	b1.pegId as atasan_pejabat_id,
	b1.pegNama as atasan_pejabat_nama,
	b1.pegKodeResmi as atasan_pejabat_nip,
	concat(b3.pktgolrId,' ',b3.pktgolrNama) as atasan_pejabat_pangkat,
	b5.jabstrukrNama as atasan_pejabat_jabatan,
	b7.satkernama as atasan_pejabat_unit_kerja,

	dpId as id,
	dpPeriode as mulai,
	dpPeriodeAkhir as selesai,
	dpTanggalDibuat as tgl_buat,
	dpTanggalDiterimaPns as tgl_pns,
	dpTanggalDiterimaAtasan as tgl_diterima,
	dpKesetiaan as kesetiaan,
	dpPrestasiKerja as prestasi_kerja,
	dpTanggungJawab as tanggung_jawab,
	dpKetaatan as ketaatan,
	dpKejujuran as kejujuran,
	dpKerjasama as kerjasama,
	dpPrakarsa as prakarsa,
	dpKepemimpinan as kepemimpinan,
	dpKeberatanPnsDinilai as keberatan,
	dpTanggapanAtasan as tanggapan_keberatan,
	dpKeputusanAtasan as keputusan_atasan,
	dpLain as lain_lain
FROM
	sdm_dp3

	LEFT JOIN pub_pegawai a1 ON dpPenilaiPegKode=a1.pegId
	LEFT JOIN sdm_pangkat_golongan a2 ON a2.pktgolPegKode=a1.pegId AND a2.pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan a3 ON a3.pktgolrId=a2.pktgolPktgolrId
	LEFT JOIN sdm_jabatan_struktural a4 ON a4.jbtnPegKode=a1.pegId ANd a4.jbtnStatus='Aktif'
	LEFT JOIN sdm_ref_jabatan_struktural a5 ON a4.jbtnJabstrukrId=a5.jabstrukrId
	LEFT JOIN sdm_satuan_kerja_pegawai a6 ON a6.satkerpegPegId=a1.pegId AND a6.satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja a7 ON a6.satkerpegSatkerId=a7.satkerId
	
	LEFT JOIN pub_pegawai b1 ON dpAtsnPenilaiPegKode=b1.pegId
	LEFT JOIN sdm_pangkat_golongan b2 ON b2.pktgolPegKode=b1.pegId AND b2.pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan b3 ON b3.pktgolrId=b2.pktgolPktgolrId
	LEFT JOIN sdm_jabatan_struktural b4 ON b4.jbtnPegKode=b1.pegId ANd b4.jbtnStatus='Aktif'
	LEFT JOIN sdm_ref_jabatan_struktural b5 ON a4.jbtnJabstrukrId=b5.jabstrukrId
	LEFT JOIN sdm_satuan_kerja_pegawai b6 ON b6.satkerpegPegId=b1.pegId AND b6.satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja b7 ON b6.satkerpegSatkerId=b7.satkerId
WHERE 
   dpPegKode='%s' AND dpId='%s' 
"; 

$sql['get_data_unsur_penilaian']="
SELECT
  dp3nId as id,
  dp3refId as idref,
	dp3refNama as nama,
	ifnull(dp3nAngkaLama,0) as lama,
	ifnull(dp3nAngkaBaru,0) as baru,
	ifnull(dp3nJumlahDigunakan,0) as digunakan,
	ifnull(dp3nJumlahLebihan,0) as lebihan
FROM	
	sdm_ref_dp3_penilaian
	INNER JOIN pub_ref_jabatan_fungsional ON jabfungrJenisrId=dp3refJabFungJenisrId
	INNER JOIN sdm_jabatan_fungsional ON jbtnJabfungrId=jabfungrId AND jbtnStatus='Aktif' AND jbtnPegKode='%s'
	LEFT JOIN sdm_dp3_penilaian ON dp3nDp3refKode=dp3refId AND dp3nDp3Id='%s'
	LEFT JOIN sdm_dp3 ON dp3nDp3Id=dp3Id AND dp3PegKode=jbtnPegKode
WHERE
	dp3refUnsur='%s' AND dp3refAktif='Ya'
	
"; 

$sql['get_combo_unit_kerja']="
SELECT
	satkerId as id,
	satkerNama as name
FROM
	pub_satuan_kerja 
";

$sql['get_combo_jabatan']="
SELECT
	jabfungrId as id,
	jabfungrNama as name
FROM
	pub_ref_jabatan_fungsional
WHERE
	jabfungrJenisrId IN (
				SELECT 
					jabfungrJenisrId
				FROM
					sdm_jabatan_fungsional 
					INNER JOIN pub_ref_jabatan_fungsional ON jbtnJabfungrId=jabfungrId AND jbtnStatus='Aktif' AND jbtnPegKode='%s'
			     )   
";

$sql['get_id_struk']="
SELECT 
   jabstrukKompgajidtId
FROM 
   sdm_ref_jabatan_struktural
WHERE 
   jabstrukrId = %s
";

// DO-----------
$sql['do_add'] = "
INSERT INTO sdm_dp3(
    dpPeriode,         
    dpPeriodeAkhir,         
    dpPegKode,         
    dpPktgolrId,         
    dpJabstrukrId,
             
    dpUkjrId,         
    dpPenilaiPegKode,         
    dpPenilaiPktgolrId,         
    dpPenilaiJabstrukrId,         
    dpPenilaiUkjrId,
             
    dpAtsnPenilaiPegKode,         
    dpAtsnPenilaiPktgolrId,         
    dpAtsnPenilaiJabstrukrId,         
    dpAtsnPenilaiUkjrId,         
    dpKesetiaan,   
          
    dpPrestasiKerja,         
    dpTanggungJawab,         
    dpKetaatan,         
    dpKejujuran,         
    dpKerjasama, 
            
    dpPrakarsa,         
    dpKepemimpinan,         
    dpKeberatanPnsDinilai,         
    dpTanggapanAtasan,         
    dpKeputusanAtasan,
             
    dpLain,         
    dpTanggalDibuat,         
    dpTanggalDiterimaPns,         
    dpTanggalDiterimaAtasan
   )
VALUES('%s','%s','%s','%s','%s',
       '%s','%s','%s','%s','%s',
       '%s','%s','%s','%s','%s',
       '%s','%s','%s','%s','%s',
       '%s','%s','%s','%s','%s',
       '%s','%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_dp3
SET 
	  dpPeriode= '%s',         
    dpPeriodeAkhir= '%s',         
    dpPegKode= '%s',         
    dpPktgolrId= '%s',         
    dpJabstrukrId= '%s',         
    dpUkjrId= '%s',         
    dpPenilaiPegKode= '%s',         
    dpPenilaiPktgolrId= '%s',         
    dpPenilaiJabstrukrId= '%s',         
    dpPenilaiUkjrId= '%s',         
    dpAtsnPenilaiPegKode= '%s',         
    dpAtsnPenilaiPktgolrId= '%s',         
    dpAtsnPenilaiJabstrukrId= '%s',         
    dpAtsnPenilaiUkjrId= '%s',         
    dpKesetiaan= '%s',         
    dpPrestasiKerja= '%s',         
    dpTanggungJawab= '%s',         
    dpKetaatan= '%s',         
    dpKejujuran= '%s',         
    dpKerjasama= '%s',         
    dpPrakarsa= '%s',         
    dpKepemimpinan= '%s',         
    dpKeberatanPnsDinilai= '%s',         
    dpTanggapanAtasan= '%s',         
    dpKeputusanAtasan= '%s',         
    dpLain= '%s',         
    dpTanggalDibuat= '%s',         
    dpTanggalDiterimaPns= '%s',         
    dpTanggalDiterimaAtasan= '%s'
WHERE 
	  dpId = '%s'
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_dp3
WHERE 
   dpId = %s  
";

$sql['get_max_id']="
select 
	max(dpId) as MAXID
FROM sdm_dp3
";
?>
