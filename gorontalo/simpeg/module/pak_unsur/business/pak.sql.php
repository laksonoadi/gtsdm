<?php
$sql['get_count_pak'] = "
   SELECT
      count(unsurId) AS total
   FROM sdm_ref_pak_unsur   
";

$sql['get_list_pak'] = "
   SELECT 
      unsurId AS id,
      unsurNama AS nama,
	  unsurKeterangan AS keterangan,
      unsurJenis AS unsur
   FROM sdm_ref_pak_unsur
   WHERE unsurNama LIKE '%s'  
   ORDER BY unsurId
   LIMIT %s, %s  
";


$sql['get_pak_by_id'] = "
  SELECT 
      unsurId AS id,
      unsurNama AS nama,
	  unsurKeterangan AS keterangan,
      unsurJenis AS unsur
   FROM sdm_ref_pak_unsur
   WHERE unsurId =%s
";

//=================DO=======================//

$sql['do_add_pak'] ="
   INSERT INTO sdm_ref_pak_unsur
   (unsurNama, unsurJenis, unsurKeterangan,unsurCreatedUserId,unsurCreatedDate)
   VALUES
   ('%s','%s','%s','%s',now())
";

$sql['do_update_pak'] = "
   UPDATE sdm_ref_pak_unsur
   SET
      unsurNama = '%s',
      unsurJenis = '%s',
      unsurKeterangan = '%s',
      unsurModifiedUserId = '%s',
	  unsurModifiedDate = now()
   WHERE
      unsurId = %d
";

$sql['do_delete_pak_by_array_id'] = "
   DELETE FROM sdm_ref_pak_unsur    
   WHERE
      unsurId IN(%s)

";

$sql['do_delete_pak'] = "
   DELETE FROM sdm_ref_pak_unsur    
   WHERE
      unsurId='%s'

";

?>
