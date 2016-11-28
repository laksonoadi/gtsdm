<?php
$sql['get_count_pak'] = "
   SELECT
      count(kegiatanId) AS total
   FROM sdm_ref_pak_kegiatan   
";

$sql['get_list_pak'] = "
   SELECT 
      k.kegiatanId AS id,
      k.kegiatanNama AS nama,
	  k.kegiatanAngkaKredit AS angka_kredit,
	  u.unsurJenis as jenis_unsur,
      concat(u.unsurNama,'-',u.unsurKeterangan) AS unsur
   FROM sdm_ref_pak_kegiatan k
   JOIN sdm_ref_pak_unsur u ON (k.kegiatanUnsurId=u.unsurId)
   WHERE k.kegiatanNama LIKE '%s'  
   ORDER BY u.unsurJenis
   LIMIT %s, %s  
";


$sql['get_pak_by_id'] = "
  SELECT 
      k.kegiatanId AS id,
      k.kegiatanNama AS nama,
	  k.kegiatanAngkaKredit AS angka_kredit,
      concat(u.unsurNama,'-',u.unsurKeterangan) AS unsur,
	  u.unsurJenis as jenis_unsur,
	  u.unsurId AS unsurId
   FROM sdm_ref_pak_kegiatan k
   JOIN sdm_ref_pak_unsur u ON (k.kegiatanUnsurId=u.unsurId)
   WHERE k.kegiatanId =%s
";

$sql['get_combo_unsur'] = "
   SELECT
      unsurId as `id`,
      concat(unsurNama,'-',unsurKeterangan) as `name`
   FROM
      sdm_ref_pak_unsur
";


//=================DO=======================//

$sql['do_add_pak'] ="
   INSERT INTO sdm_ref_pak_kegiatan
   (kegiatanNama,kegiatanUnsurId,kegiatanAngkaKredit,kegiatanCreatedUserId,kegiatanCreatedDate)
   VALUES
   ('%s','%s','%s','%s',now())
";

$sql['do_update_pak'] = "
   UPDATE sdm_ref_pak_kegiatan
   SET
      kegiatanNama = '%s',
      kegiatanUnsurId = '%s',
	  kegiatanAngkaKredit = '%s',
      kegiatanModifiedUserId = '%s',
      kegiatanModifiedDate = now()
   WHERE
      kegiatanId = %d
";

$sql['do_delete_pak_by_array_id'] = "
   DELETE FROM sdm_ref_pak_kegiatan    
   WHERE
      kegiatanId IN(%s)

";

$sql['do_delete_pak'] = "
   DELETE FROM sdm_ref_pak_kegiatan    
   WHERE
      kegiatanId='%s'

";

?>
