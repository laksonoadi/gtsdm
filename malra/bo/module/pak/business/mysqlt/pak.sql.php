<?php
$sql['get_count_pak'] = "
   SELECT
      count(pakrefId) AS total
   FROM sdm_ref_pak_penilaian   
";

$sql['get_list_pak'] = "
   SELECT 
      p.pakrefId AS id,
      p.pakrefNama AS nama,
      p.pakrefUnsur AS unsur,
      p.pakrefAktif AS aktif,
      j.jabfungJenis as jfjenis
   from sdm_ref_pak_penilaian p
   JOIN pub_ref_jabatan_fungsional_jenis j ON (j.jabfungjenisrId=p.pakrefJabFungJenisrId)
   WHERE p.pakrefNama LIKE '%s'  
   ORDER by p.pakrefNama
   LIMIT %s, %s  
";


$sql['get_pak_by_id'] = "
  SELECT 
      p.pakrefId as id,
      p.pakrefNama AS nama,
      p.pakrefUnsur AS unsur,
      p.pakrefAktif AS aktif,
      p.pakrefJabFungJenisrId as jfid, 
      j.jabfungJenis as jfjenis
   from sdm_ref_pak_penilaian p
   JOIN pub_ref_jabatan_fungsional_jenis j ON (j.jabfungjenisrId=p.pakrefJabFungJenisrId)
   WHERE p.pakrefId =%s
";

$sql['get_combo_jabfungjenisrid'] = "
   SELECT
      jabfungjenisrId as `id`,
      jabfungJenis as `name`
   FROM
      pub_ref_jabatan_fungsional_jenis
";


//=================DO=======================//

$sql['do_add_pak'] ="
   INSERT INTO sdm_ref_pak_penilaian
   (pakrefNama, pakrefUnsur, pakrefAktif,pakrefJabFungJenisrId)
   VALUES
   ('%s','%s','%s','%s')
";

$sql['do_update_pak'] = "
   UPDATE sdm_ref_pak_penilaian
   SET
      pakrefNama = '%s',
      pakrefUnsur = '%s',
      pakrefAktif = '%s',
      pakrefJabFungJenisrId = '%s'
   WHERE
      pakrefId = %d
";

$sql['do_delete_pak_by_array_id'] = "
   DELETE FROM sdm_ref_pak_penilaian    
   WHERE
      pakrefId IN(%s)

";

$sql['do_delete_pak'] = "
   DELETE FROM sdm_ref_pak_penilaian    
   WHERE
      pakrefId='%s'

";

?>
