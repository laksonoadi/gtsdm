<?php
$sql['get_count_jabstruk'] = "
   SELECT
      count(jabstrukrId) AS total
   FROM sdm_ref_jabatan_struktural   
";

$sql['get_list_jabstruk'] = "
   SELECT 
      p.jabstrukrId AS id,
      p.jabstrukrNama AS nama,
      p.jabstrukrTingkat AS tingkat,
      p.jabstrukrBatasUsiaPensiun AS batas,
      t.tpstrNama as tipe,
      CONCAT(k.kompgajidtKode,' - ',k.kompgajidtNama) as kompgaji,
      p.jabstrukrAttachAnjab AS file_anjab
   from sdm_ref_jabatan_struktural p
   LEFT JOIN sdm_ref_tipe_struktural t ON (t.tpstrId=p.jabstrukTpstrId)
   LEFT JOIN sdm_ref_komponen_gaji_detail k ON (k.kompgajidtId=p.jabstrukKompgajidtId)
   WHERE p.jabstrukrNama LIKE '%s'  
   ORDER by p.jabstrukrNama
   LIMIT %s, %s  
";


$sql['get_jabstruk_by_id'] = "
  SELECT 
      p.jabstrukrId as id,
      p.jabstrukrNama AS nama,
      p.jabstrukrTingkat AS tingkat,
      p.jabstrukrBatasUsiaPensiun AS batas,
      p.jabstrukTpstrId as tsid,
      p.jabstrukKompgajidtId as kompid,
      p.jabstrukrSatker AS unit, 
      p.jabstrukrAttachAnjab AS file_anjab,
      CONCAT(kompgajidtKode,' - ',kompgajidtNama) as kompnama
   from sdm_ref_jabatan_struktural p
   LEFT JOIN sdm_ref_tipe_struktural t ON (t.tpstrId=p.jabstrukTpstrId)
   LEFT JOIN sdm_ref_komponen_gaji_detail k ON (k.kompgajidtId=p.jabstrukKompgajidtId)
   WHERE p.jabstrukrId =%s
";

$sql['get_combo_tpstrid'] = "
   SELECT
      tpstrId as `id`,
      tpstrNama as `name`
   FROM
      sdm_ref_tipe_struktural
";

//=================DO=======================//

$sql['do_add_jabstruk'] ="
   INSERT INTO sdm_ref_jabatan_struktural
   (jabstrukrNama, jabstrukrTingkat, jabstrukrBatasUsiaPensiun, jabstrukTpstrId, jabstrukKompgajidtId, jabstrukrSatker, jabstrukrAttachAnjab)
   VALUES
   ('%s','%s','%s','%s','%s','%s','%s')
";

$sql['do_update_jabstruk'] = "
   UPDATE sdm_ref_jabatan_struktural
   SET
      jabstrukrNama = '%s',
      jabstrukrTingkat = '%s',
      jabstrukrBatasUsiaPensiun = '%s',
      jabstrukTpstrId = '%s',
      jabstrukKompgajidtId = '%s',
      jabstrukrSatker = '%s',
      jabstrukrAttachAnjab = '%s'
   WHERE
      jabstrukrId = %d
";

$sql['do_delete_jabstruk_by_array_id'] = "
   DELETE FROM sdm_ref_jabatan_struktural    
   WHERE
      jabstrukrId IN(%s)

";

$sql['do_delete_jabstruk'] = "
   DELETE FROM sdm_ref_jabatan_struktural    
   WHERE
      jabstrukrId='%s'

";

$sql['get_unit_name'] = "
   SELECT `satkerNama` AS nama FROM `pub_satuan_kerja` WHERE `satkerId` = '%s'
";

$sql['get_tipe_jabatan'] = "
  SELECT `tpstrNama` AS nama FROM `sdm_ref_tipe_struktural` WHERE `tpstrId` = '%s'
";

?>
