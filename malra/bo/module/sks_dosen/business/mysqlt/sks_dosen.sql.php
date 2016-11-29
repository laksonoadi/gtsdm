<?php
$sql['get_count_sks_dosen'] = "
   SELECT
      count(sksId) AS total
   FROM sdm_ref_sks_dosen   
";

$sql['get_list_sks_dosen'] = "
   SELECT 
      p.sksId AS id,
      p.sksTahun AS tahun,
      p.sksSemester AS semester,
      p.sksNominal AS nominal,
      p.sksIsAktif as status,
      t.jabfungrNama as jabfung
   from sdm_ref_sks_dosen p
   LEFT JOIN pub_ref_jabatan_fungsional t ON (t.jabfungrId=p.sksJabFungrId)
   WHERE p.sksTahun LIKE '%s'  
   ORDER by p.sksTahun
   LIMIT %s, %s  
";


$sql['get_sks_dosen_by_id'] = "
  SELECT 
      p.sksId AS id,
      p.sksTahun AS tahun,
      p.sksSemester AS semester,
      p.sksNominal AS nominal,
      p.sksIsAktif as status,
      p.sksJabFungrId as jfid
   from sdm_ref_sks_dosen p
   LEFT JOIN pub_ref_jabatan_fungsional t ON (t.jabfungrId=p.sksJabFungrId)
   WHERE p.sksId =%s
";

$sql['get_combo_jabfung'] = "
   SELECT
      jabfungrId as `id`,
      jabfungrNama as `name`
   FROM
      pub_ref_jabatan_fungsional
";

//=================DO=======================//

$sql['do_add_sks_dosen'] ="
   INSERT INTO sdm_ref_sks_dosen
   (sksTahun, sksSemester, sksNominal, sksIsAktif, sksJabFungrId)
   VALUES
   ('%s','%s','%s','%s','%s')
";

$sql['do_update_sks_dosen'] = "
   UPDATE sdm_ref_sks_dosen
   SET
      sksTahun = '%s',
      sksSemester = '%s',
      sksNominal ='%s',
      sksIsAktif = '%s',
      sksJabFungrId = '%s'
   WHERE
      sksId = %d
";

$sql['do_delete_sks_dosen_by_array_id'] = "
   DELETE FROM sdm_ref_sks_dosen    
   WHERE
      sksId IN(%s)

";

$sql['do_delete_sks_dosen'] = "
   DELETE FROM sdm_ref_sks_dosen    
   WHERE
      sksId='%s'

";

?>
