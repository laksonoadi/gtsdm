<?php
$sql['get_biodata_pegawai_by_id'] = "
   SELECT 
      bdtpegId 
   FROM  
      biodata_pegawai 
   WHERE 
      bdtpegNip = '%s'
";

$sql['add_biodata_pegawai'] = "
   INSERT INTO biodata_pegawai
      (
      bdtpegNip,
      bdtpegNidn,
      bdtpegNomorIndukDosen,
      bdtpegNama,
      bdtpegAlamat,
      bdtpegNoHp,
      bdtpegNoTelp,
      bdtpegUnitkerjaId,
      bdtpegIsAktif
      )
   VALUES(
      '%s',
      '%s',
      '%s',
      '%s',
      '%s',
      '%s',
      '%s',
      '%s',
      '%s');
";

$sql['update_biodata_pegawai'] = "
   UPDATE 
      biodata_pegawai 
   SET 
      bdtpegNidn = '%s',
      bdtpegNomorIndukDosen = '%s',
      bdtpegNama = '%s',
      bdtpegAlamat = '%s',
      bdtpegNoHp = '%s',
      bdtpegNoTelp = '%s',
      bdtpegUnitkerjaId = '%s',
      bdtpegIsAktif = '%s'
   WHERE 
      bdtpegNip = '%s'
";

$sql['count_komponen_gaji_ref'] = "
   SELECT 
      COUNT(kompgajiId) AS count 
   FROM 
      komponen_gaji_ref
";

$sql['copy_to_history'] = "
   INSERT INTO detail_pegawai_history (
      hispegdtBdtpegId,
      hispegdtKompgajidtId,
      hispegdtTanggal)
   SELECT 
      pegdtBdtpegId,
      pegdtKompgajidtId,
      now() 
   FROM 
      detail_pegawai
      JOIN detail_komponen_gaji_ref ON kompgajidtId = pegdtKompgajidtId
   WHERE
      pegdtBdtpegId = (SELECT bdtpegId FROM biodata_pegawai WHERE bdtpegNip = '%s')
";

$sql['delete_kom_gaji_pegawai'] = "
   DELETE
      detail_pegawai
   FROM
      detail_pegawai
   WHERE
      pegdtId IN
      (
         SELECT 
            pegdtId
         FROM 
            (SELECT * FROM detail_pegawai) a
            JOIN detail_komponen_gaji_ref ON kompgajidtId = pegdtKompgajidtId
         WHERE
            pegdtBdtpegId = (SELECT bdtpegId FROM biodata_pegawai WHERE bdtpegNip = '%s')
      )
";

$sql['insert_kom_gaji_pegawai'] = "
   INSERT INTO 
      detail_pegawai(
         pegdtBdtpegId,
         pegdtKompgajidtId,
         pegdtTanggal
   )
   SELECT 
      bdtpegId,
      kompgajidtId,
      now() 
   FROM 
      biodata_pegawai,
      detail_komponen_gaji_ref
   WHERE
      kompgajidtId = '%s' AND
      bdtpegNip = '%s'
   LIMIT 1
";

$sql['check_data'] = "
   SELECT 
      pegdtKompgajidtId 
   FROM 
      detail_pegawai
   JOIN detail_komponen_gaji_ref ON pegdtKompgajidtId = kompgajidtId
   WHERE 
      kompgajidtKode IN(%s)
   AND 
      pegdtKompgajidtId IN(
      SELECT 
         pegdtKompgajidtId 
      FROM 
         detail_pegawai 
      JOIN biodata_pegawai ON pegdtBdtpegId = bdtpegId
      WHERE bdtpegNip = '%s')
";

$sql['insert_into_detail_pegawai'] = "
   INSERT INTO 
      detail_pegawai(
         pegdtBdtpegId,
         pegdtKompgajidtId,
         pegdtTanggal
   )
   SELECT 
      bdtpegId,
      kompgajidtId,
      now() 
   FROM 
      biodata_pegawai,
      detail_komponen_gaji_ref
   WHERE
      kompgajidtKompgajiId = '%s' AND
      kompgajidtKode = '%s' AND
      bdtpegNip = '%s'
   LIMIT 1
";

$sql['get_komponen_gaji_id_by_label'] = "
SELECT
   kompgajiId,
   kompgajiKode,
   kompgajiNama
FROM
   komponen_gaji_ref
WHERE
   kompgajiKode IN ('%s') OR
   kompgajiNama IN ('%s')
"
?>
