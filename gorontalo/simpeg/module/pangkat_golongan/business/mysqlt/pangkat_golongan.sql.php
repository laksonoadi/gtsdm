<?php
$sql['get_count_pangkat_golongan'] = "
   SELECT
      count(pktgolrId) AS total
   FROM sdm_ref_pangkat_golongan   
";

$sql['get_list_pangkat_golongan'] = "
   SELECT 
      pktgolrId AS pangkat,
      pktgolrNama AS nama,
      pktgolrTingkat AS tingkat,
      pktgolrMasaKerja AS masa,
      pktgolrUrut AS urut
   from sdm_ref_pangkat_golongan
   WHERE pktgolrNama LIKE '%s'  
   ORDER by pktgolrUrut 
   LIMIT %s, %s  
";


$sql['get_pangkat_golongan_by_id'] = "
   SELECT 
      pktgolrId AS pangkat,
      pktgolrNama AS nama,
      pktgolrTingkat AS tingkat,
      pktgolrMasaKerja AS masa,
      pktgolrUrut AS urut
   from sdm_ref_pangkat_golongan
   WHERE pktgolrId =%s
";


//=================DO=======================//

$sql['do_add_pangkat_golongan'] ="
   INSERT INTO sdm_ref_pangkat_golongan
   (pktgolrId, pktgolrNama, pktgolrTingkat,pktgolrMasaKerja,pktgolrUrut)
   VALUES
   ('%s','%s','%s','%s','%s')
";

$sql['do_update_pangkat_golongan'] = "
   UPDATE sdm_ref_pangkat_golongan
   SET
      pktgolrId = '%s',
      pktgolrNama = '%s',
      pktgolrTingkat = '%s',
      pktgolrMasaKerja = '%s',
      pktgolrUrut = '%s'
   WHERE
      pktgolrId = %d
";

$sql['do_delete_pangkat_golongan_by_array_id'] = "
   DELETE FROM sdm_ref_pangkat_golongan    
   WHERE
      pktgolrId IN(%s)

";

$sql['do_delete_pangkat_golongan'] = "
   DELETE FROM sdm_ref_pangkat_golongan    
   WHERE
      pktgolrId='%s'

";

?>
