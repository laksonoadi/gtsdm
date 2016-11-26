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
   COUNT(satkerpegId) AS total
FROM 
   sdm_satuan_kerja_pegawai
WHERE 
   satkerpegPegId='%s'
";

$sql['count_peg_bkd'] = "
SELECT 
   COUNT(bkdPegId) AS cekCountPeg
FROM 
   sdm_bkd
WHERE 
   bkdPegId = '%s'
";

$sql['get_list_pegawai'] = "
SELECT 
    pegId as id,
	pegNama as nama,
	pegKodeResmi as nip,
	IF(pktgolStatus='Aktif',CONCAT(pktgolPktgolrId,' - ',pktgolrNama),(select CONCAT(pktgolrId,' - ',pktgolrNama) from sdm_ref_pangkat_golongan where pktgolrId=(select pktgolPktgolrId from sdm_pangkat_golongan where pktgolPegKode=pegId and pktgolStatus='Aktif'))) as pktgol,
	IF(satkerpegAktif='Aktif',satkerNama,(select satkerNama from pub_satuan_kerja where satkerId=(select satkerpegSatkerId from sdm_satuan_kerja_pegawai where satkerpegPegId=pegId and satkerpegAktif='Aktif'))) as satker,
	IF(a.jbtnStatus='Aktif',jabstrukrNama,(select jabstrukrNama from sdm_ref_jabatan_struktural where jabstrukrId=(select jbtnJabstrukrId from sdm_jabatan_struktural where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabstruk,
	IF(b.jbtnStatus='Aktif',jabfungrNama,(select jabfungrNama from pub_ref_jabatan_fungsional where jabfungrId=(select jbtnJabfungrId from sdm_jabatan_fungsional where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabfung,
	IF(b.jbtnStatus='Aktif',jabfungrId,(select jabfungrId from pub_ref_jabatan_fungsional where jabfungrId=(select jbtnJabfungrId from sdm_jabatan_fungsional where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabfungid
FROM 
   pub_pegawai
   LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId
   LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
   LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId
   LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
   LEFT JOIN sdm_jabatan_struktural a ON a.jbtnPegKode=pegId
   LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=a.jbtnJabstrukrId
   LEFT JOIN sdm_jabatan_fungsional b ON b.jbtnPegKode=pegId
   LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=b.jbtnJabfungrId
WHERE
   pegKodeResmi like '%s' OR pegNama like '%s'
GROUP BY 
	 pegId
ORDER BY
	 pegKodeResmi
   LIMIT %s, %s
";

$sql['get_pegawai_full'] = "
SELECT 
    pegId as id,
	pegNama as nama,
	pegKodeResmi as nip,
	pegTmpLahir as tptlahir,
	pegTglLahir as tgllahir,
	pegNoTelp as notelp,
	pegNoHp as nohp,
	(select CONCAT(pddkJurusan,' - ',pddkInstitusi) from sdm_pendidikan where pddkPegKode=pegId and pddkTkpddkrId='6') as S1,
	(select CONCAT(pddkJurusan,' - ',pddkInstitusi) from sdm_pendidikan where pddkPegKode=pegId and pddkTkpddkrId='7') as S2,
	(select CONCAT(pddkJurusan,' - ',pddkInstitusi) from sdm_pendidikan where pddkPegKode=pegId and pddkTkpddkrId='8') as S3,
	bidangilmurNama as bidang,	
	(select srtfkdetNo from sdm_sertifikasi_detail where srtfkdetPegId=pegId and srtfkdetHasilAkhir='LULUS') as nosertifikasi,
	(select srtfkdetNidn from sdm_sertifikasi_detail where srtfkdetPegId=pegId and srtfkdetHasilAkhir='LULUS') as nidn,
	IF(pktgolStatus='Aktif',CONCAT(pktgolPktgolrId,' - ',pktgolrNama),(select CONCAT(pktgolrId,' - ',pktgolrNama) from sdm_ref_pangkat_golongan where pktgolrId=(select pktgolPktgolrId from sdm_pangkat_golongan where pktgolPegKode=pegId and pktgolStatus='Aktif'))) as pktgol,
	IF(pktgolStatus='Aktif',pktgolPktgolrId,(select pktgolrId from sdm_ref_pangkat_golongan where pktgolrId=(select pktgolPktgolrId from sdm_pangkat_golongan where pktgolPegKode=pegId and pktgolStatus='Aktif'))) as pktgolid,
	IF(satkerpegAktif='Aktif',satkerNama,(select satkerNama from pub_satuan_kerja where satkerId=(select satkerpegSatkerId from sdm_satuan_kerja_pegawai where satkerpegPegId=pegId and satkerpegAktif='Aktif'))) as satker,
	IF(a.jbtnStatus='Aktif',jabstrukrNama,(select jabstrukrNama from sdm_ref_jabatan_struktural where jabstrukrId=(select jbtnJabstrukrId from sdm_jabatan_struktural where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabstruk,
	IF(b.jbtnStatus='Aktif',jabfungrNama,(select jabfungrNama from pub_ref_jabatan_fungsional where jabfungrId=(select jbtnJabfungrId from sdm_jabatan_fungsional where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabfung,
	IF(b.jbtnStatus='Aktif',jabfungrId,(select jabfungrId from pub_ref_jabatan_fungsional where jabfungrId=(select jbtnJabfungrId from sdm_jabatan_fungsional where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabfungid
FROM 
   pub_pegawai
   LEFT JOIN sdm_dosen_kepakaran ON dosenPakarPegKode=pegId
   LEFT JOIN sdm_ref_kepakaran ON kepakaranrId=dosenKepakaranId
   LEFT JOIN sdm_ref_kepakaran_bidang_ilmu ON bidangilmurId=kepakaranBidangIlmurId

   LEFT JOIN sdm_pendidikan ON pddkPegKode=pegId
   LEFT JOIN sdm_sertifikasi_detail ON srtfkdetPegId=pegId
   LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId
   LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
   LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId
   LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
   LEFT JOIN sdm_jabatan_struktural a ON a.jbtnPegKode=pegId
   LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=a.jbtnJabstrukrId
   LEFT JOIN sdm_jabatan_fungsional b ON b.jbtnPegKode=pegId
   LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=b.jbtnJabfungrId
WHERE
   pegId='%s'
GROUP BY 
	 pegId
ORDER BY
	 pegKodeResmi
";

$sql['get_pegawai_bkd'] = "
SELECT 
    bkdId as idBkd,
    bkdPegId as id,
	bkdNoSertifikasi as nosertifikasi,
	bkdNama as nama,
	bkdNIP as nip,
	bkdNIDN as nidn,
	bkdNamaPT as namapt,
	bkdAlamatPT as almtpt,
	bkdFakultas as fakultas,
	bkdProdi as prodi,
	bkdBidang as bidang,
	bkdNoHp as nohp,
	bkdS1 as s1,
	bkdS1 as s2,
	bkdS1 as s3,
	bkdJenis as jenis,
	bkdTahunAkademik as thnakd,
	bkdSemester as semester,
	bkdPegIdAsesor1 as asesor1,
	bkdPegIdAsesor2 as asesor2,
	IF(pktgolStatus='Aktif',CONCAT(pktgolPktgolrId,' - ',pktgolrNama),(select CONCAT(pktgolrId,' - ',pktgolrNama) from sdm_ref_pangkat_golongan where pktgolrId=(select pktgolPktgolrId from sdm_pangkat_golongan where pktgolPegKode=pegId and pktgolStatus='Aktif'))) as pktgol,
	IF(b.jbtnStatus='Aktif',jabfungrNama,(select jabfungrNama from pub_ref_jabatan_fungsional where jabfungrId=(select jbtnJabfungrId from sdm_jabatan_fungsional where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabfung
FROM 
   sdm_bkd
   LEFT JOIN pub_pegawai ON pegId=bkdPegId
   LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=bkdPegId
   LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
   LEFT JOIN sdm_jabatan_struktural a ON a.jbtnPegKode=bkdPegId
   LEFT JOIN sdm_jabatan_fungsional b ON b.jbtnPegKode=bkdPegId
   LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=b.jbtnJabfungrId
WHERE
   bkdPegId='%s'
GROUP BY 
	 bkdPegId
ORDER BY
	 bkdId
";

$sql['get_data_detail_bkd_dosen'] = "
SELECT 
    bkdId as idBkd,
    bkdPegId as id,
	bkdNoSertifikasi as nosertifikasi,
	bkdNama as nama,
	bkdNIP as nip,
	bkdNIDN as nidn,
	bkdNamaPT as namapt,
	bkdAlamatPT as almtpt,
	bkdFakultas as fakultas,
	bkdProdi as prodi,
	bkdBidang as bidang,
	bkdNoHp as nohp,
	bkdS1 as s1,
	bkdS1 as s2,
	bkdS1 as s3,
	bkdJenis as jenis,
	bkdTahunAkademik as thnakd,
	bkdSemester as semester,
	bkdPegIdAsesor1 as asesor1,
	bkdPegIdAsesor2 as asesor2,
	IF(pktgolStatus='Aktif',CONCAT(pktgolPktgolrId,' - ',pktgolrNama),(select CONCAT(pktgolrId,' - ',pktgolrNama) from sdm_ref_pangkat_golongan where pktgolrId=(select pktgolPktgolrId from sdm_pangkat_golongan where pktgolPegKode=pegId and pktgolStatus='Aktif'))) as pktgol,
	IF(b.jbtnStatus='Aktif',jabfungrNama,(select jabfungrNama from pub_ref_jabatan_fungsional where jabfungrId=(select jbtnJabfungrId from sdm_jabatan_fungsional where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabfung
FROM 
   sdm_bkd
   LEFT JOIN pub_pegawai ON pegId=bkdPegId
   LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=bkdPegId
   LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
   LEFT JOIN sdm_jabatan_struktural a ON a.jbtnPegKode=bkdPegId
   LEFT JOIN sdm_jabatan_fungsional b ON b.jbtnPegKode=bkdPegId
   LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=b.jbtnJabfungrId
WHERE
   bkdPegId='%s'
AND
   bkdId='%s'
GROUP BY 
	 bkdPegId
ORDER BY
	 bkdId
";

$sql['get_list_bkd'] = "
SELECT 
    bkdId as idBkd,
    bkdPegId as id,
	bkdNoSertifikasi as nosertifikasi,
	bkdNama as nama,
	bkdNIP as nip,
	bkdNIDN as nidn,
	bkdNamaPT as namapt,
	bkdAlamatPT as almtpt,
	bkdFakultas as fakultas,
	bkdProdi as prodi,
	bkdBidang as bidang,
	bkdNoHp as nohp,
	bkdS1 as s1,
	bkdS2 as s2,
	bkdS3 as s3,
	bkdJenis as jenis,
	bkdTahunAkademik as thnakd,
	bkdSemester as semester,
	bkdPegIdAsesor1 as asesor1,
	bkdPegIdAsesor2 as asesor2,
	bkdTglPengajuan as tanggal_pengajuan,
	bkdTglPenilaian as tanggal_penilaian,
	IF(pktgolStatus='Aktif',CONCAT(pktgolPktgolrId,' - ',pktgolrNama),(select CONCAT(pktgolrId,' - ',pktgolrNama) from sdm_ref_pangkat_golongan where pktgolrId=(select pktgolPktgolrId from sdm_pangkat_golongan where pktgolPegKode=pegId and pktgolStatus='Aktif'))) as pktgol,
	IF(b.jbtnStatus='Aktif',jabfungrNama,(select jabfungrNama from pub_ref_jabatan_fungsional where jabfungrId=(select jbtnJabfungrId from sdm_jabatan_fungsional where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabfung
FROM
   sdm_bkd
   LEFT JOIN pub_pegawai ON pegId=bkdPegId
   LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=bkdPegId
   LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
   LEFT JOIN sdm_jabatan_struktural a ON a.jbtnPegKode=bkdPegId
   LEFT JOIN sdm_jabatan_fungsional b ON b.jbtnPegKode=bkdPegId
   LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=b.jbtnJabfungrId
WHERE
   bkdPegId='%s'
ORDER BY
	 bkdId
";

$sql['get_detail_bkd'] = "
SELECT 
    bkdId as idBkd,
    bkdPegId as id,
	bkdNoSertifikasi as nosertifikasi,
	bkdNama as nama,
	bkdNIP as nip,
	bkdNIDN as nidn,
	bkdNamaPT as namapt,
	bkdAlamatPT as almtpt,
	bkdFakultas as fakultas,
	bkdProdi as prodi,
	bkdBidang as bidang,
	bkdNoHp as nohp,
	bkdS1 as s1,
	bkdS2 as s2,
	bkdS3 as s3,
	bkdJenis as jenis,
	bkdTahunAkademik as thnakd,
	bkdSemester as semester,
	bkdPegIdAsesor1 as asesor1,
	bkdPegIdAsesor2 as asesor2,
	bkdKesimpulan as kesimpulan,
	bkdTglPenilaian as tglPenilaian,
	IF(pktgolStatus='Aktif',CONCAT(pktgolPktgolrId,' - ',pktgolrNama),(select CONCAT(pktgolrId,' - ',pktgolrNama) from sdm_ref_pangkat_golongan where pktgolrId=(select pktgolPktgolrId from sdm_pangkat_golongan where pktgolPegKode=pegId and pktgolStatus='Aktif'))) as pktgol,
	IF(b.jbtnStatus='Aktif',jabfungrNama,(select jabfungrNama from pub_ref_jabatan_fungsional where jabfungrId=(select jbtnJabfungrId from sdm_jabatan_fungsional where jbtnPegKode=pegId and jbtnStatus='Aktif'))) as jabfung
FROM 
   sdm_bkd
   LEFT JOIN pub_pegawai ON pegId=bkdPegId
   LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=bkdPegId
   LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
   LEFT JOIN sdm_jabatan_struktural a ON a.jbtnPegKode=bkdPegId
   LEFT JOIN sdm_jabatan_fungsional b ON b.jbtnPegKode=bkdPegId
   LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=b.jbtnJabfungrId
WHERE
   bkdId='%s'
GROUP BY 
	 bkdId
ORDER BY
	 bkdId
";

$sql['get_data_mutasi_bkd_by_id']="
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
	dpLain as lain_lain,
	dpUpload as upload
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

$sql['get_data_pegawai']="
SELECT 
	pegId as id,
	pegNama as name,
	pegKodeResmi as kode,
	pegAlamat as alamat,
	pegNoTelp as telp,
	pegNoHp as hp,
	pegSatwilId as wil,
	pegFoto as foto,
	pegTmpLahir as tptlahir,
	pegTglLahir as tgllahir,
	substring(pegTglMasukInstitusi,1,4) as masuk
FROM
	pub_pegawai
WHERE pegId='%s' 
"; 

$sql['get_list_mutasi_satuan_kerja_pegawai']="
SELECT DISTINCT 
	p.satkerpegId as id,
	p.satkerpegPegId as nip,
	p.satkerpegSatkerId as satker,
	p.satkerpegTmt as tmt,
	p.satkerpegPjbSk as pejabat,
	p.satkerpegNoSk as nosk,
	p.satkerpegTglSk as tgl_sk,
	p.satkerpegAktif as status,
	p.satkerpegSkUpload as upload,
	pg.satkerNama AS satkernama
FROM
	sdm_satuan_kerja_pegawai p
	LEFT JOIN pub_satuan_kerja pg ON (p.satkerpegSatkerId=pg.satkerId)
WHERE 
   p.satkerpegPegId='%s'
ORDER BY p.satkerpegId DESC
"; 

$sql['get_data_mutasi_satuan_kerja_pegawai_by_id']="
SELECT 
	p.satkerpegId as id,
	p.satkerpegPegId as nip,
	p.satkerpegSatkerId as satker,
	p.satkerpegTmt as tmt,
	p.satkerpegPjbSk as pejabat,
	p.satkerpegNoSk as nosk,
	p.satkerpegTglSk as tgl_sk,
	p.satkerpegAktif as status,
	p.satkerpegSkUpload as upload,
	pg.satkerNama AS satkernama
FROM
	sdm_satuan_kerja_pegawai p
	LEFT JOIN pub_satuan_kerja pg ON (p.satkerpegSatkerId=pg.satkerId)
WHERE 
   p.satkerpegPegId='%s' AND
   p.satkerpegId='%s' 
"; 

$sql['get_combo_satuan_kerja']="
SELECT
	satkerId as id,
	satkerNama as name
FROM
	pub_satuan_kerja
ORDER BY
  satkerId ASC
";

$sql['get_combo_rekomendasi']="
SELECT
	bkdjnsrekomenId as id,
	bkdjnsrekomenKode as kode,
	bkdjnsrekomenNama as name
FROM
	sdm_bkd_ref_rekomendasi
ORDER BY
  bkdjnsrekomenId ASC
";



// GET LIST PENDIDIKAN START ----------------------------------------------------------------------------------------------------------------------
$sql['get_data_pendidikan'] = "
SELECT 
    a.bkdpendId AS idFix,
	a.bkdpendBkdId AS bkdId,
	a.bkdpendJenisKegiatan AS nmKeg,
	a.bkdpendBebanKerjaBukti AS bkBukti,
	a.bkdpendBebanKerjaSks AS bkSks,
	a.bkdpendMasaPenugasan AS masa,
	a.bkdpendKinerjaBukti AS kBukti,
	a.bkdpendKinerjaSks AS bksks,
	a.bkdpendRekomendasi AS rekomen,
	a.bkdpendFile AS FILE,
	b.bkdjnsrekomenNama AS nmrekomen
FROM 
   sdm_bkd_pendidikan a,sdm_bkd_ref_rekomendasi b
WHERE
   a.bkdpendRekomendasi = b.bkdjnsrekomenId
AND
   a.bkdpendBkdId ='%s'
ORDER BY
   a.bkdpendId
";
// GET LIST PENDIDIKAN END ----------------------------------------------------------------------------------------------------------------------

// GET LIST PENELITIAN START ----------------------------------------------------------------------------------------------------------------------
$sql['get_data_penelitian'] = "
SELECT 
    a.bkdpenId as idFix,
    a.bkdpenBkdId as bkdId,
	a.bkdpenJenisKegiatan as nmKeg,
	a.bkdpenBebanKerjaBukti as bkBukti,
	a.bkdpenBebanKerjaSks as bkSks,
	a.bkdpenMasaPenugasan as masa,
	a.bkdpenKinerjaBukti as kBukti,
	a.bkdpenKinerjaSks as bksks,
	a.bkdpenRekomendasi as rekomen,
	a.bkdpenltFile as file,
	b.bkdjnsrekomenNama AS nmrekomen
FROM 
   sdm_bkd_penelitian a,sdm_bkd_ref_rekomendasi b
WHERE
   a.bkdpenRekomendasi = b.bkdjnsrekomenId
AND
   a.bkdpenBkdId ='%s'
ORDER BY
   a.bkdpenId
";
// GET LIST PENELITIAN END ----------------------------------------------------------------------------------------------------------------------

// GET LIST PENGABDIAN START ----------------------------------------------------------------------------------------------------------------------
$sql['get_data_pengabdian'] = "
SELECT 
    a.bkdpengId as idFix,
    a.bkdpengBkdId as bkdId,
	a.bkdpengJenisKegiatan as nmKeg,
	a.bkdpengBebanKerjaBukti as bkBukti,
	a.bkdpengBebanKerjaSks as bkSks,
	a.bkdpengMasaPenugasan as masa,
	a.bkdpengKinerjaBukti as kBukti,
	a.bkdpengKinerjaSks as bksks,
	a.bkdpengRekomendasi as rekomen,
	a.bkdpengbFile as file,
	b.bkdjnsrekomenNama AS nmrekomen
FROM 
   sdm_bkd_pengabdian a,sdm_bkd_ref_rekomendasi b
WHERE
   a.bkdpengRekomendasi = b.bkdjnsrekomenId
AND
   a.bkdpengBkdId ='%s'
ORDER BY
   a.bkdpengId
";
// GET LIST PENGABDIAN END ----------------------------------------------------------------------------------------------------------------------

// GET LIST PENUNJANG START ----------------------------------------------------------------------------------------------------------------------
$sql['get_data_penunjang'] = "
SELECT 
    a.bkdpenuId as idFix,
    a.bkdpenuBkdId as bkdId,
	a.bkdpenuJenisKegiatan as nmKeg,
	a.bkdpenuBebanKerjaBukti as bkBukti,
	a.bkdpenuBebanKerjaSks as bkSks,
	a.bkdpenuMasaPenugasan as masa,
	a.bkdpenuKinerjaBukti as kBukti,
	a.bkdpenuKinerjaSks as bksks,
	a.bkdpenuRekomendasi as rekomen,
	a.bkdpenunjgFile as file,
	b.bkdjnsrekomenNama AS nmrekomen
FROM 
   sdm_bkd_penunjang a,sdm_bkd_ref_rekomendasi b
WHERE
   a.bkdpenuRekomendasi = b.bkdjnsrekomenId
AND
   a.bkdpenuBkdId ='%s'
ORDER BY
   a.bkdpenuId
";
// GET LIST PENUNJANG END ----------------------------------------------------------------------------------------------------------------------

// GET LIST PROFESOR START ----------------------------------------------------------------------------------------------------------------------
$sql['get_data_profesor'] = "
SELECT 
    a.bkdprofId as idFix,
    a.bkdprofBkdId as bkdId,
	a.bkdprofJenisKegiatan as nmKeg,
	a.bkdprofBebanKerjaBukti as bkBukti,
	a.bkdprofBebanKerjaSks as bkSks,
	a.bkdprofMasaPenugasan as masa,
	a.bkdprofKinerjaBukti as kBukti,
	a.bkdprofKinerjaSks as bksks,
	a.bkdprofRekomendasi as rekomen,
	a.bkdprofFile as file,
	b.bkdjnsrekomenNama AS nmrekomen
FROM 
   sdm_bkd_profesor a,sdm_bkd_ref_rekomendasi b
WHERE
   a.bkdprofRekomendasi = b.bkdjnsrekomenId
AND
   a.bkdprofBkdId ='%s'
ORDER BY
   a.bkdprofId
";
// GET LIST PROFESOR END ----------------------------------------------------------------------------------------------------------------------




// DO -----------
// DOSEN -----------------------------------------------------------------------------------------------------------------------------------
$sql['do_add_dosen'] = "
INSERT INTO 
   sdm_bkd
   (bkdPegId, bkdNoSertifikasi, bkdNama, bkdNIP, bkdNIDN, bkdNamaPT, bkdAlamatPT, bkdFakultas, bkdProdi,
	bkdBidang, bkdNoHp, bkdJabfungrId, bkdPktgolrId, bkdS1, bkdS2, bkdS3, bkdJenis, bkdTahunAkademik, bkdSemester, 
	bkdPegIdAsesor1, bkdPegIdAsesor2,bkdTglPengajuan)
VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s',
       '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
	   '%s','%s',NOW())
";
// DOSEN -----------------------------------------------------------------------------------------------------------------------------------

// PENDIDIKAN -----------------------------------------------------------------------------------------------------------------------------------
$sql['do_add_pendidikan'] = "
INSERT INTO 
   sdm_bkd_pendidikan(
		bkdpendBkdId, 
		bkdpendJenisKegiatan, 
		bkdpendBebanKerjaBukti, 
		bkdpendBebanKerjaSks, 
		bkdpendMasaPenugasan, 
		bkdpendKinerjaBukti,
		bkdpendKinerjaSks, 
		bkdpendRekomendasi,
		bkdpendFile)
VALUES('%s','%s','%s','%s','%s','%s','%s', '%s', '%s')
";
// PENDIDIKAN -----------------------------------------------------------------------------------------------------------------------------------

// PENELITIAN -----------------------------------------------------------------------------------------------------------------------------------
$sql['do_add_penelitian'] = "
INSERT INTO 
   sdm_bkd_penelitian(
		bkdpenBkdId, 
		bkdpenJenisKegiatan, 
		bkdpenBebanKerjaBukti, 
		bkdpenBebanKerjaSks, 
		bkdpenMasaPenugasan, 
		bkdpenKinerjaBukti,
		bkdpenKinerjaSks, 
		bkdpenRekomendasi,
		bkdpenltFile)
VALUES('%s','%s','%s','%s','%s','%s','%s', '%s', '%s')
";
// PENELITIAN -----------------------------------------------------------------------------------------------------------------------------------

// PENGABDIAN -----------------------------------------------------------------------------------------------------------------------------------
$sql['do_add_pengabdian'] = "
INSERT INTO 
   sdm_bkd_pengabdian(
		bkdpengBkdId, 
		bkdpengJenisKegiatan, 
		bkdpengBebanKerjaBukti, 
		bkdpengBebanKerjaSks, 
		bkdpengMasaPenugasan, 
		bkdpengKinerjaBukti,
		bkdpengKinerjaSks, 
		bkdpengRekomendasi,
		bkdpengbFile)
VALUES('%s','%s','%s','%s','%s','%s','%s', '%s', '%s')
";
// PENGABDIAN -----------------------------------------------------------------------------------------------------------------------------------

// PENUNJANG -----------------------------------------------------------------------------------------------------------------------------------
$sql['do_add_penunjang'] = "
INSERT INTO 
   sdm_bkd_penunjang(
		bkdpenuBkdId, 
		bkdpenuJenisKegiatan, 
		bkdpenuBebanKerjaBukti, 
		bkdpenuBebanKerjaSks, 
		bkdpenuMasaPenugasan, 
		bkdpenuKinerjaBukti,
		bkdpenuKinerjaSks, 
		bkdpenuRekomendasi,
		bkdpenunjgFile)
VALUES('%s','%s','%s','%s','%s','%s','%s', '%s', '%s')
";
// PENUNJANG -----------------------------------------------------------------------------------------------------------------------------------

// PROFESOR -----------------------------------------------------------------------------------------------------------------------------------
$sql['do_add_profesor'] = "
INSERT INTO
   sdm_bkd_profesor(
		bkdprofBkdId, 
		bkdprofJenisKegiatan, 
		bkdprofBebanKerjaBukti, 
		bkdprofBebanKerjaSks, 
		bkdprofMasaPenugasan, 
		bkdprofKinerjaBukti,
		bkdprofKinerjaSks, 
		bkdprofRekomendasi,
		bkdprofFile)
VALUES('%s','%s','%s','%s','%s','%s','%s', '%s', '%s')
";
// PROFESOR -----------------------------------------------------------------------------------------------------------------------------------




// ======================================================================= UPDATE ==========================================================
// DOSEN -----------------------------------------------------------------------------------------------------------------------------------
$sql['do_update_dosen'] = "
UPDATE sdm_bkd
SET 
	bkdNamaPT = '%s',
	bkdAlamatPT = '%s',
	bkdNoHp = '%s',
	bkdJenis = '%s',
	bkdTahunAkademik = '%s',
	bkdSemester = '%s',
	bkdPegIdAsesor1 = '%s',
	bkdPegIdAsesor2 = '%s'
WHERE 
	bkdId = %s
AND
	bkdPegId = %s
";  





// ======================================================================= DELETE ==========================================================
// DOSEN -----------------------------------------------------------------------------------------------------------------------------------
$sql['do_delete'] = "
DELETE FROM
   sdm_bkd
WHERE 
   bkdId = %s
";

// PENDIDIKAN -----------------------------------------------------------------------------------------------------------------------------------
$sql['do_delete_pendidikan'] = "
DELETE FROM
   sdm_bkd_pendidikan
WHERE 
   bkdpendId = %s
";

// PENELITIAN -----------------------------------------------------------------------------------------------------------------------------------
$sql['do_delete_penelitian'] = "
DELETE FROM
   sdm_bkd_penelitian
WHERE 
   bkdpenId = %s
";

// PENGABDIAN -----------------------------------------------------------------------------------------------------------------------------------
$sql['do_delete_pengabdian'] = "
DELETE FROM
   sdm_bkd_pengabdian
WHERE 
   bkdpengId = %s
";

// PENUNJANG -----------------------------------------------------------------------------------------------------------------------------------
$sql['do_delete_penunjang'] = "
DELETE FROM
   sdm_bkd_penunjang
WHERE 
   bkdpenuId = %s
";

// PROFESOR -----------------------------------------------------------------------------------------------------------------------------------
$sql['do_delete_profesor'] = "
DELETE FROM
   sdm_bkd_profesor
WHERE 
   bkdprofId = %s
";



// ======================================================================= GET NAMA FILE ========================================================
$sql['get_nmfile_pendidikan'] = "
SELECT 
   bkdpendFile AS nmfile
FROM 
   sdm_bkd_pendidikan
WHERE 
   bkdpendId='%s'
";

$sql['get_nmfile_penelitian'] = "
SELECT 
   bkdpenltFile AS nmfile
FROM 
   sdm_bkd_penelitian
WHERE 
   bkdpenId='%s'
";

$sql['get_nmfile_pengabdian'] = "
SELECT 
   bkdpengbFile AS nmfile
FROM 
   sdm_bkd_pengabadian
WHERE 
   bkdpengId='%s'
";

$sql['get_nmfile_penunjang'] = "
SELECT 
   bkdpenunjgFile AS nmfile
FROM 
   sdm_bkd_penunjang
WHERE 
   bkdpenudId='%s'
";

$sql['get_nmfile_profesor'] = "
SELECT 
   bkdprofFile AS nmfile
FROM 
   sdm_bkd_profesor
WHERE 
   bkdprofId='%s'
";



// ======================================================================= GET COUNT RECORD START ========================================================
$sql['get_count_rec_penddk'] = "
	SELECT COUNT(bkdpendId) as countRPenddk
	FROM sdm_bkd_pendidikan
	WHERE bkdpendBkdId = '%s'";
$sql['get_count_rec_penlt'] = "
	SELECT COUNT(bkdpenId) as countRPenlt
	FROM sdm_bkd_penelitian
	WHERE bkdpenBkdId = '%s'";
$sql['get_count_rec_pengbd'] = "
	SELECT COUNT(bkdpengRekomendasi) as countRPengbd
	FROM sdm_bkd_pengabdian
	WHERE bkdpengBkdId = '%s'";
$sql['get_count_rec_penunj'] = "
	SELECT COUNT(bkdpenuId) as countRPenunj
	FROM sdm_bkd_penunjang
	WHERE bkdpenuBkdId = '%s'";
$sql['get_count_rec_prof'] = "
	SELECT COUNT(bkdprofId) as countRProf
	FROM sdm_bkd_profesor
	WHERE bkdprofBkdId = '%s'";
// ======================================================================= GET COUNT RECORD START ========================================================



// ======================================================================= GET COUNT REKOMENDASI START ========================================================
$sql['get_count_rek_penddk'] = "
	SELECT COUNT(bkdpendRekomendasi) as countPenddk
	FROM sdm_bkd_pendidikan
	WHERE bkdpendBkdId = '%s'
	AND bkdpendRekomendasi != '-1'";
$sql['get_count_rek_penlt'] = "
	SELECT COUNT(bkdpenRekomendasi) as countPenlt
	FROM sdm_bkd_penelitian
	WHERE bkdpenBkdId = '%s'
	AND bkdpenRekomendasi != '-1'";
$sql['get_count_rek_pengbd'] = "
	SELECT COUNT(bkdpengRekomendasi) as countPengbd
	FROM sdm_bkd_pengabdian
	WHERE bkdpengBkdId = '%s'
	AND bkdpengRekomendasi != '-1'";
$sql['get_count_rek_penunj'] = "
	SELECT COUNT(bkdpenuRekomendasi) as countPenunj
	FROM sdm_bkd_penunjang
	WHERE bkdpenuBkdId = '%s'
	AND bkdpenuRekomendasi != '-1'";
$sql['get_count_rek_prof'] = "
	SELECT COUNT(bkdprofRekomendasi) as countProf
	FROM sdm_bkd_profesor
	WHERE bkdprofBkdId = '%s'
	AND bkdprofRekomendasi != '-1'";
// ======================================================================= GET COUNT REKOMENDASI START ========================================================



// -----------------------------------------------------------------------------------------------




$sql['update_status']="
update 
	sdm_satuan_kerja_pegawai
set satkerpegAktif = '%s'
where satkerpegId != %s AND satkerpegPegId = '%s'
";

$sql['get_max_status']="
select 
	max(satkerpegId) as MAXID,
	satkerpegAktif as STAT
FROM sdm_satuan_kerja_pegawai
WHERE satkerpegId=(select max(satkerpegId) FROM sdm_satuan_kerja_pegawai)
group by satkerpegId
";

$sql['get_max_id']="
select 
	max(bkdId) as MAXID
FROM sdm_bkd
";
?>
